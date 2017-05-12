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
    public function getId()
    {
        $feature = new Feature('someId', false);

        $this->assertSame('someId', $feature->getId());
    }

    /**
     * @test
     */
    public function getRules()
    {
        $feature = new Feature('someId', true);
        $rule = new Random('someId');

        $this->assertEmpty($feature->getRules());

        $feature->addRule($rule);

        $rules = $feature->getRules();

        $this->assertSame($rule, array_shift($rules));
    }

    /**
     * @test
     *
     * @expectedException \DomainException
     */
    public function protectsAgainstBadRuleBindings()
    {
        (new Feature('someId', true))->addRule(new Random('otherId'));
    }
}