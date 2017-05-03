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
     * @param string $feature_id
     * @param string $hash
     */
    public function __construct(string $feature_id, string $hash)
    {
        parent::__construct($feature_id);

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
