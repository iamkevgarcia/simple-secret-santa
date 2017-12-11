<?php

declare(strict_types=1);

namespace SecretSanta;


use SecretSanta\Contracts\Output;

final class CLIOutput implements Output
{
	public function printLine(string $line): void
	{
		echo $line . PHP_EOL;
	}
}
