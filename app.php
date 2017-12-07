<?php

use SecretSanta\CLIOutput;
use SecretSanta\PlayerInputFromFileReader;
use SecretSanta\RandomInteger;
use SecretSanta\SecretSantaGame;

require_once __DIR__ . '/vendor/autoload.php';

$inputReader = new PlayerInputFromFileReader('players.txt');
$output = new CLIOutput();
$random = new RandomInteger();

$game = new SecretSantaGame($inputReader, $output, $random);
$game->play();