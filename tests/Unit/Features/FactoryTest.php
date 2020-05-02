<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Features;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Features;
use RemotelyLiving\Doorkeeper\Rules;

class FactoryTest extends TestCase
{
    /**
     * @dataProvider createFromArrayDataProvider
     */
    public function testCreateFromArray(array $array, Features\Feature $expected): void
    {
        $factory = new Features\Factory();
        $this->assertEquals($factory->createFromArray($array), $expected);
    }

    public function createFromArrayDataProvider(): array
    {
        return [
          'feature 1' => [
            ['name' => 'boop', 'enabled' => true, 'rules' => [['type' => Random::class]]],
            new Features\Feature('boop', true, [new Rules\Random()]),
          ],
          'feature 2 no rules' => [
              ['name' => 'boop', 'enabled' => true],
              new Features\Feature('boop', true),
          ],
        ];
    }
}
