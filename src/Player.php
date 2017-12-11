<?php

declare(strict_types=1);

namespace SecretSanta;


use SecretSanta\Exception\EmptyPlayerNameException;

final class Player
{
    private $name;

    public function __construct(string $name)
    {
        $this->setName(trim($name));
    }

    private function setName($name): void
    {
        if (empty($name)) {
            throw new EmptyPlayerNameException("Player name can not be empty");
        }

        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name();
    }
}
