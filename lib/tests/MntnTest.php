<?php

declare(strict_types=1);

namespace Ttskch\Mntn;

use PHPUnit\Framework\TestCase;

class MntnTest extends TestCase
{
    /** @var Mntn */
    protected $mntn;

    protected function setUp(): void
    {
        $this->mntn = new Mntn();
    }

    public function testIsInstanceOfMntn(): void
    {
        $actual = $this->mntn;
        $this->assertInstanceOf(Mntn::class, $actual);
    }
}
