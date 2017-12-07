<?php

namespace Tests;


use org\bovigo\vfs\vfsStream;
use SecretSanta\PlayerInputFromFileReader;

final class PlayerInputFromFileReaderStub
{
	public static function withRepeatedPlayerNames()
	{
		$root = vfsStream::setup();
		$file = vfsStream::newFile('test.txt', 0755)
			->withContent('Stub' . PHP_EOL . 'Stub')
			->at($root);

		return new PlayerInputFromFileReader($file->url());
	}

	public static function withNoPlayers()
	{
		$root = vfsStream::setup();
		$file = vfsStream::newFile('test.txt', 0755)
			->withContent('')
			->at($root);

		return new PlayerInputFromFileReader($file->url());
	}

	public static function oddPlayers()
	{
		$root = vfsStream::setup();
		$file = vfsStream::newFile('test.txt', 0755)
			->withContent('PlayerInput' . PHP_EOL . 'Test' . PHP_EOL . 'Double')
			->at($root);

		return new PlayerInputFromFileReader($file->url());
	}

	public static function evenPlayers()
	{
		$root = vfsStream::setup();
		$file = vfsStream::newFile('test.txt', 0755)
			->withContent('PlayerInput' . PHP_EOL . 'Test' . PHP_EOL . 'Double' . PHP_EOL . 'BTS')
			->at($root);

		return new PlayerInputFromFileReader($file->url());
	}
}
