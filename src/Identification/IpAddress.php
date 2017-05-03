<?php
namespace RemotelyLiving\Doorkeeper\Identification;

class IpAddress extends IdentificationAbstract
{
    /**
     * @inheritdoc
     */
    public function validate($ip_address): void
    {
        if (!filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
          && !filter_var($ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            throw new \InvalidArgumentException("{$ip_address} is not a well formed ip address");
        }
    }
}
