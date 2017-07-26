<?php
namespace RemotelyLiving\Doorkeeper\Logger;

use RemotelyLiving\Doorkeeper\Identification\Collection;
use RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract;
use RemotelyLiving\Doorkeeper\Requestor;

class Processor
{
    const CONTEXT_KEY_REQUESTOR = Requestor::class;
    const FEATURE_ID            = 'feature_id';

    /**
     * @var string[]
     */
    private $filtered_identities = [];

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

        /** @var Collection $identification */
        foreach ($requestor->getIdentificationCollections() as $identification_collection) {
            foreach ($identification_collection as $id) {
                if (isset($this->filtered_identities[get_class($id)])) {
                    continue;
                }

                $requestor_context[$this->getKeyFromIdentification($id)] = $id->getIdentifier();
            }
        }

        $record['context'][self::CONTEXT_KEY_REQUESTOR] = $requestor_context;

        return $record;
    }

    /**
     * @param array $class_names
     */
    public function setFilteredIdentities(array $class_names)
    {
        $this->filtered_identities = array_flip($class_names);
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
