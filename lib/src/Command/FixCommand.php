<?php

declare(strict_types=1);

namespace Ttskch\Mntn\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixCommand extends Command
{
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
        $dir = $input->getArgument('dir');
        var_dump($dir);

        return 0;
    }
}
