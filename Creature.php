<?php

class Creature {
    public $legs;
    public $arms;
    public $eyes;
}

$dog = new Creature();
$dog->legs = 4;
$dog->arms = 0;
$dog->eyes = 2;

$human = new Creature();
$human->legs = 2;
$human->arms = 2;
$human->eyes= 2;

$spider = new Creature();
$spider->legs = 8;
$spider->arms = 0;
$spider->eyes = 8;

/**
 * random question generator
 */

$creatures = array('dog', 'human', 'spider');
$parts = array('legs', 'arms', 'eyes');

$rncreatures = array_rand($creatures);
$rnparts = array_rand($parts);

$rndcreature = $creatures[$rncreatures];
$rndpart = $parts[$rnparts];

if ($rndcreature == 'dog') {
    switch ($rndpart) {
        case 'legs':
            $result = $dog->legs;
            break;
        case 'arms':
            $result = $dog->arms;
            break;
        case 'eyes':
            $result = $dog->eyes;
            break;

        default:
            $result = "answer error";
            break;
    }
}
elseif ($rndcreature == 'human') {
    switch ($rndpart) {
        case 'legs':
            $result = $human->legs;
            break;
        case 'arms':
            $result = $human->arms;
            break;
        case 'eyes':
            $result = $human->eyes;
            break;

        default:
            $result = "answer error 2";
    }
}
elseif ($rndcreature == 'spider') {
    switch ($rndpart) {
        case 'legs':
            $result = $spider->legs;
            break;
        case 'arms':
            $result = $spider->arms;
            break;
        case 'eyes':
            $result = $spider->eyes;
            break;

        default:
            $result = "answer error 3";
    }
}

$q1 = 'How many ' . $rndpart . ' does a ' . $rndcreature . ' have? ';
$a1 = $result;

echo $q1 . '<br>';