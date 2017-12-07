<?php

declare(strict_types=1);

namespace Tests;


use PHPUnit\Framework\TestCase;
use SecretSanta\Contracts\Output;
use SecretSanta\Contracts\Random;
use SecretSanta\SecretSantaGame;

class SecretSantaGameShould extends TestCase
{
    /**
     * @test
	 * @expectedException SecretSanta\Exception\RepeatedNamesException
	 */
    public function throw_an_exception_after_passing_repeated_names(): void
    {
    	$repeatedNames = PlayerInputFromFileReaderStub::withRepeatedPlayerNames();
		$output = $this->createMock(Output::class);
		$random = $this->createMock(Random::class);

		new SecretSantaGame($repeatedNames, $output, $random);
    }
}