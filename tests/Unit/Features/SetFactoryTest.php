<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Features;

use RemotelyLiving\Doorkeeper\Features;
use PHPUnit\Framework\TestCase;

class SetFactoryTest extends TestCase
{
    public function testCreateFromArray(): void
    {
        $feature1 = new Features\Feature('feature1', false);
        $feature2 = new Features\Feature('feature2', true);
        $expected = new Features\Set([$feature1, $feature2]);

        $actual = (new Features\SetFactory(new Features\Factory()))
          ->createFromArray([
            ['name' => 'feature1', 'enabled' => false],
            ['name' => 'feature2', 'enabled' => true],
           ]);

        $this->assertEquals($expected, $actual);
    }
}
