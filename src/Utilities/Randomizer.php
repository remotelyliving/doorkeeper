<?php
namespace RemotelyLiving\Doorkeeper\Utilities;

class Randomizer
{
    /**
     * @param int $lower
     * @param int $upper
     *
     * @return int
     */
    public function generateRangedRandomInt(int $lower, int $upper): int
    {
        return $this->mtRand($lower, $upper);
    }

    /**
     * @param int $lower
     * @param int $upper
     *
     * @return int
     */
    protected function mtRand(int $lower, int $upper): int
    {
        return mt_rand($lower, $upper);
    }
}
