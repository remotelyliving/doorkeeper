<?php
namespace RemotelyLiving\Doorkeeper\Features;

use RemotelyLiving\Doorkeeper\Rules;

class Feature
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\RuleInterface[]
     */
    private $rule_set = [];

    /**
     * @param string                                           $name
     * @param bool                                             $enabled
     * @param \RemotelyLiving\Doorkeeper\Rules\RuleInterface[] $rules
     */
    public function __construct(string $name, bool $enabled, array $rules = [])
    {
        $this->name       = $name;
        $this->enabled  = $enabled;

        foreach ($rules as $rule) {
            $this->addRule($rule);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Rules\RuleInterface $rule
     *
     * @throws \DomainException
     */
    public function addRule(Rules\RuleInterface $rule): void
    {
        if ($rule->getFeatureId() !== $this->name) {
            throw new \DomainException(sprintf(
                'Rules for %s may not be added to rules for feature %s',
                $rule->getFeatureId(),
                $this->name
            ));
        }

        $this->rule_set[] = $rule;
    }

    /**
     * @return \RemotelyLiving\Doorkeeper\Rules\RuleInterface[]
     */
    public function getRules(): array
    {
        return $this->rule_set;
    }
}
