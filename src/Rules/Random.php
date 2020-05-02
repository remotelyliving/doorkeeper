<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Utilities;

final class Random extends AbstractRule
{
    private Utilities\Randomizer $randomizer;

    public function __construct(Utilities\Randomizer $randomizer = null)
    {
        $this->randomizer = $randomizer ?? new Utilities\Randomizer();
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        return ($this->randomizer->generateRangedRandomInt(1, 100)
         === $this->randomizer->generateRangedRandomInt(1, 100));
    }
}
