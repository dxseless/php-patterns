<?php

/**
 * Команда - поведенческий паттерн проектирования, который позволяет выносить связь
 * с верхними объектами в отдельный класс. Данные классы и называются командами, и имеют
 * одинаковый интерфейс с методом типа ->execute() или ->handle(), возможно и __invoke().
 * 
 * Аналогия из фронтенда:
 * На ум приходит концепция контейнерных и глупых компонентов.
 * Доступ из UI в бизнес-логику через команду (коллбек от умного компонента глупому в React).
 */

/**
 * Интерфейс, определяющий метод запуска команды.
 * Команды внутри должны иметь свойство, содержащее ссылку на верхний компонент,
 * которому команда и будет передавать запросы.
 * 
 * Кроме этого, команда должна иметь поля для хранения параметров,
 * которые нужны при вызове методов верхнего компонента.
 */
interface CommandInterface
{
    public function execute(): void;
}

/**
 * Верхний компонент, он работает с БД (моделью User) 
 */
class UsersService
{
    public function createUser(string $userId)
    {
        echo "Creating user with id = {$userId}" . PHP_EOL;
    }

    public function deleteUser(string $userId)
    {
        echo "Deleting user with id = {$userId}" . PHP_EOL;
    }
}

/**
 * Команда на создание пользователя.
 * Содержит внутри себя все необходимые данные для создания пользователя
 */
class CreateUserCommand implements CommandInterface
{
    private string $userId;
    private UsersService $usersService;

    public function __construct(string $userId, UsersService $usersService)
    {
        $this->userId = $userId;
        $this->usersService = $usersService;
    }

    public function execute(): void
    {
        $this->usersService->createUser($this->userId);
    }
}

/**
 * Команда на удаление пользователя.
 * Содержит внутри себя все необходимые данные для удаления пользователя
 */
class DeleteUserCommand implements CommandInterface
{
    private string $userId;
    private UsersService $usersService;

    public function __construct(string $userId, UsersService $usersService)
    {
        $this->userId = $userId;
        $this->usersService = $usersService;
    }

    public function execute(): void
    {
        $this->usersService->deleteUser($this->userId);
    }
}

/**
 * Слой, который связывает интерфейс, через который пользователь взаимодействует с программой
 * и верхние слои (в нашем случае, через команды)
 */
class UsersController
{
    /**
     * Команды, как вариант, можно передавать методу через параметр.
     * Но, вряд ли в store будет иная команда, в нашем случае, нежели CreateUserCommand
     */
    public function store(string $userId)
    {
        $createUserCommand = new CreateUserCommand($userId, new UsersService);
        $createUserCommand->execute($userId);
    }

    public function destroy(string $userId)
    {
        $deleteUserCommand = new DeleteUserCommand($userId, new UsersService);
        $deleteUserCommand->execute($userId);
    }
}

$usersController = new UsersController;

$userId = uniqid();

$usersController->store($userId);
sleep(1);
$usersController->destroy($userId);