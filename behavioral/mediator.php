<?php

/**
 * Медиатор - поведенческий паттерн проектирования, инкапсулирующий способ
 * взаимодействия множества объектов. Медиатор обеспечивает слабую связанность
 * системы, избавляя объекты от необходимости явно ссылаться друг на друга
 * и позволяя тем самым независимо изменять взаимодействия между ними.
 */

/**
 * Медиатор заменяет взаимодействия "все со всеми" взаимодействиями "один со всеми",
 * то есть посредник взаимодействует со всеми коллегами. Такая модель проще
 * для понимания, сопровождения и расширения.
 */
interface MediatorInterface
{
    public function notify($sender, string $event): void;
}

/**
 * Медиатор будет обеспечивать централизованное управление обработкой HTTP-запроса
 */
class HttpMediator implements MediatorInterface
{
    /**
     * @var BaseController[] $controllers
     */
    private array $controllers = [];

    public function registerController(BaseController $controller)
    {
        $this->controllers[$controller::class] = $controller;
    }

    /**
     * Медиатор может использовать информацию о том, кто инициировал событие ($sender)
     */
    public function notify($sender, string $event): void
    {
        switch ($event) {
            case 'GET':
                echo "Mediator handling GET request from " . $sender::class . ' ' . PHP_EOL;
                $this->controllers[GetController::class]->handle();
                break;
            case 'POST':
                echo "Mediator handling POST request from" . $sender::class . ' ' . PHP_EOL;
                $this->controllers[PostController::class]->handle();
                break;
        }
    }
}

/**
 * Медиатор избавляет объекты от необходимости явно ссылаться друг на друга.
 * Все объекты располагают информацией только о посреднике, поэтому количество
 * взаимосвязей сокращается.
 */
abstract class BaseController
{
    protected MediatorInterface $mediator;

    public function setMediator(MediatorInterface $mediator)
    {
        $this->mediator = $mediator;
    }

    abstract public function handle();
}

class GetController extends BaseController
{
    public function handle()
    {
        echo "Handling GET request" . PHP_EOL;
    }
}

class PostController extends BaseController
{
    public function handle()
    {
        echo "Handling POST request" . PHP_EOL;
    }
}

$httpMediator = new HttpMediator;

$getController = new GetController;
$postController = new PostController;

$httpMediator->registerController($getController);
$httpMediator->registerController($postController);

$httpRequests = [
    ['controller' => $getController, 'method' => 'GET'],
    ['controller' => $postController, 'method' => 'POST']
];

$currentRequest = $httpRequests[array_rand($httpRequests)];

$httpMediator->notify(
    $currentRequest['controller'],
    $currentRequest['method']
);