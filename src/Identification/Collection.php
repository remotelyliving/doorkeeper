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

    /**
     * @param string $class_type
     * @param array $identifications
     */
    public function __construct(string $class_type, array $identifications = [])
    {
        $this->class_type = $class_type;

        foreach ($identifications as $identification) {
            $this->add($identification);
        }
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->identifications);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->identifications);
    }

    /**
     * @param IdentificationAbstract $identification
     */
    public function add(IdentificationAbstract $identification)
    {
        if (get_class($identification) !== $this->class_type) {
            throw new \InvalidArgumentException("Identification must be a {$this->class_type}");
        }

        $this->identifications[$identification->getIdentifier()] = $identification;
    }

    /**
     * @param IdentificationAbstract $identification
     */
    public function remove(IdentificationAbstract $identification)
    {
        if ($this->has($identification)) {
            unset($this->identifications[$identification->getIdentifier()]);
        }
    }

    /**
     * @param $id
     *
     * @return null|IdentificationAbstract
     */
    public function get($id)
    {
        return $this->identifications[$id] ?? null;
    }

    /**
     * @param IdentificationAbstract $identification
     *
     * @return bool
     */
    public function has(IdentificationAbstract $identification): bool
    {
        return isset($this->identifications[$identification->getIdentifier()]);
    }
}
