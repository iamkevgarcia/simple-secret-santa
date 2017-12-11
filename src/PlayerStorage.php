<?php

namespace SecretSanta;


use SplObjectStorage;

final class PlayerStorage extends SPLObjectStorage
{
    public function getHash($obj)
    {
        return strtolower($obj->name());
    }
}
