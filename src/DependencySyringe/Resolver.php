<?php

namespace fernandoSa\DependencySyringe;

class Resolver
{

    private $dependencyValues = [];

    public function resolveMethod(callable $method, array $dependencyValues = [])
    {
        $this->dependencyValues = $dependencyValues;

        $function = new \ReflectionFunction($method);
        $functionParameters = $function->getParameters();
        $functionParameters = $this->resolveParameters($functionParameters);

        return call_user_func($function->getClosure(), $functionParameters);
    }

    public function resolveClass(object $class, array $dependencyValues = []) : object
    {
        $this->dependencyValues = $dependencyValues;

        $classReflection = new \ReflectionClass($class);

        if (! $classReflection->isInstantiable()) {
            throw new \Exception("{$classReflection->getName()} is not instantiable");
        }

        $constructor = $classReflection->getConstructor();
        $constructorParameters = $constructor->getParameters();

        if (! $constructor && empty($constructorParameters)) {
            return $classReflection->newInstance();
        }

        $parameters = $this->resolveParameters($constructorParameters);

        return $classReflection->newInstanceArgs($parameters);
    }

    private function resolveParameters($parameters) : array
    {
        $dependecies = [];


        foreach ($parameters as $parameter) {
            // If this isn't a class it is a funcion or a variable
            if ($this->isClass($parameter)) {
                $dependecies[] = $this->resolveClass($parameter);
            } else {
                $dependecies[] = $this->resolveDependencies($parameter);
            }
        }

        return $dependecies;
    }

    private function isClass($subject) : bool
    {
        return $subject->getClass() === true;
    }

    private function resolveDependencies(\ReflectionParameter $dependency)
    {
        // If dependency value was passed with call, return it
        if (isset($this->dependencyValues[$dependency->name])) {
            return $this->dependencyValues[$dependency->name];
        }

        if ($dependency->isDefaultValueAvailable()) {
            return $dependency->getDefaultValue();
        }

        throw new \Exception("{$dependency->getName()} needs a value and it was'n provided");
    }
}
