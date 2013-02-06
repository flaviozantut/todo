<?php namespace Flaviozantut\Todo\Providers;

interface ProvidersInterface {


    public function add($todo);

    public function ArrayOfAll();

    public function last();

    public function get($todo);

    public function complete($todo);

    //public function delete($todo);


}