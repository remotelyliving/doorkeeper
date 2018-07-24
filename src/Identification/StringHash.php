<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class StringHash extends IdentificationAbstract
{
    public function validate($string)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException("{$string} is not a string");
        }
    }
}
