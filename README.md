
# Keystore

## Usage

```php
KeyStore::SetKeyfile("/opt/.keystore.yml"); // Default file

$secret = KeyStore::Get()->getAccessKey(Service::GoogleMaps);
```


## Example `.keystore.yml`


```yaml
google_maps: gx-02kjlskjehzlwk...
open_ai: ai-398hwlwkjdl...
```
