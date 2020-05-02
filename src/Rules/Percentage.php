<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

use RemotelyLiving\Doorkeeper\Requestor;
use RemotelyLiving\Doorkeeper\Utilities;

final class Percentage extends AbstractRule
{
    private int $chances;

    private Utilities\Randomizer $randomizer;

    public function __construct(int $percentage, Utilities\Randomizer $randomizer = null)
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException("Percentage must be represented as a value from 1 to 100");
        }

        $this->chances = $percentage;
        $this->randomizer = $randomizer ?? new Utilities\Randomizer();
    }

    public function getValue()
    {
        return $this->chances;
    }

    protected function childCanBeSatisfied(Requestor $requestor = null): bool
    {
        $lotteryNumber = $this->randomizer->generateRangedRandomInt(1, 100);

        if ($this->chances === 100) {
            return true;
        }

        return $lotteryNumber <= $this->chances;
    }
}
