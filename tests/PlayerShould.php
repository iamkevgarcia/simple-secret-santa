<?php

declare(strict_types=1);

namespace Tests;


use PHPUnit\Framework\TestCase;
use SecretSanta\Player;

class PlayerShould extends TestCase
{
    /**
     * @test
     * @expectedException SecretSanta\Exception\EmptyPlayerNameException
     */
    public function throw_an_exception_after_passing_empty_name(): void
    {
        new Player('');
    }

    public function have_passed_name(): void
    {
        $bestKPOPSingers = 'BTS';
        $player = new Player($bestKPOPSingers);

        $this->assertEquals($bestKPOPSingers, $player->name());
    }
}
