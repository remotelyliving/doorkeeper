<?php
namespace RemotelyLiving\Tests\Doorkeeper\Features;

use RemotelyLiving\Doorkeeper\Features\Feature;
use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules\Random;

class FeatureTest extends TestCase
{
    /**
     * @test
     */
    public function isEnabled()
    {
        $disabled = new Feature('boop', false);
        $enabled  = new Feature('beep', true);

        $this->assertTrue($enabled->isEnabled());
        $this->assertFalse($disabled->isEnabled());
    }

    /**
     * @test
     */
    public function getsId()
    {
        $feature = new Feature('someId', false);

        $this->assertSame('someId', $feature->getName());
    }

    /**
     * @test
     */
    public function getsRules()
    {
        $feature = new Feature('someId', true);
        $rule = new Random();

        $this->assertEmpty($feature->getRules());

        $feature->addRule($rule);

        $rules = $feature->getRules();

        $this->assertSame($rule, array_shift($rules));
    }

    /**
     * @test
     */
    public function jsonSerializes()
    {
        $feature = new Feature('someId', true);
        $this->assertEquals('{"name":"someId","enabled":true,"rules":[]}', json_encode($feature));
    }
}
