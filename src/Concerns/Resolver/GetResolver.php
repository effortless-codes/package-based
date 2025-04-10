<?php

namespace Winata\PackageBased\Concerns\Resolver;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use ReflectionException;

/**
 * Trait GetResolver
 *
 * Provides logic to dynamically resolve an Eloquent model instance
 * based on a configured model string in `config/resolver/{configName}.php`.
 *
 * @package Winata\PackageBased\Concerns\Resolver
 */
trait GetResolver
{
    use ModelResolver;

    /**
     * Resolve a model instance by configuration path and identifier.
     *
     * This method reads the configured model class from:
     * `config/resolver/{configName}.{type}.model`,
     * and attempts to resolve it using the identifier (usually ID).
     *
     * @param string     $configName  The configuration file name inside `config/resolver`.
     * @param string     $type        The model group/type key inside the config file.
     * @param int|string $identifier  The primary key or identifier for the model instance.
     *
     * @return Model  The resolved Eloquent model.
     *
     * @throws ReflectionException      If reflection-based instantiation fails.
     * @throws InvalidArgumentException If the resolved class is not a Model instance.
     */
    protected function resolve(string $configName, string $type, int|string $identifier): Model
    {
        $modelString = config("resolver.{$configName}.{$type}.model");

        abort_unless(
            $modelString,
            404,
            "Model for type [{$type}] not found. Did you forget to register it in config/resolver/{$configName}.php?"
        );

        $model = $this->resolveModel($modelString, $identifier);

        if (! $model instanceof Model) {
            throw new InvalidArgumentException("Class [{$modelString}] must be an instance of " . Model::class);
        }

        return $model;
    }
}
