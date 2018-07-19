# TSDNS-over-Cloudflare-APIv4
Using the Cloudflare API v4 to create SRV record

### Edit config.php

```
$apikey = 'you api key';
$email = 'example@gmail.com';  
$domains = array(
    'domain1.com',
    'domain2.com',
);
```

### Edit index.php
Use A record of direction to IP (example.com = 1.2.3.4)
```
<option value="example.com" text="example.com" name="ip">example.com</option>
<option value="example2.com" text="example2.com" name="ip">example2.com</option>
 ```
