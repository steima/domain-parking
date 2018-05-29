# Domain Parking Landing Page

This is the [steinbauer.org](https://steinbauer.org) domain-parking landing page. If you are anything like me, you will sometimes just buy a domain in the hope you can sell it later at a higher price. While holding the domain you want to put some minimal interface to make clear to potentialy buyers that your domain is for sale.

In this project you will find a simple minimal PHP script which provides a minimal landing page for domain parking. It uses the server domain name to automatically load a configuration from a JSON file and sets up widgets accordingly.

To install this script usually if you bought your domain with a webspace you would just upload the files found in this repository to the webspace and everything should work out of the box.

I run the script on a webserver machine which defaults to use the scripts found in this repo if no other more specific VHost configuration was found in Apache.

## Configuration

The page uses configuration found in JSON files to display information. The defaults are setup in `defaults.json` and can be overriden with domain specific configuration files `<domain>.json`. If a user hits a subdomain or FDQN i.e. `www.mydomain.com` the automatically fallbacks with the TLD are pulled (`mydomain.com`).

If a domain is just a fallback to another domain i.e. `mydomain.com` and `my-domain.com` then the config file can have the special key `forward` which denotes another domain name which is used to load the configuration. If the `forward` key is set no other config processing is performed!