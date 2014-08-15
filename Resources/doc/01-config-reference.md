Configuration Reference
=======================

All configuration options with default values are listed below:

```yaml
# app/config/config.yml

amf_openvpn:
    servers:
        1: 
            name: ""
            telnet_ip: "127.0.0.1"
            telnet_port: "7500"
    config:
        reload: "5"
```