<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class StringHash extends RuleAbstract
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Identification\StringHash
     */
    private $hash;

    /**
     * @param string $hash
     */
    public function __construct(string $hash)
    {
        $this->hash = new Identification\StringHash($hash);
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->hash->getIdentifier();
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->hash);
    }
}
