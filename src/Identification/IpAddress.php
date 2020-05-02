<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

final class IpAddress extends AbstractIdentification
{
    protected function validate($ipAddress): void
    {
        if (
            !filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            && !filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)
        ) {
            throw new \InvalidArgumentException("{$ipAddress} is not a well formed ip address");
        }
    }
}
