<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;
use RemotelyLiving\Doorkeeper\Identification;

class PipedCompositeTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $rule = new Rules\PipedComposite('Rule|1');
        $identification = new Identification\PipedComposite('Rule|1');
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $requestor->registerIdentification($identification);

        $this->assertTrue($rule->canBeSatisfied($requestor));
    }

    public function testGetValue(): void
    {
        $this->assertEquals('A|B', (new Rules\PipedComposite('A|B'))->getValue());
    }
}
