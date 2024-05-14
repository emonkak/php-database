<?php

namespace Emonkak\Database\Tests;

class Entity
{
    private mixed $foo;

    private mixed $bar;

    public static function fromArray(array $props): self
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
