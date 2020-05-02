<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

abstract class AbstractIdentification implements IdentificationInterface
{
    /**
     * @var string|int
     */
    private $identifier;

    private string $identityHash;

    private string $type;

    /**
     * @param string|int $identifier
     */
    final public function __construct($identifier)
    {
        if (!is_scalar($identifier) || is_bool($identifier)) {
            throw new \InvalidArgumentException('Identifier must be a scalar, alpha numeric value');
        }

        $this->validate($identifier);

        $this->identifier = $identifier;
        $this->type = get_class($this);
        $this->identityHash = self::createIdentityHash($this);
    }

    final public function getIdentifier()
    {
        return $this->identifier;
    }

    final public function getType(): string
    {
        return $this->type;
    }

    final public function equals(IdentificationInterface $identity): bool
    {
        return $this->identityHash === self::createIdentityHash($identity);
    }

    /**
     * @param string|int|float $value
     */
    abstract protected function validate($value): void;

    private static function createIdentityHash(IdentificationInterface $identification): string
    {
        return md5($identification->getType() . (string)$identification->getIdentifier());
    }
}
