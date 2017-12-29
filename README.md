# slim-api

Api Rest con [ Slim Framework](https://www.slimframework.com/)


### Instalación manual:

> Primer tener instalado composer "Gestor de paquetes en php", si tienes una mac puedes usar "brew install" y si deseas instalar en linux puedes verificar el siguiente enlace [instalación en linux](http://librosweb.es/libro/composer/capitulo_1/instalacion_en_servidores_linux.html)

Paso a seguir:

<pre> 
$ composer require slim/slim "^3.0"
$ composer require vlucas/phpdotenv
$ composer require monolog/monolog
$ composer require tuupola/slim-basic-auth
$ composer require tuupola/cors-middleware
$ composer require tuupola/slim-jwt-auth
$ composer require tuupola/base62
$ composer require firebase/php-jwt
$ composer require gofabian/negotiation-middleware
</pre>

### Instalación Global:

Requisitos configuración, verificar el archivo composer.js
puedes realizar también una instalación global apartir del mismo archivo usando la siguiente line de comando.

<pre>$ composer install o componser update</pre>


Creditos a tuupola.

Proyecto de referencia: https://github.com/tuupola/slim-api-skeleton
