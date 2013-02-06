<?php namespace Flaviozantut\Todo\Facades;

class Todo extends \Illuminate\Support\Facades\Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'todo'; }

}