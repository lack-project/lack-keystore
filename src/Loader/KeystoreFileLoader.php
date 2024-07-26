<?php

namespace Lack\Keystore\Loader;

class KeystoreFileLoader implements KeyStoreLoaderInterface
{

    private $rootPath;

    private $lastKeyFile;

    public function __construct(string $keystoreFile = null) {
        if ($keystoreFile === null) {
            $keystoreFile = $this->detectKeystoreFileInSiblingPath();
        }

        if ($keystoreFile === null) {
            $keystoreFile = $this->detectKeystoreFileInSearchPath();
        }
        $this->rootPath = $keystoreFile;
    }

    const SEARCH_PATH = [
        "/etc",
        "/run/secrets",
        "/var/run"
    ];

    public function detectKeystoreFileInSearchPath() {
        foreach (self::SEARCH_PATH as $path) {
            $keystoreFile = $path . "/.keystore.yml";

            if (file_exists($keystoreFile)) {
                return $keystoreFile;
            }
        }

        return null;

    }

    public function detectKeystoreFileInSiblingPath(string $cwd = null) {
        if ($cwd === null) {
            $cwd = getcwd();
        }

        $path = $cwd;

        while ($path !== "/") {
            $keystoreFile = $path . "/.keystore.yml";

            if (file_exists($keystoreFile)) {
                return $keystoreFile;
            }

            $path = dirname($path);
        }

        return null;
    }

    public function has(string $key) : bool {
        if ($this->rootPath === null) {
            return false;
        }
        $data = yaml_parse(file_get_contents($this->rootPath));
        return isset($data[$key]);
    }

    public function getLastKeyFile(): string
    {

        return $this->rootPath;
    }


    public function get(string $key) : string|array {
        if ($this->rootPath === null) {
            throw new KeyMissingException("Keystore: No .keystore.yml found in path: './', " . implode(", ", self::SEARCH_PATH) . " or in parent path.");
        }
        $data = yaml_parse(file_get_contents($this->rootPath));
        if ( ! isset ($data[$key])) {
            throw new KeyMissingException("No key found for service '$key'");
        }
        return $data[$key];
    }
}
