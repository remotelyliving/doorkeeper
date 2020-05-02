<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules;

class UserIdTest extends TestCase
{
    /**
     * @dataProvider idProvider
     */
    public function testCanBeSatisfied($id): void
    {
        $rule = new Rules\UserId($id);
        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));

        $this->assertTrue($rule->canBeSatisfied($requestor->withUserId($id)));
    }

    public function testGetValue(): void
    {
        $this->assertEquals(234, (new Rules\UserId(234))->getValue());
    }

    public function idProvider(): array
    {
        return [
            'uuid' => ['4dd8ab53-162c-4681-930a-62879d9e4b5f'],
            'integer id' => [234],
            'string' => ['#hi'],
        ];
    }
}
