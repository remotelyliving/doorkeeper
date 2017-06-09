<?php

namespace RemotelyLiving\Doorkeeper\Rules;

class Factory
{
    /**
     * Extend and append to this array
     *
     * @var callable[]
     */
    protected $create_map = [];

    /**
     * @var \RemotelyLiving\Doorkeeper\Rules\TypeMapper
     */
    private $rule_type_mapper;

    public function __construct()
    {
        $this->initCreateMap();
        $this->rule_type_mapper = new TypeMapper();
    }

    /**
     * @param array $fields
     *
     * @return \RemotelyLiving\Doorkeeper\Rules\RuleAbstract
     */
    public function createFromArray(array $fields): RuleAbstract
    {
        $rule_type = $this->normalizeRuleType($fields['type']);

        if (!isset($this->create_map[$rule_type])) {
            throw new \InvalidArgumentException("{$rule_type} has no create method for this factory");
        }

        /** @var \RemotelyLiving\Doorkeeper\Rules\RuleAbstract $rule */
        $rule = $this->create_map[$rule_type]($fields);

        if (isset($fields['prerequisite'])) {
            $pre_req_type = $this->normalizeRuleType($fields['prerequisite']['type']);
            $rule->setPrerequisite($this->create_map[$pre_req_type]($fields['prerequisite']));
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

    private function initCreateMap(): void
    {
        $this->create_map = [
          HttpHeader::class => function (array $fields) {
            return new HttpHeader($fields['value']);
          },
          IpAddress::class  => function (array $fields) {
            return new IpAddress($fields['value']);
          },
          Percentage::class => function (array $fields) {
            return new Percentage($fields['value']);
          },
          Random::class => function () {
            return new Random();
          },
          StringHash::class => function (array $fields) {
            return new StringHash($fields['value']);
          },
          UserId::class => function (array $fields) {
            return new UserId($fields['value']);
          },
          Environment::class => function (array $fields) {
              return new Environment($fields['value']);
          },
          TimeBefore::class => function (array $fields) {
              return new TimeBefore($fields['value']);
          },
          TimeAfter::class => function (array $fields) {
              return new TimeAfter($fields['value']);
          },
        ];
    }
}
