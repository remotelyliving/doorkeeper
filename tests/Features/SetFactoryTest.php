<?php
namespace RemotelyLiving\Tests\Doorkeeper\Features;

use RemotelyLiving\Doorkeeper\Features\Factory;
use RemotelyLiving\Doorkeeper\Features\Feature;
use RemotelyLiving\Doorkeeper\Features\Set;
use RemotelyLiving\Doorkeeper\Features\SetFactory;
use PHPUnit\Framework\TestCase;

class SetFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function createFromArray()
    {
        $feature         = $this->createMock(Feature::class);
        $feature_factory = $this->createMock(Factory::class);

        $feature_factory->method('createFromArray')
            ->willReturn($feature);

        $expected = new Set([ $feature, $feature ]);
        $actual   = (new SetFactory($feature_factory))->createFromArray([ ['feature'], ['feature'] ]);

        $this->assertEquals($expected, $actual);
    }
}
