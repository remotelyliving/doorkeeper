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
        $feature_id  = 'the feature';
        $random      = new Random($feature_id);
        $percent     = new Percentage($feature_id, 1);
        $string_hash = new StringHash($feature_id, 'some hash');
        $user_id     = new UserId($feature_id, 123);
        $ip_address  = new IpAddress($feature_id, '127.0.0.1');
        $header      = new HttpHeader($feature_id, 'customHeader');
        $env         = new Environment($feature_id, 'DEV');
        $env_w_prq   = new Environment($feature_id, 'DEV');
        $env_w_prq->setPrerequisite($header);

        $time_before = new TimeBefore($feature_id, '2013-12-12');
        $time_after  = new TimeAfter($feature_id, '2012-12-12');
        $factory     = new Factory();

        $this->assertEquals($random, $factory->createFromArray([
          'feature_id' => $feature_id, 'type' => Random::class
        ]));

        $this->assertEquals($percent, $factory->createFromArray([
          'feature_id' => $feature_id, 'type' => Percentage::class, 'value' => 1,
        ]));

        $this->assertEquals($string_hash, $factory->createFromArray([
          'feature_id' => $feature_id, 'type' => StringHash::class, 'value' => 'some hash',
        ]));

        $this->assertEquals($user_id, $factory->createFromArray([
          'feature_id' => $feature_id, 'type' => UserId::class, 'value' => 123,
        ]));

        $this->assertEquals($ip_address, $factory->createFromArray([
          'feature_id' => $feature_id, 'type' => IpAddress::class, 'value' => '127.0.0.1',
        ]));

        $this->assertEquals($header, $factory->createFromArray([
            'feature_id' => $feature_id, 'type' => HttpHeader::class, 'value' => 'customHeader',
        ]));

        $this->assertEquals($env, $factory->createFromArray([
            'feature_id' => $feature_id, 'type' => TypeMapper::RULE_TYPE_ENVIRONMENT, 'value' => 'DEV',
        ]));

        $this->assertEquals($time_after, $factory->createFromArray([
            'feature_id' => $feature_id, 'type' => TypeMapper::RULE_TYPE_AFTER, 'value' => '2012-12-12',
        ]));

        $this->assertEquals($time_before, $factory->createFromArray([
            'feature_id' => $feature_id, 'type' => TypeMapper::RULE_TYPE_BEFORE, 'value' => '2013-12-12',
        ]));

        $this->assertEquals($env_w_prq, $factory->createFromArray([
            'feature_id' => $feature_id,
            'type' => TypeMapper::RULE_TYPE_ENVIRONMENT,
            'value' => 'DEV',
            'prerequisite' => ['feature_id' => $feature_id, 'type' => HttpHeader::class, 'value' => 'customHeader'],
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
