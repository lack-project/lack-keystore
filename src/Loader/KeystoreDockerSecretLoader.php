<?php

namespace Lack\Keystore\Loader;

class KeystoreDockerSecretLoader implements KeyStoreLoaderInterface
{

        private $rootPath;

        private $lastKeyFile;

        public function __construct(string $rootPath = "/run/secrets") {
            $this->rootPath = $rootPath;
        }

        public function has(string $key) : bool {
            $this->lastKeyFile = $this->rootPath . "/" . $key;
            return file_exists($this->lastKeyFile);
        }

        public function getLastKeyFile(): string
        {
            return $this->lastKeyFile;
        }

        public function get(string $key) : string|array {
            $this->lastKeyFile = $this->rootPath . "/" . $key;
            return file_get_contents($this->lastKeyFile);
        }

}
