<?php

class Person
{
    public $name;
    public $age;
    public function __construct($name, $age)
    {
        $this->name = $name;
        $this->age = $age;
    }
}

$p = new Person("David", 42);

print_r($p);
