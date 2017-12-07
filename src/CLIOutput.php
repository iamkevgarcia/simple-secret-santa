<?php

namespace SecretSanta;


use SecretSanta\Contracts\Output;

class CLIOutput implements Output
{
    public function printLine(string $line)
    {
        echo $line . PHP_EOL;
    }
}