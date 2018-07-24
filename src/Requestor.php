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

    public function __construct(array $identifications = [])
    {
        foreach ($identifications as $identification) {
            $this->registerIdentification($identification);
        }
    }

    public function getIdentityHash(): string
    {
        return md5(serialize($this->id_collections));
    }

    public function registerIdentification(Identification\IdentificationAbstract $identification)
    {
        $type = $identification->getType();

        if (!isset($this->id_collections[$type])) {
            $this->id_collections[$type] = new Identification\Collection($type, [$identification]);
            return;
        }

        $this->id_collections[$type]->add($identification);
    }

    public function getIdentificationCollections(): array
    {
        return $this->id_collections;
    }

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

    public function withIpAddress(string $ip_address): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\IpAddress($ip_address));

        return $mutee;
    }

    public function withStringHash(string $hash): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\StringHash($hash));

        return $mutee;
    }

    public function withRequest(RequestInterface $request): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(Identification\HttpHeader::createFromRequest($request));

        return $mutee;
    }

    public function withEnvironment(string $environment): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\Environment($environment));

        return $mutee;
    }

    public function withPipedComposite(string $piped_composite): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\PipedComposite($piped_composite));

        return $mutee;
    }

    public function withIntegerId(int $integer_id): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\IntegerId($integer_id));

        return $mutee;
    }
}
