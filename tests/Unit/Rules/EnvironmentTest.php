<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class EnvironmentTest extends TestCase
{
    public function testCanBeSatisfied()
    {
        $rule = new Rules\Environment('DEV');
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertTrue($rule->canBeSatisfied($requestor->withEnvironment('DEV')));
        $this->assertFalse($rule->canBeSatisfied($requestor->withEnvironment('PROD')));
    }

    public function testCannotBeSatisfiedWithFalseyPrereq()
    {
        $rule = new Rules\Environment('DEV');
        $prereq = new Rules\HttpHeader('headerValue');

        $rule->addPrerequisite($prereq);

        $this->assertTrue($rule->hasPrerequisites());

        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertFalse($rule->canBeSatisfied($requestor->withEnvironment('DEV')));
    }

    public function testCanBeSatisfiedWithTruthyPrereq(): void
    {
        $rule = new Rules\Environment('DEV');
        $prereq = new Rules\UserId(321);

        $rule->addPrerequisite($prereq);

        $this->assertTrue($rule->hasPrerequisites());

        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertTrue($rule->canBeSatisfied($requestor->withEnvironment('DEV')->withUserId(321)));
        $this->assertEquals([$prereq], $rule->getPrerequisites());
    }

    public function testGetValue()
    {
        $this->assertEquals('dev', (new Rules\Environment('dev'))->getValue());
    }
}
