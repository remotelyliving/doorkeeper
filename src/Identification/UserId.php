<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class UserId extends IdentificationAbstract
{
    public function validate($value)
    {
        if (is_numeric($value)) {
            return;
        }

        $uuidPattern = '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/';
        if (preg_match($uuidPattern, $value)) {
            return;
        }

        throw new \InvalidArgumentException("{$value} is not an integer or uuid");
    }
}
