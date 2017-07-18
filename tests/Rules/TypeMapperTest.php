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
        $extra_type = new class extends Random {
        };

        $extra_type_class = get_class($extra_type);
        $mapper = new TypeMapper([1001 => $extra_type_class]);

        $this->assertEquals(TypeMapper::RULE_TYPE_ENVIRONMENT, $mapper->getIdForClassName(Environment::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_USER_ID, $mapper->getIdForClassName(UserId::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_STRING_HASH, $mapper->getIdForClassName(StringHash::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_RANDOM, $mapper->getIdForClassName(Random::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_PERCENTAGE, $mapper->getIdForClassName(Percentage::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_IP_ADDRESS, $mapper->getIdForClassName(IpAddress::class));
        $this->assertEquals(TypeMapper::RULE_TYPE_HEADER, $mapper->getIdForClassName(HttpHeader::class));
        $this->assertEquals(1001, $mapper->getIdForClassName($extra_type_class));

        $this->assertEquals(Environment::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_ENVIRONMENT));
        $this->assertEquals(UserId::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_USER_ID));
        $this->assertEquals(StringHash::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_STRING_HASH));
        $this->assertEquals(Random::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_RANDOM));
        $this->assertEquals(Percentage::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_PERCENTAGE));
        $this->assertEquals(IpAddress::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_IP_ADDRESS));
        $this->assertEquals(HttpHeader::class, $mapper->getClassNameById(TypeMapper::RULE_TYPE_HEADER));
        $this->assertEquals($extra_type_class, $mapper->getClassNameById(1001));
    }

    /**
     * @test
     */
    public function pushesCustomIdClassValues()
    {
        $mapper = new TypeMapper();

        $mapper->pushExtraType(20002, \stdClass::class);

        $this->assertEquals(20002, $mapper->getIdForClassName(\stdClass::class));
    }

    /**
     * @test
     * @expectedException \DomainException
     */
    public function doesNotAllowOverridesForIds()
    {
        $mapper = new TypeMapper();

        $mapper->pushExtraType(1, \stdClass::class);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function doesNotAllowForNonClassesToBePushed()
    {
        $mapper = new TypeMapper();

        $mapper->pushExtraType(10002, 'boopClass');
    }
}
