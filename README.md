# Domain Parking Landing Page

This is the steinbauer.org domain-parking landing page. It uses the server domain name to automatically pull a configuration and sets up widgets accordingly.

## Configuration

The page uses configuration found in JSON files to display information. The defaults are setup in `defaults.json` and can be overriden with domain specific configuration files `<domain>.json`. If a user hits a subdomain or FDQN i.e. `www.mydomain.com` the automatically fallbacks with the TLD are pulled (`mydomain.com`).

If a domain is just a fallback to another domain i.e. `mydomain.com` and `my-domain.com` then the config file can have the special key `forward` which denotes another domain name which is used to load the configuration. If the `forward` key is set no other config processing is performed! 