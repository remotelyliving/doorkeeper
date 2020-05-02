<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use RemotelyLiving\Doorkeeper\RequestorInterface;
use RemotelyLiving\Doorkeeper\Rules;

class TypeMapperTest extends TestCase
{
    public function testGetsIdForClassnameAndClassnameForId(): void
    {
        $extraType = new class extends Rules\AbstractRule {
            protected function childCanBeSatisfied(RequestorInterface $requestor = null): bool
            {
                return true;
            }
        };

        $extraTypeClass = get_class($extraType);
        $mapper = new Rules\TypeMapper([1001 => $extraTypeClass]);

        $this->assertEquals(
            Rules\TypeMapper::RULE_TYPE_ENVIRONMENT,
            $mapper->getIdForClassName(Rules\Environment::class)
        );

        $this->assertEquals(
            Rules\TypeMapper::RULE_TYPE_USER_ID,
            $mapper->getIdForClassName(Rules\UserId::class)
        )
        ;
        $this->assertEquals(
            Rules\TypeMapper::RULE_TYPE_STRING_HASH,
            $mapper->getIdForClassName(Rules\StringHash::class)
        );

        $this->assertEquals(
            Rules\TypeMapper::RULE_TYPE_RANDOM,
            $mapper->getIdForClassName(Rules\Random::class)
        );

        $this->assertEquals(
            Rules\TypeMapper::RULE_TYPE_PERCENTAGE,
            $mapper->getIdForClassName(Rules\Percentage::class)
        );

        $this->assertEquals(
            Rules\TypeMapper::RULE_TYPE_IP_ADDRESS,
            $mapper->getIdForClassName(Rules\IpAddress::class)
        );

        $this->assertEquals(
            Rules\TypeMapper::RULE_TYPE_HEADER,
            $mapper->getIdForClassName(Rules\HttpHeader::class)
        );

        $this->assertEquals(
            1001,
            $mapper->getIdForClassName($extraTypeClass)
        );

        $this->assertEquals(
            Rules\Environment::class,
            $mapper->getClassNameById(Rules\TypeMapper::RULE_TYPE_ENVIRONMENT)
        )
        ;
        $this->assertEquals(
            Rules\UserId::class,
            $mapper->getClassNameById(Rules\TypeMapper::RULE_TYPE_USER_ID)
        );

        $this->assertEquals(
            Rules\StringHash::class,
            $mapper->getClassNameById(Rules\TypeMapper::RULE_TYPE_STRING_HASH)
        );

        $this->assertEquals(
            Rules\Random::class,
            $mapper->getClassNameById(Rules\TypeMapper::RULE_TYPE_RANDOM)
        );

        $this->assertEquals(
            Rules\Percentage::class,
            $mapper->getClassNameById(Rules\TypeMapper::RULE_TYPE_PERCENTAGE)
        );

        $this->assertEquals(
            Rules\IpAddress::class,
            $mapper->getClassNameById(Rules\TypeMapper::RULE_TYPE_IP_ADDRESS)
        );

        $this->assertEquals(
            Rules\HttpHeader::class,
            $mapper->getClassNameById(Rules\TypeMapper::RULE_TYPE_HEADER)
        );

        $this->assertEquals(
            $extraTypeClass,
            $mapper->getClassNameById(1001)
        );
    }

    public function testPushesCustomIdClassValues(): void
    {
        $mapper = new Rules\TypeMapper();

        $mapper->pushExtraType(20002, \stdClass::class);

        $this->assertEquals(20002, $mapper->getIdForClassName(\stdClass::class));
    }

    public function testDoesNotAllowOverridesForIds(): void
    {
        $this->expectException(\DomainException::class);
        $mapper = new Rules\TypeMapper();

        $mapper->pushExtraType(1, \stdClass::class);
    }

    public function testDoesNotAllowForNonClassesToBePushed(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $mapper = new Rules\TypeMapper();

        $mapper->pushExtraType(10002, 'boopClass');
    }
}
