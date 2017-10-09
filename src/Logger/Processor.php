<?php
namespace RemotelyLiving\Doorkeeper\Logger;

use RemotelyLiving\Doorkeeper\Identification\Collection;
use RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract;
use RemotelyLiving\Doorkeeper\Requestor;

class Processor
{
    const CONTEXT_KEY_REQUESTOR   = Requestor::class;
    const CONTEXT_KEY_IDENTIFIERS = 'identifiers';
    const FEATURE_ID              = 'feature_id';

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
        if (!isset($record['context'][static::CONTEXT_KEY_REQUESTOR])
            || !$record['context'][static::CONTEXT_KEY_REQUESTOR] instanceof Requestor) {
            return $record;
        }

        /** @var Requestor $requestor */
        $requestor = $record['context'][static::CONTEXT_KEY_REQUESTOR];
        $requestor_context = [];

        /** @var Collection $identification */
        foreach ($requestor->getIdentificationCollections() as $identification_collection) {
            foreach ($identification_collection as $id) {
                if (isset($this->filtered_identities[get_class($id)])) {
                    continue;
                }
                $key = $this->getKeyFromIdentification($id);
                $requestor_context[self::CONTEXT_KEY_IDENTIFIERS][$key][] = $id->getIdentifier();
            }
        }

        $requestor_context = $this->flattenIdCollections($requestor_context);
        $record['context'][static::CONTEXT_KEY_REQUESTOR] = $requestor_context;

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

    private function flattenIdCollections(array $requestor_context): array
    {
        if (!isset($requestor_context[self::CONTEXT_KEY_IDENTIFIERS])) {
            return $requestor_context;
        }
        $flattened = [];

        foreach ($requestor_context[self::CONTEXT_KEY_IDENTIFIERS] as $id_name => $identifiers) {
            $flattened[$id_name] = $identifiers;
        }

        $requestor_context[self::CONTEXT_KEY_IDENTIFIERS] = json_encode($flattened);

        return $requestor_context;
    }
}
