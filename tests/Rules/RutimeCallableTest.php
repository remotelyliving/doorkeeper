<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules\RuntimeCallable;

class RutimeCallableTest extends TestCase
{
    /**
     * @test
     */
    public function canBeSatisfied()
    {
        $callableTrue = new RuntimeCallable(function () {
            return true;
        });

        $callableFalse = new RuntimeCallable(function () {
            return false;
        });

        $callableWithRequestor = new RuntimeCallable(function (Requestor $requestor = null) {
            return (bool) $requestor;
        });

        $this->assertTrue($callableTrue->canBeSatisfied());
        $this->assertFalse($callableFalse->canBeSatisfied());
        $this->assertTrue($callableWithRequestor->canBeSatisfied(new Requestor()));
        $this->assertFalse($callableWithRequestor->canBeSatisfied());
    }

    /**
     * @test
     */
    public function getValue()
    {
        $fn = function () {
        };

        $this->assertNull((new RuntimeCallable($fn))->getValue());
    }
}
