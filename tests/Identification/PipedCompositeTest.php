<?php
namespace RemotelyLiving\Doorkeeper\Tests\Identification;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Identification\PipedComposite;

class PipedCompositeTest extends TestCase
{
    /**
     * @test
     * @dataProvider invalidIdProvider
     * @expectedException \InvalidArgumentException
     */
    public function validateInvalid($invalidId)
    {
        new PipedComposite($invalidId);
    }

    /**
     * @test
     */
    public function validateValid()
    {
        $this->assertInstanceOf(PipedComposite::class, (new PipedComposite('boop|beep')));
    }

    /**
     * @return array
     */
    public function invalidIdProvider(): array
    {
        return [
            ['1'],
            ['boop'],
            [(object)[]],
            [[]],
            [-1]
        ];
    }
}
