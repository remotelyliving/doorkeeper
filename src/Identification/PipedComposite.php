<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class PipedComposite extends IdentificationAbstract
{
    public function validate($value): void
    {
        if (!$value || !stristr($value, '|')) {
            throw new \InvalidArgumentException("{$value} is not a pipe delimited composite");
        }
    }
}
