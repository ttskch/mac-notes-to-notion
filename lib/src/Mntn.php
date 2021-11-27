<?php

declare(strict_types=1);

namespace Ttskch\Mntn;

use Symfony\Component\Console\Application;
use Ttskch\Mntn\Command\FixCommand;

final class Mntn
{
    public function run(): void
    {
        $console = new Application();
        $console->setName('mntn');
        $console->add(new FixCommand());
        $console->run();
    }
}
