<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class StringHash extends IdentificationAbstract
{
    /**
     * @inheritdoc
     */
    public function validate($string): void
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException("{$string} is not a string");
        }
    }
}
