<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Rules\UserId;

class UserIdTest extends TestCase
{
    /**
     * @test
     * @dataProvider idProvider
     */
    public function canBeSatisfied($id)
    {
        $rule      = new UserId($id);

        $requestor = new Requestor();

        $this->assertFalse($rule->canBeSatisfied());
        $this->assertFalse($rule->canBeSatisfied($requestor));

        $this->assertTrue($rule->canBeSatisfied($requestor->withUserId($id)));
    }

    /**
     * @test
     */
    public function getValue()
    {
        $this->assertEquals(234, (new UserId(234))->getValue());
    }

    /**
     * @return array
     */
    public function idProvider(): array
    {
        return [
            'uuid' => ['4dd8ab53-162c-4681-930a-62879d9e4b5f'],
            'integer id' => [234]
        ];
    }
}
