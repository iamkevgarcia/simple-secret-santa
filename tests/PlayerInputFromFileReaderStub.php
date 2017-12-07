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
}