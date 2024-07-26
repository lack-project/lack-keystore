<?php

namespace Lack\Keystore\Loader;

interface KeyStoreLoaderInterface
{

    public function has(string $key) : bool;

    public function get(string $key) : string|array;

    /**
     * Return the filename of the last key file that was read
     *
     * @return string
     */
    public function getLastKeyFile() : string;
}
