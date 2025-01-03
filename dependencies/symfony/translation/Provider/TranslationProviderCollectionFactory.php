<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Symfony\Component\Translation\Provider;

use Symfony\Component\Translation\Exception\UnsupportedSchemeException;
/**
 * @author Mathieu Santostefano <msantostefano@protonmail.com>
 */
class TranslationProviderCollectionFactory
{
    private iterable $factories;
    private array $enabledLocales;
    /**
     * @param iterable<mixed, ProviderFactoryInterface> $factories
     */
    public function __construct(iterable $factories, array $enabledLocales)
    {
        $this->factories = $factories;
        $this->enabledLocales = $enabledLocales;
    }
    public function fromConfig(array $config) : \Symfony\Component\Translation\Provider\TranslationProviderCollection
    {
        $providers = [];
        foreach ($config as $name => $currentConfig) {
            $providers[$name] = $this->fromDsnObject(new \Symfony\Component\Translation\Provider\Dsn($currentConfig['dsn']), !$currentConfig['locales'] ? $this->enabledLocales : $currentConfig['locales'], !$currentConfig['domains'] ? [] : $currentConfig['domains']);
        }
        return new \Symfony\Component\Translation\Provider\TranslationProviderCollection($providers);
    }
    public function fromDsnObject(\Symfony\Component\Translation\Provider\Dsn $dsn, array $locales, array $domains = []) : \Symfony\Component\Translation\Provider\ProviderInterface
    {
        foreach ($this->factories as $factory) {
            if ($factory->supports($dsn)) {
                return new \Symfony\Component\Translation\Provider\FilteringProvider($factory->create($dsn), $locales, $domains);
            }
        }
        throw new UnsupportedSchemeException($dsn);
    }
}
