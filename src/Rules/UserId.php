<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class UserId extends RuleAbstract
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Identification\UserId
     */
    private $user_id;

    /**
     * @param string|int $user_id
     */
    public function __construct($user_id)
    {
        $this->user_id = new Identification\UserId($user_id);
    }

    public function getValue()
    {
        return $this->user_id->getIdentifier();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->user_id);
    }
}
