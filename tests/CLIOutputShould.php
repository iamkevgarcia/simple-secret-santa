<?php

declare(strict_types=1);

namespace Tests;


use PHPUnit\Framework\TestCase;
use SecretSanta\CLIOutput;

class CLIOutputShould extends TestCase
{
	/**
	 * @test
	 */
	public function have_printed_a_line_after_passing_a_string(): void
	{
		$expectedString = "just a rubber duck";
		$output = new CLIOutput();

		$output->printLine($expectedString);

		$this->expectOutputString($expectedString . PHP_EOL);
	}
}
