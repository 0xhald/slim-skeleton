<?php

use App\Database\Transaction;
use App\Database\TransactionInterface;
use Cycle\Database\Config\DatabaseConfig;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseManager;
use Psr\Container\ContainerInterface;
use Slim\App;
use DI\Bridge\Slim\Bridge;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

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
    }
];