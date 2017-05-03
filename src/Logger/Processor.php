<?php
namespace RemotelyLiving\Doorkeeper\Logger;

use RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract;
use RemotelyLiving\Doorkeeper\Requestor;

class Processor
{
    public const CONTEXT_KEY_REQUESTOR = Requestor::class;
    public const FEATURE_ID            = 'feature_id';

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        if (!isset($record['context'][self::CONTEXT_KEY_REQUESTOR])
            || !$record['context'][self::CONTEXT_KEY_REQUESTOR] instanceof Requestor) {
            return $record;
        }

        /** @var Requestor $requestor */
        $requestor = $record['context'][self::CONTEXT_KEY_REQUESTOR];
        $requestor_context = [];

        foreach ($requestor->getIdentifications() as $identification) {
            $requestor_context[$this->getKeyFromIdentification($identification)] = $identification->getIdentifier();
        }

        $record['context'][self::CONTEXT_KEY_REQUESTOR] = $requestor_context;

        return $record;
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract $identity
     *
     * @return string
     */
    private function getKeyFromIdentification(IdentificationAbstract $identity): string
    {
        $class_paths = explode('\\', get_class($identity));

        return array_pop($class_paths);
    }
}
