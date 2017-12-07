<?php

declare(strict_types=1);

namespace Tests;


use PHPUnit\Framework\TestCase;
use SecretSanta\CLIOutput;
use SecretSanta\Contracts\Output;
use SecretSanta\Contracts\Random;
use SecretSanta\RandomInteger;
use SecretSanta\SecretSantaGame;

class SecretSantaGameShould extends TestCase
{
    /**
     * @test
	 * @expectedException SecretSanta\Exception\NoPlayersFoundException
	 */
    public function throw_an_exception_after_passing_no_player_names(): void
    {
    	$repeatedNames = PlayerInputFromFileReaderStub::withNoPlayers();
		$output = $this->createMock(Output::class);
		$random = $this->createMock(Random::class);

		new SecretSantaGame($repeatedNames, $output, $random);
    }

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

	/**
	 * @test
	 * @expectedException SecretSanta\Exception\OddNumberOfPlayersException
	 */
	public function throw_an_exception_after_passing_odd_quantity_players(): void
	{
		$repeatedNames = PlayerInputFromFileReaderStub::oddPlayers();
		$output = $this->createMock(Output::class);
		$random = $this->createMock(Random::class);

		new SecretSantaGame($repeatedNames, $output, $random);
	}

	/**
	 * @test
	 */
	public function print_a_secret_santa_result_with_payers_not_assigned_themselves_and_player_playing_gift_giver_and_gift_receiver_roles(): void
	{
		$repeatedNames = PlayerInputFromFileReaderStub::evenPlayers();
		$output = new CLIOutput();
		$random = new RandomInteger();

		for ($i = 0; $i < 15; $i++) {
			ob_start();
			$game = new SecretSantaGame($repeatedNames, $output, $random);
			$game->play();
			$secretSantaResult = $this->getActualOutput();
			ob_end_clean();

			$this->assertNotEmpty($secretSantaResult);
			$this->assertThereIsNoPlayerAssignedItself($secretSantaResult);
			$this->assertThereIsNoPlayerWithMoreThanTwoOccurrences($secretSantaResult);
			$this->assertThatEveryoneHasSomeoneToGiveAPresentAndEveryoneHasSomeoneWhoWillGiveThemOne($secretSantaResult);
		}
	}

	private function assertThereIsNoPlayerWithMoreThanTwoOccurrences(string $secretSantaResult): void
	{
		$wordsCount = array_count_values(str_word_count($secretSantaResult, 1));

		foreach ($wordsCount as $word => $count) {
			if ($word != '-' && $count > 2) {
				$this->assertTrue(false);
				return;
			}
		}

		$this->assertTrue(true);
	}

	private function assertThereIsNoPlayerAssignedItself(string $result): void
	{
		$secretSantaAssignments = explode(PHP_EOL, trim($result));

		foreach ($secretSantaAssignments as $assignment) {
			$firstWord = strtok($assignment, " ");
			if (substr_count($assignment, $firstWord) > 1) {
				$this->assertTrue(false);
				return;
			}
		}

		$this->assertTrue(true);
	}

	private function assertThatEveryoneHasSomeoneToGiveAPresentAndEveryoneHasSomeoneWhoWillGiveThemOne(string $result): void
	{
		$secretSantaAssignments = explode(PHP_EOL, trim($result));

		$onesWhoWillGiveAPresent = [];
		$onesWhoWillReceiveAPresent = [];
		foreach ($secretSantaAssignments as $assignment) {
			$exploded = explode(' ', $assignment);
			$onesWhoWillGiveAPresent[] = end($exploded);
			$onesWhoWillReceiveAPresent[] = reset($exploded);
		}

		foreach ($onesWhoWillGiveAPresent as $playerName) {
			if (!in_array($playerName, $onesWhoWillReceiveAPresent)) {
				$this->assertTrue(false);
				return;
			}
		}

		$this->assertTrue(true);
	}
}