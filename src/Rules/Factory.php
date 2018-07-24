<?php

namespace RemotelyLiving\Doorkeeper\Rules;

class Factory
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\TypeMapper
     */
    private $rule_type_mapper;

    public function __construct(TypeMapper $type_mapper = null)
    {
        $this->rule_type_mapper = $type_mapper ?? new TypeMapper();
    }

    public function createFromArray(array $fields): RuleAbstract
    {
        $rule_type = $this->normalizeRuleType($fields['type']);

        /** @var \RemotelyLiving\Doorkeeper\Rules\RuleAbstract $rule */
        $rule = isset($fields['value']) ? new $rule_type($fields['value']) : new $rule_type;

        return isset($fields['prerequisites']) ? $this->addPrerequisites($rule, $fields['prerequisites']) : $rule;
    }

    private function addPrerequisites(RuleAbstract $rule, array $prequisites): RuleAbstract
    {
        foreach ($prequisites as $prequisite) {
            $pre_req_type = $this->normalizeRuleType($prequisite['type']);
            $pre_req = isset($prequisite['value']) ? new $pre_req_type($prequisite['value']) : new $pre_req_type;

            $rule->addPrerequisite($pre_req);
        }

        return $rule;
    }

    private function normalizeRuleType($type): string
    {
        if (is_numeric($type)) {
            return $this->rule_type_mapper->getClassNameById((int) $type);
        }

        $fqcnSegments = explode('\\', $type);
        $className = array_pop($fqcnSegments);

        if (class_exists(__NAMESPACE__ . "\\{$className}")) {
            return __NAMESPACE__ . "\\{$className}";
        }

        throw new \InvalidArgumentException("{$type} is not a valid rule type");
    }
}
