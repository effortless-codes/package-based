<?php

namespace Winata\PackageBased\Concerns\Resolver;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use ReflectionClass;
use ReflectionException;

/**
 * Trait ModelResolver
 *
 * Provides logic to resolve Eloquent models either by ID or a hashed key,
 * and determine whether the input is already a valid model instance.
 *
 * @package Winata\PackageBased\Concerns\Resolver
 */
trait ModelResolver
{
    /**
     * Resolve a model instance based on key (ID or hash) or object.
     *
     * If the provided `$key` is already an instance of the model, it returns it.
     * If the model uses `HashableId`, it attempts to resolve it by the hash.
     * Otherwise, it falls back to `Model::find()`.
     *
     * @param class-string<Model> $model  The model class name to resolve.
     * @param Model|string|int $key       The key to resolve the model by (ID, hash, or model instance).
     * @param bool $strict                Whether to throw an exception if the model is not found.
     *
     * @return Model|null                 The resolved model instance or null if not found (when not strict).
     *
     * @throws ReflectionException        If model class does not exist or reflection fails.
     * @throws ModelNotFoundException     If the model is not found and strict mode is enabled.
     */
    public function resolveModel(string $model, mixed $key, bool $strict = true): ?Model
    {
        if (is_object($key) && is_a($key, $model)) {
            return $key;
        }

        $reflection = new ReflectionClass($model);

        $found = null;

        if (in_array(HashableId::class, $reflection->getTraitNames()) && !is_null($key)) {
            $found = $model::byHash($key);
        }

        if (is_null($found)) {
            $found = $model::find($key);
        }

        if ($strict && !($found instanceof Model)) {
            throw (new ModelNotFoundException())->setModel($model);
        }

        return $found;
    }

    /**
     * Resolve the actual model key (ID) from a hash string, if applicable.
     *
     * Useful when dealing with URLs or APIs that pass hash-encoded identifiers.
     *
     * @param class-string<Model> $model  The model class name.
     * @param string|int $key             The hash or numeric ID.
     *
     * @return int|string|null            The resolved model ID or the raw key if no transformation is needed.
     *
     * @throws ReflectionException        If model class does not exist or reflection fails.
     */
    public function resolveModelKey(string $model, string|int $key): int|string|null
    {
        $reflection = new ReflectionClass($model);

        $found = null;

        if (
            in_array(HashableId::class, $reflection->getTraitNames()) &&
            is_string($key) &&
            !is_null($key)
        ) {
            $found = $model::hashToId($key);
        }

        return $found ?? $key;
    }
}
