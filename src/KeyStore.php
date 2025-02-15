<?php

namespace Lack\Keystore;

use Lack\Keystore\Exception\KeyMissingException;
use Lack\Keystore\Exception\KeystoreException;
use Lack\Keystore\Loader\KeystoreDockerSecretLoader;
use Lack\Keystore\Loader\KeystoreFileLoader;
use Lack\Keystore\Loader\KeyStoreLoaderInterface;
use Lack\Keystore\Type\Service;

class KeyStore
{

    /**
     * @var KeyStoreLoaderInterface[]
     */
    private $loaders = [];


    /**
     * @param KeyStoreLoaderInterface[] $loader
     */
    protected function __construct(array $loader) {
        $this->loaders = $loader;
    }

    /**
     * Load the access key for the service in parameter 1
     *
     * @param string $service
     * @return string
     */
    public function getAccessKey(string|Service $service, bool $allowArray = false) : string|array {

        try {
            foreach ($this->loaders as $curLoader) {
                if ($curLoader->has($service)) {
                    $val = $curLoader->get($service);
                    if ( ! is_string($val) && ! $allowArray)
                        throw new \InvalidArgumentException("Keystore: Key for service '$service' is not a string.");
                    return $val;
                }
            }
        } catch (KeystoreException $e) {
            throw new KeyMissingException("Keystore: Error loading key for service '$service' (Keyfile: {$curLoader->getLastKeyFile()}): " . $e->getMessage(), 0, $e);
        } catch (KeyMissingException $e) {
            throw new KeyMissingException("Keystore: Error loading key for service '$service' (Keyfile: {$curLoader->getLastKeyFile()}): " . $e->getMessage(), 0, $e);
        }
        $files = [];
        foreach ($this->loaders as $curLoader) {
            $files[] =  $curLoader->getLastKeyFile();
        }
        throw new KeyMissingException("Keystore: No key found for service '$service' (Loaders: " . implode(", ", $files) . ")");
    }

    private static self|null $instance =null;

    /**
     * Singleton get the Keystore
     *
     * @return self
     */
    public static function Get() : self {
        if (self::$instance === null) {
            $loaders = [];
            foreach (self::$loader as $loader) {
                $loaders[] = new $loader();
            }
            self::$instance = new self($loaders);
        }
        return self::$instance;

    }

    private static array $loader = [
        KeystoreFileLoader::class,
        KeystoreDockerSecretLoader::class,
    ];

    private static ?string $keyFile = null;

    public static function SetKeyfile (string $keyfile) {
        self::$keyFile = $keyfile;
    }

}
