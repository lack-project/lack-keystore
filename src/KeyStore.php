<?php

namespace Lack\Keystore;

use Lack\Keystore\Exception\KeyMissingException;

class KeyStore
{

    private $keystoreData = [];


    protected function __construct(array $keystoreData, private string $filename) {
        $this->keystoreData = $keystoreData;
    }

    /**
     * @param string $service
     * @return string
     */
    public function getAccessKey(string $service) : string {
        if ( ! isset ($this->keystoreData[$service])) {
            throw new KeyMissingException("Keystore: No key found for service '$service' (Keyfile: {$this->filename})");
        }
        if (is_string($this->keystoreData[$service])) {
            return $this->keystoreData[$service];
        }
        throw new KeyMissingException("Keystore: Invalid key definition for service '$service' (Keyfile: {$this->filename})");
    }


    /**
     * Singleton get
     *
     * @return self
     */
    public static function Get() {


    }

    private static string $keyFile = "/opt/.keystore";

    public static function SetKeyfile (string $keyfile) {
        self::$keyFile = $keyfile;
    }

}
