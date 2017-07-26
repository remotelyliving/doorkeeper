<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules\Environment;
use RemotelyLiving\Doorkeeper\Rules\HttpHeader;
use RemotelyLiving\Doorkeeper\Rules\TypeMapper;
use RemotelyLiving\Doorkeeper\Rules\UserId;

class EnvironmentTest extends TestCase
{
    /**
     * @test
     */
    public function canBeSatisfied()
    {
        $rule      = new Environment('DEV');
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertTrue($rule->canBeSatisfied($requestor->withEnvironment('DEV')));
        $this->assertFalse($rule->canBeSatisfied($requestor->withEnvironment('PROD')));
    }

    /**
     * @test
     */
    public function cannotBeSatisfiedWithFalseyPrereq()
    {
        $rule   = new Environment('DEV');
        $prereq = new HttpHeader('headerValue');

        $rule->addPrerequisite($prereq);

        $this->assertTrue($rule->hasPrerequisites());

        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertFalse($rule->canBeSatisfied($requestor->withEnvironment('DEV')));
    }

    /**
     * @test
     */
    public function canBeSatisfiedWithTruthyPrereq()
    {
        $rule   = new Environment('DEV');
        $prereq = new UserId(321);

        $rule->addPrerequisite($prereq);

        $this->assertTrue($rule->hasPrerequisites());

        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertTrue($rule->canBeSatisfied($requestor->withEnvironment('DEV')->withUserId(321)));
        $this->assertEquals([$prereq], $rule->getPrerequisites());
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals('dev', (new Environment('dev'))->getValue());
    }
}
