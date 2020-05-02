<?php

declare(strict_types=1);

namespace RemotelyLiving\Doorkeeper;

use Psr\Log as PSRLog;
use RemotelyLiving\Doorkeeper\Features;
use RemotelyLiving\Doorkeeper\Logger;
use RemotelyLiving\Doorkeeper\Utilities;

final class Doorkeeper
{
    private Features\Set $featureSet;

    private Utilities\RuntimeCache $runtimeCache;

    private PSRLog\LoggerInterface $auditLog;

    private ?Requestor $requestor = null;

    public function __construct(
        Features\Set $featureSet,
        Utilities\RuntimeCache $cache = null,
        PSRLog\LoggerInterface $auditLog = null
    ) {
        $this->featureSet = $featureSet;
        $this->auditLog = $auditLog ?? new PSRLog\NullLogger();
        $this->runtimeCache = $cache ?? new Utilities\RuntimeCache();
    }

    /**
     * @throws \DomainException
     */
    public function setRequestor(Requestor $requestor): void
    {
        if ($this->requestor) {
            throw new \DomainException('Requestor already set');
        }

        $this->requestor = $requestor;
    }

    public function getRequestor(): ?Requestor
    {
        return $this->requestor;
    }

    public function grantsAccessTo(string $featureName): bool
    {
        return $this->grantsAccessToRequestor($featureName, $this->requestor);
    }

    public function grantsAccessToRequestor(string $featureName, Requestor $requestor = null): bool
    {
        $logContext = [
            Logger\Processor::CONTEXT_KEY_REQUESTOR => $requestor,
            Logger\Processor::FEATURE_ID => $featureName,
        ];

        if (!$this->featureSet->offsetExists($featureName)) {
            $this->logAttempt('Access denied because feature does not exist.', $logContext);
            return false;
        }

        $cache_key = md5(sprintf('%s:%s', $featureName, ($requestor) ? $requestor->getIdentityHash() : ''));
        $fallback = function () use ($featureName, $requestor, $logContext): bool {
            $feature = $this->featureSet->getFeatureByName($featureName);

            if (!$feature->isEnabled()) {
                $this->logAttempt('Access denied because feature is disabled.', $logContext);
                return false;
            }

            if ($feature->isEnabled() && !$feature->getRules()) {
                return true;
            }

            foreach ($feature->getRules() as $rule) {
                if ($rule->canBeSatisfied($requestor)) {
                    $this->logAttempt('Access granted to feature', $logContext);
                    return true;
                }
            }

            $this->logAttempt('Access denied to feature', $logContext);
            return false;
        };

        return (bool) $this->runtimeCache->get($cache_key, $fallback);
    }

    public function flushRuntimeCache(): void
    {
        $this->runtimeCache->flush();
    }

    private function logAttempt(string $message, array $context): void
    {
        $this->auditLog->info($message, $context);
    }
}
