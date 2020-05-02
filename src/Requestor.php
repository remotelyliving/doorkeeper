<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper;

use Psr\Http;
use RemotelyLiving\Doorkeeper\Identification;

final class Requestor implements RequestorInterface
{
    /**
     * @var Identification\Collection[]
     */
    private $idCollections = [];

    public function __construct(array $identifications = [])
    {
        foreach ($identifications as $identification) {
            $this->registerIdentification($identification);
        }
    }

    public function getIdentityHash(): string
    {
        return md5(serialize($this->idCollections));
    }

    public function registerIdentification(Identification\IdentificationInterface $identification): void
    {
        $type = $identification->getType();

        if (!isset($this->idCollections[$type])) {
            $this->idCollections[$type] = new Identification\Collection($type, [$identification]);
            return;
        }

        $this->idCollections[$type]->add($identification);
    }

    public function getIdentificationCollections(): array
    {
        return $this->idCollections;
    }

    public function hasIdentification(Identification\IdentificationInterface $identification): bool
    {
        if (!isset($this->idCollections[$identification->getType()])) {
            return false;
        }

        return $this->idCollections[$identification->getType()]->has($identification);
    }

    /**
     * @param string|int $id
     */
    public function withUserId($id): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\UserId($id));

        return $mutee;
    }

    public function withIpAddress(string $ipAddress): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\IpAddress($ipAddress));

        return $mutee;
    }

    public function withStringHash(string $hash): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\StringHash($hash));

        return $mutee;
    }

    public function withRequest(Http\Message\RequestInterface $request): self
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

    public function withPipedComposite(string $pipedComposite): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\PipedComposite($pipedComposite));

        return $mutee;
    }

    public function withIntegerId(int $integerId): self
    {
        $mutee = clone $this;
        $mutee->registerIdentification(new Identification\IntegerId($integerId));

        return $mutee;
    }
}
