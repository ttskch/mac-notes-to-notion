<?php

declare(strict_types=1);

namespace Ttskch\Mntn\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class FixCommand extends Command
{
    const OUTPUT_DIR_POSTFIX = '.fixed';

    public function configure(): void
    {
        $this
            ->setName('fix')
            ->setDescription('Fix markdown files')
            ->addArgument('dir', InputArgument::REQUIRED, '/path/to/dir/markdown/files/are/placed')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $inputDir = rtrim($input->getArgument('dir'), '/');
        $outputDir = $inputDir . self::OUTPUT_DIR_POSTFIX;

        /** @var \SplFileInfo[] $files */
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($inputDir, \FilesystemIterator::KEY_AS_PATHNAME|\FilesystemIterator::CURRENT_AS_FILEINFO|\FilesystemIterator::SKIP_DOTS));

        $count = 0;
        foreach ($files as $file) {
            $inputPathname = $file->getPathname();
            $outputPathname = str_replace($inputDir, $outputDir, $inputPathname);
            $outputPath = str_replace($inputDir, $outputDir, $file->getPath());

            if (!$file->isFile() || !preg_match('/\.md$/', $inputPathname)) {
                continue;
            }

            $content = file_get_contents($inputPathname);

            // remove first line
            $content = mb_eregi_replace('^#[^\n]+\n+', '', $content);

            // remove unnecessary underscores
            $content = mb_eregi_replace('^_+', '', $content);
            $content = mb_eregi_replace('\n_+', "\n", $content);
            $content = mb_eregi_replace('_+$', '', $content);
            $content = mb_eregi_replace('_+\n', "\n", $content);
            $content = mb_eregi_replace('_+(https?://)', '\1', $content);

            // fix line breaks
            $content = mb_eregi_replace('\n+', "\n\n", $content);

            // fix urls
            $content = mb_eregi_replace('https?://[^\s]+', '[\0](\0)', $content);

            // make output path
            if (!file_exists($outputPath)) {
                mkdir($outputPath, 0777, true);
            }

            // output
            file_put_contents($outputPathname, $content);

            $count++;
        }

        $io->info(sprintf('%s fixed files are saved under "%s"', $count, $outputDir));

        return 0;
    }
}
