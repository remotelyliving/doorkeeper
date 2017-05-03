<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class IntegerId extends IdentificationAbstract
{
    /**
     * @inheritdoc
     */
    public function validate($id): void
    {
        if (!is_int($id) || $id < 0) {
            throw new \InvalidArgumentException("{$id} is not a positive integer id");
        }
    }
}
