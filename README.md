
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

## Search Path

By default the keystore will search for the file in the following paths for a .keystore.yml:

- cur CWD and subpaths
- /run/secrets/.keystore.yml

The Keystore will then search for a filename "service_name" 

in 

```
/run/secrets/<service_name>
```