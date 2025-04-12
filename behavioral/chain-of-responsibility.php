<?php

/**
 * Цепочка обязанностей - поведенческий паттерн проектирования,  который позволяет
 * обработать запрос нескольким объектам. Он связывает объекты-получатели в цепочку
 * и передаёт запрос по этой цепочке, пока он не будет обработан.
 * 
 * Идея паттерна заключается в том, чтобы разорвать связь между отправителями
 * и получателями, дав возможность обработать запрос нескольким объектам.
 * Запрос перемещается по цепочке объектов, пока не будет обработан.
 */

/**
 * Включенный в цепочку объектов элементов ничего не знает о структуре цепочки и хранит
 * ссылку лишь на следующий элемент в цепи.
 */
interface MiddlewareInterface
{
    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface;
    public function handle(array $request): mixed;
}

/**
 * В абстрактный класс вынесены общие операции объектов цепочки
 */
abstract class Middleware implements MiddlewareInterface
{
    private MiddlewareInterface $nextMiddleware;

    public function setNext(MiddlewareInterface $middleware): MiddlewareInterface
    {
        $this->nextMiddleware = $middleware;
        return $middleware;
    }

    public function handle(array $request): mixed
    {
        if (isset($this->nextMiddleware)) {
            return $this->nextMiddleware->handle($request);
        }

        return null;
    }
}

/**
 * Если обработчик способен обработать запрос, то именно он и обрабатывает его (ветка else),
 * иначе - обработка передаётся следующему объекту цепи.
 */
class AuthenticationMiddleware extends Middleware
{
    public function handle(array $request): mixed
    {
        if (isset($request['user'])) {
            echo "[AUTH]: Authentication successfull for user: " . $request['user'] . PHP_EOL;
            return parent::handle($request);
        } else {
            echo "[AUTH]: Authentication failed" . PHP_EOL;
            return "Access denied" . PHP_EOL;
        }
    }
}

class LoggingMiddleware extends Middleware
{
    public function handle(array $request): mixed
    {
        echo "[LOG]: Logging request: " . json_encode($request) . PHP_EOL;
        return parent::handle($request);
    }
}

class ValidationMiddleware extends Middleware
{
    public function handle(array $request): mixed
    {
        if (isset($request['data']) && ! empty($request['data'])) {
            echo "[VALIDATION]: Validation successfull for data: " . $request['data'] . PHP_EOL;
            return parent::handle($request);
        } else {
            echo "[VALIDATION]: Validation failed" . PHP_EOL;
            return "Invalid data" . PHP_EOL;
        }
    }
}

/**
 * Клиентский код, тут отправляется запрос к элементу в цепи.
 */
class Client
{
    private MiddlewareInterface $middleware;

    public function __construct(MiddlewareInterface $middleware)
    {
        $this->middleware = $middleware;
    }

    public function handleRequest(array $request)
    {
        $error = $this->middleware->handle($request);

        if ($error) {
            echo "Error happended: {$error}" . PHP_EOL;
        } else {
            echo "All OK" . PHP_EOL;
        }
    }
}

$authMiddleware = new AuthenticationMiddleware;
$loggingMiddleware = new LoggingMiddleware;
$validationMiddleware = new ValidationMiddleware;

$authMiddleware->setNext($loggingMiddleware)->setNext($validationMiddleware);

$client = new Client($authMiddleware);

$requests = [
    ['user' => 'Alice', 'data' => 'Some data'],
    ['user' => 'Bob', 'data' => ''],
    ['data' => 'Valid data'],
    ['user' => 'Charlie']
];

foreach ($requests as $request) {
    $client->handleRequest($request);
    echo PHP_EOL .  "=====" . PHP_EOL;
}