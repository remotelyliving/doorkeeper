<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class RuntimeCallableTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $callableTrue = new Rules\RuntimeCallable(function () {
            return true;
        });

        $callableFalse = new Rules\RuntimeCallable(function () {
            return false;
        });

        $callableWithRequestor = new Rules\RuntimeCallable(function (Requestor $requestor = null) {
            return (bool) $requestor;
        });

        $this->assertTrue($callableTrue->canBeSatisfied());
        $this->assertFalse($callableFalse->canBeSatisfied());
        $this->assertTrue($callableWithRequestor->canBeSatisfied(new Requestor()));
        $this->assertFalse($callableWithRequestor->canBeSatisfied());
    }

    public function testGetValue(): void
    {
        $fn = fn() => null;
        $this->assertNull((new Rules\RuntimeCallable($fn))->getValue());
    }
}
