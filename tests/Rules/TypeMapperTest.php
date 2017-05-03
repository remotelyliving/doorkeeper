<?php
namespace RemotelyLiving\Doorkeeper\Tests\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\Rules\Environment;
use RemotelyLiving\Doorkeeper\Rules\HttpHeader;
use RemotelyLiving\Doorkeeper\Rules\IpAddress;
use RemotelyLiving\Doorkeeper\Rules\Percentage;
use RemotelyLiving\Doorkeeper\Rules\Random;
use RemotelyLiving\Doorkeeper\Rules\StringHash;
use RemotelyLiving\Doorkeeper\Rules\TypeMapper;
use RemotelyLiving\Doorkeeper\Rules\UserId;

class TypeMapperTest extends TestCase
{
    /**
     * @test
     */
    public function getsIdForClassnameAndClassnameForID()
    {
        $mapper = new TypeMapper();

        $this->assertEquals(TypeMapper::RULE_TYPE_ENVIRONMENT, $mapper->getIdForClassName(Environment::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_USER_ID, $mapper->getIdForClassName(UserId::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_STRING_HASH, $mapper->getIdForClassName(StringHash::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_RANDOM, $mapper->getIdForClassName(Random::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_PERCENTAGE, $mapper->getIdForClassName(Percentage::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_IP_ADDRESS, $mapper->getIdForClassName(IpAddress::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_HEADER, $mapper->getIdForClassName(HttpHeader::class));

        $this->assertEquals(Environment::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_ENVIRONMENT));
        $this->assertEquals(UserId::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_USER_ID));
        $this->assertEquals(StringHash::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_STRING_HASH));
        $this->assertEquals(Random::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_RANDOM));
        $this->assertEquals(Percentage::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_PERCENTAGE));
        $this->assertEquals(IpAddress::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_IP_ADDRESS));
        $this->assertEquals(HttpHeader::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_HEADER));
    }
}
