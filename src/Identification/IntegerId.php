<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class IntegerId extends IdentificationAbstract
{
    public function validate($id)
    {
        if (!is_int($id) || $id < 0) {
            throw new \InvalidArgumentException("{$id} is not a positive integer id");
        }
    }
}
