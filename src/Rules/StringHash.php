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
     * @param string $feature_name
     * @param string $hash
     */
    public function __construct(string $feature_name, string $hash)
    {
        parent::__construct($feature_name);

        $this->hash = new Identification\StringHash($hash);
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        if (!$this->requestorHasIdentity($requestor, Identification\StringHash::class)) {
            return false;
        }

        return $requestor->getIdentifiationByClassName(Identification\StringHash::class)
            ->equals($this->hash);
    }
}
