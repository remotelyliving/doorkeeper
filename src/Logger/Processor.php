<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Logger;

use RemotelyLiving\Doorkeeper\Identification;
use RemotelyLiving\Doorkeeper\Requestor;

final class Processor
{
    public const CONTEXT_KEY_REQUESTOR = Requestor::class;
    public const CONTEXT_KEY_IDENTIFIERS = 'identifiers';
    public const FEATURE_ID = 'featureId';

    /**
     * @var string[]
     */
    private $filteredIdentities = [];

    public function __invoke(array $record): array
    {
        if (
            !isset($record['context'][static::CONTEXT_KEY_REQUESTOR])
            || !$record['context'][static::CONTEXT_KEY_REQUESTOR] instanceof Requestor
        ) {
            return $record;
        }

        /** @var Requestor $requestor */
        $requestor = $record['context'][static::CONTEXT_KEY_REQUESTOR];
        $requestorContext = [];

        foreach ($requestor->getIdentificationCollections() as $identificationCollection) {
            foreach ($identificationCollection as $id) {
                if (isset($this->filteredIdentities[get_class($id)])) {
                    continue;
                }
                $key = $this->getKeyFromIdentification($id);
                $requestorContext[self::CONTEXT_KEY_IDENTIFIERS][$key][] = $id->getIdentifier();
            }
        }

        $requestorContext = $this->flattenIdCollections($requestorContext);
        $record['context'][static::CONTEXT_KEY_REQUESTOR] = $requestorContext;

        return $record;
    }

    public function setFilteredIdentityTypes(array $idTypes): void
    {
        $this->filteredIdentities = array_flip($idTypes);
    }

    private function getKeyFromIdentification(Identification\IdentificationInterface $identity): string
    {
        $classPaths = explode('\\', $identity->getType());

        return (string) array_pop($classPaths);
    }

    private function flattenIdCollections(array $requestorContext): array
    {
        if (!isset($requestorContext[self::CONTEXT_KEY_IDENTIFIERS])) {
            return $requestorContext;
        }

        $flattened = [];

        foreach ($requestorContext[self::CONTEXT_KEY_IDENTIFIERS] as $idName => $identifiers) {
            $flattened[$idName] = $identifiers;
        }

        $requestorContext[self::CONTEXT_KEY_IDENTIFIERS] = json_encode($flattened);

        return $requestorContext;
    }
}
