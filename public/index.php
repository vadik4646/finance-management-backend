<?php

use App\Kernel;
use App\Service\JsonRequest;
use Symfony\Component\Debug\Debug;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../vendor/autoload.php';

// The check is to ensure we don't use .env in production
if (!isset($_SERVER['APP_ENV'])) {
  if (!class_exists(Dotenv::class)) {
    throw new \RuntimeException(
      'APP_ENV environment variable is not defined. You need to define environment variables for configuration or add "symfony/dotenv" as a Composer dependency to load variables from a .env file.'
    );
  }
  (new Dotenv())->load(__DIR__ . '/../.env');
}

if ($_SERVER['APP_DEBUG'] ?? ('prod' !== ($_SERVER['APP_ENV'] ?? 'dev'))) {
  umask(0000);

  Debug::enable();
}

if ($trustedProxies = $_SERVER['TRUSTED_PROXIES'] ?? false) {
  JsonRequest::setTrustedProxies(
    explode(',', $trustedProxies),
    Request::HEADER_X_FORWARDED_ALL ^ Request::HEADER_X_FORWARDED_HOST
  );
}

if ($trustedHosts = $_SERVER['TRUSTED_HOSTS'] ?? false) {
  JsonRequest::setTrustedHosts(explode(',', $trustedHosts));
}

$kernel = new Kernel(
  $_SERVER['APP_ENV'] ?? 'dev', $_SERVER['APP_DEBUG'] ?? ('prod' !== ($_SERVER['APP_ENV'] ?? 'dev'))
);
$request = JsonRequest::createFromGlobals();
$jsonContent = json_decode($request->getContent(), true);
$request->setContent($jsonContent);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
