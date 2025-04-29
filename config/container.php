<?php

use App\Database\Transaction;
use App\Database\TransactionInterface;
use App\Support\Logger\LoggerFactoryInterface;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Psr\Container\ContainerInterface;
use Slim\App;
use DI\Bridge\Slim\Bridge;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Exception\HttpNotFoundException;
use Slim\Middleware\ErrorMiddleware;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;
use Tuupola\Middleware\CorsMiddleware;

return [
    "settings" => fn() => require __DIR__ . "/settings.php",
    App::class => function (ContainerInterface $container) {
        $app = Bridge::create($container);
        // Register routes
        (require __DIR__ . "/routes.php")($app);
        // Register middleware
        (require __DIR__ . "/middleware.php")($app);
        return $app;
    },
    ErrorMiddleware::class => function (ContainerInterface $container) {
        $app = $container->get(App::class);
        $settings = $container->get("settings")["error"];
        $loggerSettings = $container->get("settings")["logger"];
        $logger = new Logger('app');
        $filename = sprintf("%s/error.log", $loggerSettings["path"]);
        $level = $loggerSettings["level"];
        $fileHandler = new RotatingFileHandler($filename, 0, $level, true, 0777);
        $fileHandler->setFormatter(new LineFormatter(null, null, false, true));
        $logger->pushHandler($fileHandler);
        $errorMiddleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings["display_error_details"],
            (bool)$settings["log_errors"],
            (bool)$settings["log_error_details"],
            $logger
        );
        $errorMiddleware->setErrorHandler(
            HttpNotFoundException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write("404 Not found");
                return $response->withStatus(404);
            }
        );
        $errorMiddleware->setErrorHandler(
            HttpMethodNotAllowedException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write("405 Not allowed");
                return $response->withStatus(405);
            }
        );
        $errorMiddleware->setErrorHandler(
            HttpForbiddenException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write("403 Forbidden");
                return $response->withStatus(403);
            }
        );
        $errorMiddleware->setErrorHandler(
            HttpException::class,
            function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails) {
                $response = new Response();
                $response->getBody()->write("500 Internal server error");
                return $response->withStatus(500);
            }
        );
        return $errorMiddleware;
    },
    Application::class => function (ContainerInterface $container) {
        $application = new Application();
        $application->getDefinition()->addOption(
            new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The environment name.', 'dev')
        );

        foreach ($container->get('settings')['commands'] as $class) {
            $application->add($container->get($class));
        }
        
        return $application;
    },
    DatabaseManager::class => function (ContainerInterface $container) {
         $db = $container->get("settings")["db"];
         $config = [
            "default" => "default",
            "databases" => [
                "default" => [
                    "connection" => "mysql"
                ]
            ],
            "connections" => [
                'mysql' => new Cycle\Database\Config\MySQLDriverConfig(
                    connection: new Cycle\Database\Config\MySQL\TcpConnectionConfig(
                        database: $db["db_name"],
                        host: $db["host"],
                        port: $db["port"],
                        user: $db["username"],
                        password: $db["password"],
                    ),
                    queryCache: true
                ),
            ]
        ];

        return new DatabaseManager(new DatabaseConfig($config));
    },
    DatabaseInterface::class => function (ContainerInterface $container) {
        return $container->get(DatabaseManager::class)->database("default");
    },
    PDO::class => function (ContainerInterface $container) {
        $driver = $container->get(DatabaseManager::class)->driver("default");
        $class = new ReflectionClass($driver);
        $method = $class->getMethod("getPDO");
        $method->setAccessible(true);
        return $method->invoke($driver);
    },
    TransactionInterface::class => function (ContainerInterface $container) {
        return $container->get(Transaction::class);
    },
    CorsMiddleware::class => static function (ContainerInterface $container) {
        $settings = $container->get('settings')['cors'];

        return new CorsMiddleware([
            'origin' => $settings['origin'],
            'headers.allow' => $settings['headers.allow'],
            'credentials' => (bool)$settings['credentials']
        ]);
    },
];