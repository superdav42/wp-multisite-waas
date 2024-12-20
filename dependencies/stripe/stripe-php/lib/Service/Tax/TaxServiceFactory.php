<?php

// File generated from our OpenAPI spec
namespace WP_Ultimo\Dependencies\Stripe\Service\Tax;

/**
 * Service factory class for API resources in the Tax namespace.
 *
 * @property CalculationService $calculations
 * @property SettingsService $settings
 * @property TransactionService $transactions
 */
class TaxServiceFactory extends \WP_Ultimo\Dependencies\Stripe\Service\AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = ['calculations' => CalculationService::class, 'settings' => SettingsService::class, 'transactions' => TransactionService::class];
    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
