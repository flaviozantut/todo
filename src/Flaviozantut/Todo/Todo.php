<?php namespace Flaviozantut\Todo;

class Todo {

    public function __construct($provider, array $providerConstruct)
    {
        $provider = "Flaviozantut\Todo\Providers\\".$provider;
        $refMethod = new \ReflectionMethod($provider,  '__construct');
        $params = $refMethod->getParameters();
        $reArgs = array();
        foreach ($providerConstruct as $key => $param) {
            $reArgs[$key] = $providerConstruct[$key];
        }
        $refClass = new \ReflectionClass($provider);
        if (!$refClass->implementsInterface('Flaviozantut\Todo\Providers\ProvidersInterface')) {
            throw new Exception($provider . " not Implements: Flaviozantut\Todo\Providers\ProvidersInterface");
        }
        $this->object  = $refClass->newInstanceArgs((array) $reArgs);

    }

    function __call($method, $args) {
        return call_user_func_array(array($this->object, $method), $args);
    }
}