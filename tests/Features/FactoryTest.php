<?php
namespace RemotelyLiving\Doorkeeper\Tests\Features;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Features\Factory;
use RemotelyLiving\Doorkeeper\Features\Feature;
use RemotelyLiving\Doorkeeper\Rules\Random;

class FactoryTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider createFromArrayDataProvider
     *
     * @param array                                       $array
     * @param \RemotelyLiving\Doorkeeper\Features\Feature $expected
     */
    public function createFromArray(array $array, Feature $expected): void
    {
        $factory = new Factory();
        $this->assertEquals($factory->createFromArray($array), $expected);
    }

    /**
     * @return array
     */
    public function createFromArrayDataProvider(): array
    {
        return [
          'set 1' => [
            [ 'name' => 'boop', 'enabled' => true, 'rules' => [ [ 'feature_name' => 'boop', 'type' => Random::class ] ] ],
            new Feature('boop', true, [ new Random('boop') ]),
          ]
        ];
    }
}
