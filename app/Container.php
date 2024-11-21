<?php

namespace App;

use App\Exceptions\ContainerException;
use App\Exceptions\NotFoundException;
use http\Exception\BadConversionException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];
            return $entry($this);
        }
        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable $concrete): void
    {
        $this->entries[$id] = $concrete;
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     * @throws ContainerExceptionInterface
     * @throws ContainerException
     */
    public function resolve(string $id)
    {
        //1
        $reflectionClass = new \ReflectionClass($id);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException('Class "' . $id . '" is not instantiable');
        }
        //2
        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $id;
        }
        //3
        $parameters = $constructor->getParameters();
        if(! $parameters) {
            return new $id;
        }

        //4

        $dependencies = array_map(function (\ReflectionParameter $parameter) use ($id) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if(!$type) {
                throw new ContainerException('Failed to resolve class "' . $id . '" because param "' . $name . '" is missing a type hint');
            }

            if ($type instanceof \ReflectionNamedType) {
                if ($type->isBuiltin()) {
                    throw new ContainerException('Failed to resolve class "' . $id . '" because of union type of param "' . $name . '"');
                }
                return $this->get($type->getName());
            }

            if ($type instanceof \ReflectionUnionType) {
                foreach ($type->getTypes() as $type) {
                    if ($type->isBuiltin()) {
                        return $this->get($type->getName());
                    }

                }
                throw new ContainerException("Failed to resolve class");
            }
            throw new BadConversionException('Union type of param "' . $name . '" is not supported');
        },
            $parameters
        );
        return $reflectionClass->newInstanceArgs($dependencies);
    }
}