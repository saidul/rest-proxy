rest-proxy
=========================

Simple Rest Proxy forked from Gonzalo123/rest-proxy

Almost identical to the original project although I was getting errors related to chunking on
larger requests. Decided to swap out the CurlWrapper with guzzle.

Example
=========================

```
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

use RestProxy\RestProxy;

$proxy = new RestProxy(Request::createFromGlobals());

// register the services you need for dev
$proxy->register('github', 'https://api.github.com');
$proxy->run();

echo $proxy->getContent();
```

How to install:
=========================
Install composer:
```
curl -s https://getcomposer.org/installer | php
```

Create a new project:

```
php composer.phar create-project plumpNation/rest-proxy proxy
```

Run dummy server (only with PHP5.4)

```
cd proxy
php -S localhost:8888 -t www/
```

Open a web browser and type: http://localhost:8888/github/users/plumpNation

