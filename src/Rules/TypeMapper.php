<?php
namespace RemotelyLiving\Doorkeeper\Rules;

/**
 * A type mapper if you choose to store integer types for persisted rules
 */
class TypeMapper
{
    const RULE_TYPE_HEADER          = 1;
    const RULE_TYPE_IP_ADDRESS      = 2;
    const RULE_TYPE_PERCENTAGE      = 3;
    const RULE_TYPE_RANDOM          = 4;
    const RULE_TYPE_STRING_HASH     = 5;
    const RULE_TYPE_USER_ID         = 6;
    const RULE_TYPE_ENVIRONMENT     = 7;
    const RULE_TYPE_BEFORE          = 8;
    const RULE_TYPE_AFTER           = 9;
    const RULE_TYPE_PIPED_COMPOSITE = 10;

    private $type_map = [
        self::RULE_TYPE_HEADER          => HttpHeader::class,
        self::RULE_TYPE_IP_ADDRESS      => IpAddress::class,
        self::RULE_TYPE_PERCENTAGE      => Percentage::class,
        self::RULE_TYPE_RANDOM          => Random::class,
        self::RULE_TYPE_STRING_HASH     => StringHash::class,
        self::RULE_TYPE_USER_ID         => UserId::class,
        self::RULE_TYPE_ENVIRONMENT     => Environment::class,
        self::RULE_TYPE_BEFORE          => TimeBefore::class,
        self::RULE_TYPE_AFTER           => TimeAfter::class,
        self::RULE_TYPE_PIPED_COMPOSITE => PipedComposite::class,
    ];

    public function __construct(array $extra_types = [])
    {
        foreach ($extra_types as $id => $name) {
            $this->pushExtraType($id, $name);
        }
    }

    public function getClassNameById(int $integer_id): string
    {
        return $this->type_map[$integer_id];
    }

    public function getIdForClassName(string $class_name): int
    {
        return array_flip($this->type_map)[$class_name];
    }

    public function pushExtraType(int $id, string $class_name)
    {

        if (isset($this->type_map[$id])) {
            throw new \DomainException("Type {$id} already set by parent");
        }

        if (!class_exists($class_name)) {
            throw new \InvalidArgumentException("{$class_name} is not a loaded class");
        }

        $this->type_map[$id] = $class_name;
    }
}
