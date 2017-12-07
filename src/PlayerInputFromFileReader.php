<?php

declare(strict_types=1);

namespace SecretSanta;


use SecretSanta\Contracts\Input;
use SecretSanta\Exception\FileDoesNotExistException;
use SecretSanta\Exception\FileNotReadableException;
use SecretSanta\Exception\InvalidPlayerFileSyntaxException;
use SplFileObject;
use SplObjectStorage;

final class PlayerInputFromFileReader implements Input
{
	private $playersFile;

	public function __construct(string $playersFilePath)
	{
		$this->setPlayersFile($playersFilePath);
	}

	private function setPlayersFile(string $playersFilePath): void
	{
		if (!file_exists($playersFilePath)) {
			throw new FileDoesNotExistException(sprintf("file: %s does not exist", $playersFilePath));
		}

		if (!is_readable($playersFilePath)) {
			throw new FileNotReadableException(sprintf("file: %s not readable", $playersFilePath));
		}

		$this->playersFile = $playersFilePath;
	}

	public function read(): array
	{
		$file = new SplFileObject($this->playersFile, "r");

		$players = [];
		while (!$file->eof()) {
			$players[] = rtrim($file->fgets());
		}

		return $players;
	}
}