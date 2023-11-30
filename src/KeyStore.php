<?php

namespace Lack\Keystore;

use Lack\Keystore\Exception\KeyMissingException;
use Lack\Keystore\Exception\KeystoreException;
use Lack\Keystore\Type\Service;

class KeyStore
{

    private $keystoreData = [];


    protected function __construct(array $keystoreData, private string $filename) {
        $this->keystoreData = $keystoreData;
    }

    /**
     * Load the access key for the service in parameter 1
     *
     * @param string $service
     * @return string
     */
    public function getAccessKey(string|Service $service) : string {
        if ( ! isset ($this->keystoreData[$service])) {
            throw new KeyMissingException("Keystore: No key found for service '$service' (Keyfile: {$this->filename})");
        }
        if (is_string($this->keystoreData[$service])) {
            return $this->keystoreData[$service];
        }
        throw new KeyMissingException("Keystore: Invalid key definition for service '$service' (Keyfile: {$this->filename})");
    }

    private static self|null $instance =null;

    /**
     * Singleton get the Keystore
     *
     * @return self
     */
    public static function Get() : self {
        if (self::$instance === null) {
            $fileData = file_get_contents(self::$keyFile);
            if ($fileData === false)
                throw new KeystoreException("Cannot load Keystore file " . self::$keyFile);
            $data = yaml_parse($fileData);
            if ($data === false) {
                throw new KeystoreException("Keystore: Invalid yaml data in " . self::$keyFile);
            }
            self::$instance = new self($data, self::$keyFile);
        }
        return self::$instance;

    }

    private static string $keyFile = "/opt/.keystore.yml";

    public static function SetKeyfile (string $keyfile) {
        self::$keyFile = $keyfile;
    }

}
