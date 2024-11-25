<?php

declare(strict_types = 1);

namespace App;

use App\Exceptions\RouteNotFoundException;
use App\Services\Emailable\EmailValidationService;
use App\Services\PaymentGatewayService;
use App\Services\PaymentGatewayServiceInterface;

class App
{
    private static DB $db;

    public function __construct(
        protected \Illuminate\Container\Container $container,
        protected Router $router,
        protected array $request,
        protected Config $config
    ) {
        static::$db = new DB($config->db ?? []);

        $this->container->bind(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
        $this->container->bind(EmailValidationService::class, fn() => new EmailValidationService($this->config->apiKeys['emailable']));
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        try {
            echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
        } catch (RouteNotFoundException) {
            //http_response_code(404);

            echo View::make('error/404');
        }
    }
}