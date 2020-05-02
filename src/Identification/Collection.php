<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Identification;

final class Collection implements \Countable, \IteratorAggregate
{
    private string $classType;

    /**
     * @var \RemotelyLiving\Doorkeeper\Identification\IdentificationInterface[]
     */
    private $identifications = [];

    public function __construct(string $classType, array $identifications = [])
    {
        $this->classType = $classType;

        foreach ($identifications as $identification) {
            $this->add($identification);
        }
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->identifications);
    }

    public function count(): int
    {
        return count($this->identifications);
    }

    public function add(IdentificationInterface $identification): void
    {
        if (get_class($identification) !== $this->classType) {
            throw new \InvalidArgumentException("Identification must be a {$this->classType}");
        }

        $this->identifications[$identification->getIdentifier()] = $identification;
    }

    public function remove(IdentificationInterface $identification): void
    {
        if ($this->has($identification)) {
            unset($this->identifications[$identification->getIdentifier()]);
        }
    }

    public function get(string $id): ?IdentificationInterface
    {
        return $this->identifications[$id] ?? null;
    }

    public function has(IdentificationInterface $identification): bool
    {
        return isset($this->identifications[$identification->getIdentifier()]);
    }
}
