<?php

declare(strict_types=1);

namespace Tests;


use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use SecretSanta\PlayerInputFromFileReader;

class PlayerInputFromFileReaderShould extends TestCase
{
	private $root;

	public function setUp()
	{
		$this->root = vfsStream::setup();
	}


	/**
	 * @test
	 * @expectedException SecretSanta\Exception\FileDoesNotExistException
	 */
	public function throw_a_file_does_not_exists_exception_after_passing_non_existent_file(): void
	{
		new PlayerInputFromFileReader('');
	}

	/**
	 * @test
	 * @expectedException SecretSanta\Exception\FileNotReadableException
	 */
	public function throw_a_file_not_readable_exception_after_passing_non_readable_file(): void
	{
		$file = vfsStream::newFile('test.txt', 0000)
			->withContent('nothingSoFar')
			->at($this->root);

		new PlayerInputFromFileReader($file->url());
	}

	/**
	 * @test
	 */
	public function return_players_read_from_file(): void
	{
		$file = vfsStream::newFile('test.txt', 0755)
			->withContent('Fun' . PHP_EOL . 'Fun' . PHP_EOL . 'Function')
			->at($this->root);
		$inputReader = new PlayerInputFromFileReader($file->url());

		$players = $inputReader->read();

		$this->assertNotEmpty($players);
		$this->assertCount(3, $players);
	}
}

