Getting started with AMFOpenVpnBundle
=======================================

* [Installation](#installation)
* [Base usage](#basic usage)



1) Installation
-------------------------------

Installation is done within 3 process:

1. Download AMFOpenVpnBundle using composer
2. Enable the Bundle
3. Configure your application's config.yml

### Step 1: Download AMFOpenVpnBundle using composer

Add AMFOpenVpnBundle in your composer.json:

```json
  {
      "require": {
        "amf/openvpn-bundle": "dev-master"
      }
  }
```

Now tell composer to download the bundle by running the command:

   ``` bash
   $ php composer.phar update amf/openvpn-bundle
   ```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
// app/ApplicationKernel.php
public function registerBundles()
{
      return array(
          // ...
          new AMF\OpenVpnBundle\AMFOpenVpnBundle(),
          // ...
      );
}
```

### Step3: Register the bundle's routes

Finally, add the following routes to your application:

``` yaml
# app/config/routing.yml
amf_openvpn:
    resource: "@AMFOpenVpnBundle/Resources/config/routing.yml"
    prefix:   /openvpn
```

Congratulations! Now you interact with vpn servers!


2) Basic usage
-------------------------------

This bundle works by configuring a set of openvpn servers usign their telnet configuration
assign a name to every server:

``` yaml
# app/config/config.yml
amf_openvpn:
    servers:
        1: 
            name: "my first server"
            telnet_ip: "127.0.0.1"
            telnet_port: "7500"
```

[Read the whole configuration reference](01-config-reference.md)