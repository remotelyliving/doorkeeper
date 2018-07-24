<?php
namespace RemotelyLiving\Doorkeeper\Identification;

use Ramsey\Uuid\Uuid;

class UserId extends IdentificationAbstract
{
    public function validate($value)
    {
        if (is_numeric($value)) {
            return;
        }

        if (Uuid::isValid($value)) {
            return;
        }

        throw new \InvalidArgumentException("{$value} is not an integer or uuid");
    }
}
