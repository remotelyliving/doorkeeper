<?php
namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

class UserId extends RuleAbstract
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Identification\IntegerId
     */
    private $user_id;

    /**
     * @param int $user_id
     */
    public function __construct(int $user_id)
    {
        $this->user_id = new Identification\IntegerId($user_id);
    }

    /**
     * @inheritdoc
     */
    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        if (!$this->requestorHasIdentity($requestor, Identification\IntegerId::class)) {
            return false;
        }

        return $requestor->getIdentifiationByClassName(Identification\IntegerId::class)
            ->equals($this->user_id);
    }
}
