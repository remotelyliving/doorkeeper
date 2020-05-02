<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper\Rules;

final class Factory
{
    private TypeMapper $ruleTypeMapper;

    public function __construct(TypeMapper $typeMapper = null)
    {
        $this->ruleTypeMapper = $typeMapper ?? new TypeMapper();
    }

    public function createFromArray(array $fields): RuleInterface
    {
        $ruleType = $this->normalizeRuleType($fields['type']);

        /** @var \RemotelyLiving\Doorkeeper\Rules\RuleInterface $rule */
        $rule = isset($fields['value']) ? new $ruleType($fields['value']) : new $ruleType();

        return isset($fields['prerequisites']) ? $this->addPrerequisites($rule, $fields['prerequisites']) : $rule;
    }

    private function addPrerequisites(RuleInterface $rule, array $prequisites): RuleInterface
    {
        foreach ($prequisites as $prequisite) {
            $preReqType = $this->normalizeRuleType($prequisite['type']);
            $preReq = isset($prequisite['value']) ? new $preReqType($prequisite['value']) : new $preReqType();

            $rule->addPrerequisite($preReq);
        }

        return $rule;
    }

    /**
     * @param string|int $type
     */
    private function normalizeRuleType($type): string
    {
        if (is_numeric($type)) {
            return $this->ruleTypeMapper->getClassNameById((int) $type);
        }

        $fqcnSegments = explode('\\', $type);
        $className = array_pop($fqcnSegments);

        if (class_exists(__NAMESPACE__ . "\\{$className}")) {
            return __NAMESPACE__ . "\\{$className}";
        }

        throw new \InvalidArgumentException("{$type} is not a valid rule type");
    }
}
