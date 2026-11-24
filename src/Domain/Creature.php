<?php

declare(strict_types=1);

namespace App\Domain;

abstract class Creature
{
    public function __construct(
        protected readonly int $legs,
        protected readonly int $arms,
        protected readonly int $eyes,
        protected readonly int $wings,
        protected readonly int $tails,
        protected readonly int $stomachs,
        protected readonly int $antennae,
        protected readonly int $chromosomes,
        protected readonly int $teeth,
        protected readonly int $toes,
        protected readonly int $neckVertebrae,
    ) {
    }

    abstract public function getName(): string;

    public function getPartCount(BodyPart $part): int
    {
        return match ($part) {
            BodyPart::Legs => $this->legs,
            BodyPart::Arms => $this->arms,
            BodyPart::Eyes => $this->eyes,
            BodyPart::Wings => $this->wings,
            BodyPart::Tails => $this->tails,
            BodyPart::Stomachs => $this->stomachs,
            BodyPart::Antennae => $this->antennae,
            BodyPart::Chromosomes => $this->chromosomes,
            BodyPart::Teeth => $this->teeth,
            BodyPart::Toes => $this->toes,
            BodyPart::NeckVertebrae => $this->neckVertebrae,
        };
    }
}
