<?php
namespace RemotelyLiving\Doorkeeper\Features;

interface SetProviderInterface
{
    /**
     * @return \RemotelyLiving\Doorkeeper\Features\Set
     */
    public function getFeatureSet(): Set;
}
