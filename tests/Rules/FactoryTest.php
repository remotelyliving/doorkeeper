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
        $env_w_prq->setPrerequisite($header);

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
            'type' => HttpHeader::class, 'value' => 'customHeader',
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
            'prerequisite' => ['type' => HttpHeader::class, 'value' => 'customHeader'],
        ]));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function invalidType()
    {
        (new Factory())->createFromArray(['type' => 'Bippity']);
    }
}
