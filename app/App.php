<?php

declare(strict_types = 1);

namespace App;

use App\Contracts\EmailValidationInterface;
use App\Exceptions\RouteNotFoundException;
use App\Services\Emailable\EmailValidationService;
use App\Services\PaymentGatewayService;
use App\Services\PaymentGatewayServiceInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class App
{
    private static DB $db;

    public function __construct(
        protected \Illuminate\Container\Container $container,
        protected Router $router,
        protected array $request,
        protected Config $config
    ) {
        $loader = new FilesystemLoader(VIEW_PATH);
        $twig = new Environment($loader, [
            'cache' => STORAGE_PATH . '/cache',
            'auto_reload' => true,
        ]);
        static::$db = new DB($config->db ?? []);

        $this->container->bind(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
        $this->container->bind(EmailValidationInterface::class, fn() => new EmailValidationService($this->config->apiKeys['emailable']));
        $this->container->singleton(Environment::class, fn() => $twig);
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