<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

final class UserId extends AbstractRule
{
    private Identification\UserId $userId;

    /**
     * @param string|int $userId
     */
    public function __construct($userId)
    {
        $this->userId = new Identification\UserId($userId);
    }

    public function getValue()
    {
        return $this->userId->getIdentifier();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return $this->requestorHasMatchingId($requestor, $this->userId);
    }
}
