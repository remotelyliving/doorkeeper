<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Features;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Features;
use RemotelyLiving\Doorkeeper\Rules;

class FeatureTest extends TestCase
{
    public function testIsEnabled(): void
    {
        $disabled = new Features\Feature('boop', false);
        $enabled = new Features\Feature('beep', true);

        $this->assertTrue($enabled->isEnabled());
        $this->assertFalse($disabled->isEnabled());
    }

    public function testGetsId(): void
    {
        $feature = new Features\Feature('someId', false);

        $this->assertSame('someId', $feature->getName());
    }

    public function testGetsRules(): void
    {
        $feature = new Features\Feature('someId', true);
        $rule = new Rules\Random();

        $this->assertEmpty($feature->getRules());

        $feature->addRule($rule);

        $rules = $feature->getRules();

        $this->assertSame($rule, array_shift($rules));
    }

    public function testJsonSerializes(): void
    {
        $feature = new Features\Feature('someId', true);
        $this->assertEquals('{"name":"someId","enabled":true,"rules":[]}', json_encode($feature));
    }
}
