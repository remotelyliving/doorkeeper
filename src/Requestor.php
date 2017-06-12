<?php
namespace RemotelyLiving\Doorkeeper;

use Psr\Http\Message\RequestInterface;
use RemotelyLiving\Doorkeeper\Identification;

class Requestor
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract[]
     */
    private $identities = [];

    /**
     * @param \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract[] $identities
     */
    public function __construct(array $identities = [])
    {
        foreach ($identities as $identity) {
            $this->registerIdentification($identity);
        }
    }

    /**
     * @return string
     */
    public function getIdentityHash(): string
    {
        return md5(serialize($this->identities));
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract $identification
     */
    public function registerIdentification(Identification\IdentificationAbstract $identification): void
    {
        $this->identities[get_class($identification)] = $identification;
    }

    /**
     * @return \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract[]
     */
    public function getIdentifications(): array
    {
        return $this->identities;
    }

    /**
     * @param string $class_name
     *
     * @return \RemotelyLiving\Doorkeeper\Identification\IdentificationAbstract|null
     */
    public function getIdentifiationByClassName(string $class_name): ?Identification\IdentificationAbstract
    {
        return $this->identities[$class_name] ?? null;
    }

    /**
     * @param int $id
     *
     * @return \RemotelyLiving\Doorkeeper\Requestor
     */
    public function withUserId(int $id): self
    {
        $mutee = new static($this->getIdentifications());
        $mutee->registerIdentification(new Identification\UserId($id));

        return $mutee;
    }

    /**
     * @param string $ip_address
     *
     * @return \RemotelyLiving\Doorkeeper\Requestor
     */
    public function withIpAddress(string $ip_address): self
    {
        $mutee = new static($this->getIdentifications());
        $mutee->registerIdentification(new Identification\IpAddress($ip_address));

        return $mutee;
    }

    /**
     * @param string $hash
     *
     * @return \RemotelyLiving\Doorkeeper\Requestor
     */
    public function withStringHash(string $hash): self
    {
        $mutee = new static($this->getIdentifications());
        $mutee->registerIdentification(new Identification\StringHash($hash));

        return $mutee;
    }

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     *
     * @return \RemotelyLiving\Doorkeeper\Requestor
     */
    public function withRequest(RequestInterface $request): self
    {
        $mutee = new static($this->getIdentifications());
        $mutee->registerIdentification(Identification\HttpHeader::createFromRequest($request));

        return $mutee;
    }

    /**
     * @param string $environment
     *
     * @return \RemotelyLiving\Doorkeeper\Requestor
     */
    public function withEnvironment(string $environment): self
    {
        $mutee = new static($this->getIdentifications());
        $mutee->registerIdentification(new Identification\Environment($environment));

        return $mutee;
    }
}
