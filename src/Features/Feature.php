<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Features;

use RemotelyLiving\Doorkeeper\Rules;

final class Feature implements \JsonSerializable
{
    private string $name;

    private bool $enabled;

    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\RuleInterface[]
     */
    private $ruleSet = [];

    /**
     * @param string                                           $name
     * @param bool                                             $enabled
     * @param \RemotelyLiving\Doorkeeper\Rules\RuleInterface[] $rules
     */
    public function __construct(string $name, bool $enabled, array $rules = [])
    {
        $this->name      = $name;
        $this->enabled = $enabled;

        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @throws \DomainException
     */
    public function addRule(Rules\RuleInterface $rule): void
    {
        $this->ruleSet[] = $rule;
    }

    /**
     * @return \RemotelyLiving\Doorkeeper\Rules\RuleInterface[]
     */
    public function getRules(): array
    {
        return $this->ruleSet;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'enabled' => $this->enabled,
            'rules' => $this->ruleSet,
        ];
    }
}
