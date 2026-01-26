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
    protected $stomachs;
    protected $antennae;
    protected $chromosomes;
    protected $teeth;
    protected $toes;
    protected $neck_vertebrae;

    public function __construct($legs, $arms, $eyes, $wings, $tails, $stomachs, $antennae, $chromosomes, $teeth, $toes, $neck_vertebrae)
    {
        $this->legs = $legs;
        $this->arms = $arms;
        $this->eyes = $eyes;
        $this->wings = $wings;
        $this->tails = $tails;
        $this->stomachs = $stomachs;
        $this->antennae = $antennae;
        $this->chromosomes = $chromosomes;
        $this->teeth = $teeth;
        $this->toes = $toes;
        $this->neck_vertebrae = $neck_vertebrae;
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
