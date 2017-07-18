<?php

namespace RemotelyLiving\Doorkeeper\Rules;

class Factory
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\TypeMapper
     */
    private $rule_type_mapper;

    /**
     * Factory constructor.
     *
     * @param TypeMapper|null $type_mapper
     */
    public function __construct(TypeMapper $type_mapper = null)
    {
        $this->rule_type_mapper = $type_mapper ?? new TypeMapper();
    }

    /**
     * @param array $fields
     *
     * @return \RemotelyLiving\Doorkeeper\Rules\RuleAbstract
     */
    public function createFromArray(array $fields): RuleAbstract
    {
        $rule_type = $this->normalizeRuleType($fields['type']);

        /** @var \RemotelyLiving\Doorkeeper\Rules\RuleAbstract $rule */
        $rule = isset($fields['value']) ? new $rule_type($fields['value']) : new $rule_type;

        if (isset($fields['prerequisite'])) {
            $pre_req_type = $this->normalizeRuleType($fields['prerequisite']['type']);
            $pre_req = isset($fields['prerequisite']['value'])
                       ? new $pre_req_type($fields['prerequisite']['value'])
                       : new $pre_req_type;

            $rule->setPrerequisite($pre_req);
        }
        
        return $rule;
    }

    /**
     * @param string|int $type
     *
     * @return string
     */
    private function normalizeRuleType($type): string
    {
        return (is_numeric($type))
               ? $this->rule_type_mapper->getClassNameById($type)
               : $type;
    }
}
