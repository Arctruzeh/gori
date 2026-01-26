<?php

namespace App;

/**
 * Abstract Base Class
 * Defines the blueprint for all creatures.
 */
abstract class Creature
{
    protected $legs;
    protected $arms;
    protected $eyes;
    protected $wings;
    protected $tails;

    public function __construct($legs, $arms, $eyes, $wings, $tails)
    {
        $this->legs = $legs;
        $this->arms = $arms;
        $this->eyes = $eyes;
        $this->wings = $wings;
        $this->tails = $tails;
    }

    // Abstract method forces children to define their own name
    abstract public function getName();

    /**
     * Polymorphic method to get part count.
     */
    public function getPartCount($part)
    {
        if (property_exists($this, $part)) {
            return $this->$part;
        }
        return 0;
    }
}
