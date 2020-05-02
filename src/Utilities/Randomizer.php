<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Utilities;

class Randomizer
{
    public function generateRangedRandomInt(int $lower, int $upper): int
    {
        return $this->mtRand($lower, $upper);
    }

    protected function mtRand(int $lower, int $upper): int
    {
        return mt_rand($lower, $upper);
    }
}
