<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules;

class FactoryTest extends TestCase
{
    private Rules\Factory $factory;

    protected function setUp(): void
    {
        $this->factory = new Rules\Factory();
    }

    public function testCreateFromArray(): void
    {
        $random = new Rules\Random();
        $percent = new Rules\Percentage(1);
        $stringHash = new Rules\StringHash('some hash');
        $userId = new Rules\UserId(123);
        $ipAddress = new Rules\IpAddress('127.0.0.1');
        $header = new Rules\HttpHeader('customHeader');
        $env = new Rules\Environment('DEV');
        $envWithPreReq = new Rules\Environment('DEV');
        $envWithPreReq->addPrerequisite($header);
        $envWithPreReq->addPrerequisite($userId);

        $time_before = new Rules\TimeBefore('2013-12-12');
        $time_after = new Rules\TimeAfter('2012-12-12');
        $factory = new Rules\Factory();

        $this->assertEquals($random, $factory->createFromArray([
          'type' => Rules\Random::class
        ]));

        $this->assertEquals($percent, $factory->createFromArray([
          'type' => Rules\Percentage::class, 'value' => 1,
        ]));

        $this->assertEquals($stringHash, $factory->createFromArray([
          'type' => Rules\StringHash::class, 'value' => 'some hash',
        ]));

        $this->assertEquals($userId, $factory->createFromArray([
          'type' => Rules\UserId::class, 'value' => 123,
        ]));

        $this->assertEquals($ipAddress, $factory->createFromArray([
          'type' => Rules\IpAddress::class, 'value' => '127.0.0.1',
        ]));

        $this->assertEquals($header, $factory->createFromArray([
            'type' => 'HttpHeader', 'value' => 'customHeader',
        ]));

        $this->assertEquals($env, $factory->createFromArray([
            'type' => Rules\TypeMapper::RULE_TYPE_ENVIRONMENT, 'value' => 'DEV',
        ]));

        $this->assertEquals($time_after, $factory->createFromArray([
            'type' => Rules\TypeMapper::RULE_TYPE_AFTER, 'value' => '2012-12-12',
        ]));

        $this->assertEquals($time_before, $factory->createFromArray([
            'type' => Rules\TypeMapper::RULE_TYPE_BEFORE, 'value' => '2013-12-12',
        ]));

        $this->assertEquals($envWithPreReq, $factory->createFromArray([
            'type' => Rules\TypeMapper::RULE_TYPE_ENVIRONMENT,
            'value' => 'DEV',
            'prerequisites' => [
                ['type' => Rules\HttpHeader::class, 'value' => 'customHeader'],
                ['type' => 'UserId', 'value' => 123],
            ],
        ]));
    }

    /**
     * @dataProvider invalidRuleTypeProvider
     */
    public function testThrowsOnInvalidRuleType($invalidType): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("{$invalidType} is not a valid rule type");
        $this->factory->createFromArray(['type' => $invalidType, 'value' => 123]);
    }

    public function invalidRuleTypeProvider(): array
    {
        return [
            'existing non lib class' => [\stdClass::class],
            'prefixed existing non lib class' => ['\\stdClass'],
            'non existent class' => ['boop'],
        ];
    }
}
