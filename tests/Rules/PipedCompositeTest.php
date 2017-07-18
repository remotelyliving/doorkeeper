<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules\PipedComposite;
use RemotelyLiving\Doorkeeper\Identification;

class PipedCompositeTest extends TestCase
{
    /**
     * @test
     */
    public function canBeSatisfied()
    {
        $rule           = new PipedComposite('Rule|1');
        $identification = new Identification\PipedComposite('Rule|1');
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $requestor->registerIdentification($identification);

        $this->assertTrue($rule->canBeSatisfied($requestor));
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals('A|B', (new PipedComposite('A|B'))->getValue());
    }
}
