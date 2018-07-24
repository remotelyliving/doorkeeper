<?php

namespace RemotelyLiving\Doorkeeper\Identification;

class Collection implements \Countable, \IteratorAggregate
{
    /**
     * @var string
     */
    private $class_type;

    /**
     * @var array
     */
    private $identifications = [];

    public function __construct(string $class_type, array $identifications = [])
    {
        $this->class_type = $class_type;

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

    public function add(IdentificationAbstract $identification)
    {
        if (get_class($identification) !== $this->class_type) {
            throw new \InvalidArgumentException("Identification must be a {$this->class_type}");
        }

        $this->identifications[$identification->getIdentifier()] = $identification;
    }

    public function remove(IdentificationAbstract $identification)
    {
        if ($this->has($identification)) {
            unset($this->identifications[$identification->getIdentifier()]);
        }
    }

    /**
     * @return null|IdentificationAbstract
     */
    public function get(string $id)
    {
        return $this->identifications[$id] ?? null;
    }

    public function has(IdentificationAbstract $identification): bool
    {
        return isset($this->identifications[$identification->getIdentifier()]);
    }
}
