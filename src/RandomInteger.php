<?php

declare(strict_types=1);

namespace SecretSanta;


use SecretSanta\Contracts\Random;
use SecretSanta\Exception\InvalidMinimumValueException;

final class RandomInteger implements Random
{

    public function generate(int $min, int $max): int
    {
        $this->checkGivenMinIsLesserThanMax($min, $max);

        return rand($min, $max);
    }

    private function checkGivenMinIsLesserThanMax(int $min, int $max): void
    {
        if ($min > $max) {
            throw new InvalidMinimumValueException(
                sprintf("Given min value must be lesser than given max value %s", $max)
            );
        }
    }
}