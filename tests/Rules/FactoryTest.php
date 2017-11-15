<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;

use RemotelyLiving\Doorkeeper\Rules\Environment;
use RemotelyLiving\Doorkeeper\Rules\Factory;
use RemotelyLiving\Doorkeeper\Rules\HttpHeader;
use RemotelyLiving\Doorkeeper\Rules\IpAddress;
use RemotelyLiving\Doorkeeper\Rules\Percentage;
use RemotelyLiving\Doorkeeper\Rules\Random;
use RemotelyLiving\Doorkeeper\Rules\StringHash;
use RemotelyLiving\Doorkeeper\Rules\TimeAfter;
use RemotelyLiving\Doorkeeper\Rules\TimeBefore;
use RemotelyLiving\Doorkeeper\Rules\TypeMapper;
use RemotelyLiving\Doorkeeper\Rules\UserId;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new Factory();
    }

    /**
     * @test
     */
    public function createFromArray()
    {
        $random      = new Random();
        $percent     = new Percentage(1);
        $string_hash = new StringHash('some hash');
        $user_id     = new UserId(123);
        $ip_address  = new IpAddress('127.0.0.1');
        $header      = new HttpHeader('customHeader');
        $env         = new Environment('DEV');
        $env_w_prq   = new Environment('DEV');
        $env_w_prq->addPrerequisite($header);
        $env_w_prq->addPrerequisite($user_id);

        $time_before = new TimeBefore('2013-12-12');
        $time_after  = new TimeAfter('2012-12-12');
        $factory     = new Factory();

        $this->assertEquals($random, $factory->createFromArray([
          'type' => Random::class
        ]));

        $this->assertEquals($percent, $factory->createFromArray([
          'type' => Percentage::class, 'value' => 1,
        ]));

        $this->assertEquals($string_hash, $factory->createFromArray([
          'type' => StringHash::class, 'value' => 'some hash',
        ]));

        $this->assertEquals($user_id, $factory->createFromArray([
          'type' => UserId::class, 'value' => 123,
        ]));

        $this->assertEquals($ip_address, $factory->createFromArray([
          'type' => IpAddress::class, 'value' => '127.0.0.1',
        ]));

        $this->assertEquals($header, $factory->createFromArray([
            'type' => 'HttpHeader', 'value' => 'customHeader',
        ]));

        $this->assertEquals($env, $factory->createFromArray([
            'type' => TypeMapper::RULE_TYPE_ENVIRONMENT, 'value' => 'DEV',
        ]));

        $this->assertEquals($time_after, $factory->createFromArray([
            'type' => TypeMapper::RULE_TYPE_AFTER, 'value' => '2012-12-12',
        ]));

        $this->assertEquals($time_before, $factory->createFromArray([
            'type' => TypeMapper::RULE_TYPE_BEFORE, 'value' => '2013-12-12',
        ]));

        $this->assertEquals($env_w_prq, $factory->createFromArray([
            'type' => TypeMapper::RULE_TYPE_ENVIRONMENT,
            'value' => 'DEV',
            'prerequisites' => [
                ['type' => HttpHeader::class, 'value' => 'customHeader'],
                ['type' => 'UserId', 'value' => 123],
            ],
        ]));
    }

    /**
     * @test
     *
     * @dataProvider invalidRuleTypeProvider
     */
    public function throwsOnInvalidRuleType($invalidType)
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
