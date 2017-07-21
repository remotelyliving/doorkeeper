<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class Environment extends IdentificationAbstract
{
    /**
     * @inheritdoc
     */
    public function validate($string)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException("{$string} is not a string");
        }
    }
}
