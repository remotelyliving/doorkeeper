<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules;
use RemotelyLiving\Doorkeeper\Tests\Stubs;

class AbstractTest extends TestCase
{
    private Rules\AbstractRule $abstractRule;

    protected function setUp(): void
    {
        $this->abstractRule = new Stubs\AbstractRule();
    }

    public function testJsonSerializes(): void
    {
        $this->abstractRule->addPrerequisite(new Rules\Random());
        // phpcs:disable
        $expected = '{"type":"RemotelyLiving\\\Doorkeeper\\\Tests\\\Stubs\\\AbstractRule","value":"mockValue","prerequisites":[{"type":"RemotelyLiving\\\Doorkeeper\\\Rules\\\Random","value":null,"prerequisites":[]}]}';
        // phpcs:enable

        $this->assertEquals($expected, json_encode($this->abstractRule));
    }
}
