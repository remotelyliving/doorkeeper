<?php
namespace RemotelyLiving\Doorkeeper\Identification;

abstract class IdentificationAbstract
{
    /**
     * @var int|string
     */
    private $identifier;

    /**
     * @var mixed
     */
    private $identity_hash;

    /**
     * @param string $identifier
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($identifier)
    {
        if (!is_scalar($identifier) || is_bool($identifier)) {
            throw new \InvalidArgumentException('Identifier must be a scalar, alpha numeric value');
        }

        $this->validate($identifier);

        $this->identifier    = $identifier;
        $this->identity_hash = md5(get_class($this) . (string)$identifier);
    }

    /**
     * @return mixed
     */
    final public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract $identity
     *
     * @return bool
     */
    final public function equals(IdentificationAbstract $identity): bool
    {
        return $this->getUniqueIdentityHash() === $identity->getUniqueIdentityHash();
    }

    /**
     * @throws \InvalidArgumentException
     */
    abstract protected function validate($value): void;
    /**
     * @return mixed
     */
    private function getUniqueIdentityHash(): string
    {
        return $this->identity_hash;
    }
}
