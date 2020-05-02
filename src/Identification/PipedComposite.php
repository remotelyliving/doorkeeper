<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

final class PipedComposite extends AbstractIdentification
{
    protected function validate($value): void
    {
        if (!$value || mb_strpos((string)$value, '|') === false) {
            throw new \InvalidArgumentException("{$value} is not a pipe delimited composite");
        }
    }
}
