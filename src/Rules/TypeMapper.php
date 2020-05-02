<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

/**
 * A type mapper if you choose to store integer types for persisted rules
 */
final class TypeMapper
{
    public const RULE_TYPE_HEADER = 1;
    public const RULE_TYPE_IP_ADDRESS = 2;
    public const RULE_TYPE_PERCENTAGE = 3;
    public const RULE_TYPE_RANDOM = 4;
    public const RULE_TYPE_STRING_HASH = 5;
    public const RULE_TYPE_USER_ID = 6;
    public const RULE_TYPE_ENVIRONMENT = 7;
    public const RULE_TYPE_BEFORE = 8;
    public const RULE_TYPE_AFTER = 9;
    public const RULE_TYPE_PIPED_COMPOSITE = 10;

    private array $typeMap = [
        self::RULE_TYPE_HEADER => HttpHeader::class,
        self::RULE_TYPE_IP_ADDRESS => IpAddress::class,
        self::RULE_TYPE_PERCENTAGE => Percentage::class,
        self::RULE_TYPE_RANDOM => Random::class,
        self::RULE_TYPE_STRING_HASH => StringHash::class,
        self::RULE_TYPE_USER_ID => UserId::class,
        self::RULE_TYPE_ENVIRONMENT => Environment::class,
        self::RULE_TYPE_BEFORE => TimeBefore::class,
        self::RULE_TYPE_AFTER => TimeAfter::class,
        self::RULE_TYPE_PIPED_COMPOSITE => PipedComposite::class,
    ];

    public function __construct(array $extraTypes = [])
    {
        foreach ($extraTypes as $id => $name) {
            $this->pushExtraType($id, $name);
        }
    }

    public function getClassNameById(int $integerId): string
    {
        return $this->typeMap[$integerId];
    }

    public function getIdForClassName(string $className): int
    {
        return array_flip($this->typeMap)[$className];
    }

    public function pushExtraType(int $id, string $className): void
    {

        if (isset($this->typeMap[$id])) {
            throw new \DomainException("Type {$id} already set by parent");
        }

        if (!class_exists($className)) {
            throw new \InvalidArgumentException("{$className} is not a loaded class");
        }

        $this->typeMap[$id] = $className;
    }
}
