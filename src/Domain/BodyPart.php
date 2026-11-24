<?php

declare(strict_types=1);

namespace App\Domain;

enum BodyPart: string
{
    case Legs = 'legs';
    case Arms = 'arms';
    case Eyes = 'eyes';
    case Wings = 'wings';
    case Tails = 'tails';
    case Stomachs = 'stomachs';
    case Antennae = 'antennae';
    case Chromosomes = 'chromosomes';
    case Teeth = 'teeth';
    case Toes = 'toes';
    case NeckVertebrae = 'neck_vertebrae';

    public function label(): string
    {
        return \str_replace('_', ' ', $this->value);
    }
}
