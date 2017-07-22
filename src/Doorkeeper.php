<?php
namespace RemotelyLiving\Doorkeeper;

use Psr\Log\LoggerInterface;
use RemotelyLiving\Doorkeeper\Logger\Processor;
use RemotelyLiving\Doorkeeper\Utilities\RuntimeCache;

class Doorkeeper
{
    /**
     * @var \RemotelyLiving\Doorkeeper\Features\Set
     */
    private $feature_set;

    /**
     * @var null|\RemotelyLiving\Doorkeeper\Utilities\RuntimeCache
     */
    private $runtime_cache;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    private $audit_log;

    /**
     * @var \RemotelyLiving\Doorkeeper\Requestor|null
     */
    private $requestor = null;

    /**
     * @param Features\Set $feature_set
     * @param RuntimeCache|null $cache
     * @param LoggerInterface|null $audit_log
     */
    public function __construct(
        Features\Set $feature_set,
        RuntimeCache $cache = null,
        LoggerInterface $audit_log = null
    ) {
        $this->feature_set   = $feature_set;
        $this->audit_log     = $audit_log;
        $this->runtime_cache = $cache ?? new RuntimeCache();
    }

    /**
     * @param \RemotelyLiving\Doorkeeper\Requestor $requestor
     *
     * @throws \DomainException
     */
    public function setRequestor(Requestor $requestor)
    {
        if ($this->requestor) {
            throw new \DomainException('Requestor already set');
        }

        $this->requestor = $requestor;
    }

    /**
     * @return \RemotelyLiving\Doorkeeper\Requestor|null
     */
    public function getRequestor()
    {
        return $this->requestor;
    }

    /**
     * @param string $feature_name
     *
     * @return bool
     */
    public function grantsAccessTo(string $feature_name): bool
    {
        return $this->grantsAccessToRequestor($feature_name, $this->requestor);
    }

    /**
     * @param string                                    $feature_name
     * @param \RemotelyLiving\Doorkeeper\Requestor|null $requestor
     *
     * @return bool
     */
    public function grantsAccessToRequestor(string $feature_name, Requestor $requestor = null): bool
    {
        $log_context = [
            Processor::CONTEXT_KEY_REQUESTOR => $requestor,
            Processor::FEATURE_ID            => $feature_name,
        ];

        if (!$this->feature_set->offsetExists($feature_name)) {
            $this->logAttempt('Access denied because feature does not exist.', $log_context);
            return false;
        }

        $cache_key = md5(sprintf('%s:%s', $feature_name, ($requestor) ? $requestor->getIdentityHash() : ''));
        $fallback = function () use ($feature_name, $requestor, $log_context): bool {
            $feature = $this->feature_set->getFeatureByName($feature_name);

            if (!$feature->isEnabled()) {
                $this->logAttempt('Access denied because feature is disabled.', $log_context);
                return false;
            }

            if ($feature->isEnabled() && !$feature->getRules()) {
                return true;
            }

            foreach ($feature->getRules() as $rule) {
                if ($rule->canBeSatisfied($requestor)) {
                    $this->logAttempt('Access granted to feature', $log_context);
                    return true;
                }
            }

            $this->logAttempt('Access denied to feature', $log_context);
            return false;
        };

        return $this->runtime_cache->get($cache_key, $fallback);
    }

    public function flushRuntimeCache()
    {
        $this->runtime_cache->flush();
    }

    /**
     * @param string $message
     * @param array  $context
     */
    private function logAttempt(string $message, array $context)
    {
        if (!$this->audit_log) {
            return;
        }

        $this->audit_log->info($message, $context);
    }
}
