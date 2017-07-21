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
    public function createFromArray(array $array, Feature $expected)
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
          'feature 1' => [
            [ 'name' => 'boop', 'enabled' => true, 'rules' => [ [ 'type' => Random::class ] ] ],
            new Feature('boop', true, [ new Random() ]),
          ],
          'feature 2 no rules' => [
              [ 'name' => 'boop', 'enabled' => true ],
              new Feature('boop', true),
          ],
        ];
    }
}
