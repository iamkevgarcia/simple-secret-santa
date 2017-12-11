<?php

declare(strict_types=1);

namespace SecretSanta;


use SecretSanta\Contracts\Game;
use SecretSanta\Contracts\Input;
use SecretSanta\Contracts\Output;
use SecretSanta\Contracts\Random;
use SecretSanta\Exception\NoPlayersFoundException;
use SecretSanta\Exception\OddNumberOfPlayersException;
use SecretSanta\Exception\RepeatedNamesException;

final class SecretSantaGame implements Game
{
	private $output;
	private $random;
	private $players;

	public function __construct(Input $playersInput, Output $output, Random $random)
	{
		$this->setPlayers($playersInput->read());
		$this->output = $output;
		$this->random = $random;
	}

	private function setPlayers(array $playerNames): void
	{
		$this->checkThereArePlayers($playerNames);
		$this->checkNumberOfPlayersIsEven($playerNames);

		$players = new PlayerStorage();
		foreach ($playerNames as $playerName) {
			$player = new Player($playerName);
			if ($players->contains($player)) {
				throw new RepeatedNamesException(sprintf("%s already used", $playerName));
			}
			$players->attach($player);
		}

		$this->players = $players;
	}

	private function checkThereArePlayers(array $playerNames): void
	{
		if (empty($playerNames)) {
			throw new NoPlayersFoundException("There are no players in order create a game");
		}
	}

	private function checkNumberOfPlayersIsEven(array $playerNames): void
	{
		if (count($playerNames) % 2 !== 0) {
			throw new OddNumberOfPlayersException(
				"Secret santa means that all players receives a gift, so provide an even number of players :D"
			);
		}
	}

	public function play(): void
	{
		$shufflePlayers = $this->getShufflePlayersArray();

		$giftReceivers = $shufflePlayers;
		$giftGivers = $shufflePlayers;
		$playersCount = count($shufflePlayers);
		$assignments = [];
		for ($i = 0; $i < $playersCount; $i++) {
			$randomGiftGiverPos = $this->getRandomGiverPosition($assignments, $giftGivers);
			$giftGiverName = $giftGivers[$randomGiftGiverPos]->name();
			$randomGiftReceiverPos = $this->getRandomReceiverPosition($assignments, $giftReceivers, $giftGiverName);
			$giftReceiverName = $giftReceivers[$randomGiftReceiverPos]->name();
			$assignments[$giftGiverName] = $giftReceiverName;
			$assignments = $this->ifLastAssignmentIsRepeatedChangeGiftReceiverValue($assignments, $giftReceiverName);
			array_splice($giftGivers, $randomGiftGiverPos, 1);
			array_splice($giftReceivers, $randomGiftReceiverPos, 1);
		}

		$this->printResult($assignments);
	}

	private function getShufflePlayersArray(): array
	{
		$players = [];
		foreach ($this->players as $player) {
			$players[] = $player;
		}

		shuffle($players);
		return $players;
	}

	private function getRandomGiverPosition(array $assignments, array $giftGivers): int
	{
		$random = $this->random->generate(0, count($giftGivers) - 1);
		while ($this->playerIsAlreadyAGiftGiver($assignments, $giftGivers[$random])) {
			$random = $this->random->generate(0, count($giftGivers) - 1);
		}

		return $random;
	}

	private function playerIsAlreadyAGiftGiver(array $assignments, Player $player): bool
	{
		foreach ($assignments as $giftGiver => $giftReceiver) {
			if ($giftGiver === $player->name()) {
				return true;
			}
		}

		return false;
	}

	private function getRandomReceiverPosition(array $assignments, array $giftReceivers, string $giftGiver): int
	{
		$random = $this->random->generate(0, count($giftReceivers) - 1);
		while ($giftGiver === $giftReceivers[$random]->name() &&
			!$this->playerIsAlreadyAReceiver($assignments, $giftReceivers[$random])
		) {
			$random = $this->random->generate(0, count($giftReceivers) - 1);

			if (count($giftReceivers) - 1 === 0) break;
		}

		return $random;
	}

	private function playerIsAlreadyAReceiver(array $playersList, Player $player): bool
	{
		foreach ($playersList as $giftGiver => $giftReceiver) {
			if ($giftReceiver === $player->name()) {
				return true;
			}
		}

		return false;
	}

	private function ifLastAssignmentIsRepeatedChangeGiftReceiverValue(array $assignments, string $lastReceiver): array
	{
		end($assignments);
		if (key($assignments) === $lastReceiver) {
			reset($assignments);
			$playerToChange = current($assignments);
			$keyWherePutLastReceiver = key($assignments);
			end($assignments);
			$assignments[$keyWherePutLastReceiver] = $lastReceiver;
			$assignments[$lastReceiver] = $playerToChange;
		}

		return $assignments;
	}

	private function printResult(array $assignments): void
	{
		foreach ($assignments as $player1 => $player2) {
			$this->output->printLine($player1 . ' -> ' . $player2);
		}
	}
}
