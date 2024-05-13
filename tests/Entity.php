<?php

namespace Emonkak\Database\Tests;

class Entity
{
    private $foo;

    private $bar;

    public static function fromArray(array $props)
    {
        $self = new Entity();
        foreach ($props as $key => $prop) {
            $self->$key = $prop;
        }
        return $self;
    }

    public function __construct()
    {
    }
}
