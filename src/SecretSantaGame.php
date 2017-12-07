<?php

declare(strict_types=1);

namespace SecretSanta;


use SecretSanta\Contracts\Game;
use SecretSanta\Contracts\Input;
use SecretSanta\Contracts\Output;
use SecretSanta\Contracts\Random;
use SecretSanta\Exception\RepeatedNamesException;

final class SecretSantaGame implements Game
{
	private $output;
	private $random;

	public function __construct(Input $playersInput, Output $output, Random $random)
	{
		$this->setPlayers($playersInput->read());
		$this->output = $output;
		$this->random = $random;
	}

	public function play(): void
	{

	}

	private function setPlayers(array $playerNames): void
	{
		$players = new PlayerStorage();
		foreach ($playerNames as $playerName) {
			$player = new Player($playerName);
			if ($players->contains($player)) {
				throw new RepeatedNamesException(sprintf("%s already used", $playerName));
			}
			$players->attach($player);
		}
	}
}