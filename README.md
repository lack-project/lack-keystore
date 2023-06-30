
# Keystore

## Usage

```php
KeyStore::SetKeyfile("/opt/.keystore.yml"); // Default file

$secret = KeyStore::Get()->getAccessKey(Service::GoogleMaps);
```
