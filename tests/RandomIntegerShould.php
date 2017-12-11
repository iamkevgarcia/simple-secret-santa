<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use SecretSanta\RandomInteger;

class RandomIntegerShould extends TestCase
{
	/**
	 * @test
	 * @expectedException SecretSanta\Exception\InvalidMinimumValueException
	 */
	public function throw_an_exception_after_passing_a_min_value_not_lesser_than_maximum(): void
	{
		$random = new RandomInteger();

		$random->generate(3, 2);
	}

	/**
	 * @test
	 */
	public function return_random_value_after_passing_min_and_max_value(): void
	{
		$random = new RandomInteger();

		$integer = $random->generate(1, 2);

		$this->assertTrue(is_integer($integer));
	}
}
