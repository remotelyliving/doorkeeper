<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class StringHashTest extends TestCase
{
    public function testCanBeSatisfied(): void
    {
        $rule = new Rules\StringHash('1lk2j34lk');
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));
        $this->assertTrue($rule->canBeSatisfied($requestor->withStringHash('1lk2j34lk')));
    }

    public function testGetValue(): void
    {
        $this->assertEquals('#hash', (new Rules\StringHash('#hash'))->getValue());
    }
}
