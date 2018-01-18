<?php
namespace RemotelyLiving\Doorkeeper;

use Psr\Http\Message\RequestInterface;
use RemotelyLiving\Doorkeeper\Identification;

class Requestor
{
    /**
     * @var Identification\Collection[]
     */
    private $id_collections = [];

    /**
     * @param \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract[] $identifications
     */
    public function __construct(array $identifications = [])
    {
        foreach ($identifications as $identification) {
            $this->registerIdentification($identification);
        }
    }

    /**
     * @return string
     */
    public function getIdentityHash(): string
    {
        return md5(serialize($this->id_collections));
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract $identification
     */
    public function registerIdentification(Identification\IdentificationAbstract $identification)
    {
        $type = $identification->getType();

        if (!isset($this->id_collections[$type])) {
            $this->id_collections[$type] = new Identification\Collection($type, [$identification]);
            return;
        }

        $this->id_collections[$type]->add($identification);
    }

    /**
     * @return Identification\Collection[]
     */
    public function getIdentificationCollections(): array
    {
        return $this->id_collections;
    }

    /**
     * @param Identification\IdentificationAbstract $identification
     *
     * @return bool
     */
    public function hasIdentification(Identification\IdentificationAbstract $identification): bool
    {
        if (!isset($this->id_collections[$identification->getType()])) {
            return false;
        }

        return $this->id_collections[$identification->getType()]->has($identification);
    }

    /**
     * @param int|string $id
     *
     * @return self
     */
    public function withUserId($id): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\UserId($id));

        return $mutee;
    }

    /**
     * @param string $ip_address
     *
     * @return self
     */
    public function withIpAddress(string $ip_address): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\IpAddress($ip_address));

        return $mutee;
    }

    /**
     * @param string $hash
     *
     * @return self
     */
    public function withStringHash(string $hash): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\StringHash($hash));

        return $mutee;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return self
     */
    public function withRequest(RequestInterface $request): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(Identification\HttpHeader::createFromRequest($request));

        return $mutee;
    }

    /**
     * @param string $environment
     *
     * @return self
     */
    public function withEnvironment(string $environment): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\Environment($environment));

        return $mutee;
    }

    /**
     * @param string $piped_composite
     *
     * @return self
     */
    public function withPipedComposite(string $piped_composite): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\PipedComposite($piped_composite));

        return $mutee;
    }

    /**
     * @param int $integer_id
     *
     * @return self
     */
    public function withIntegerId(int $integer_id): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\IntegerId($integer_id));

        return $mutee;
    }
}
