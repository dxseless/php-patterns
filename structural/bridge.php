<?php

/**
 * Мост — это структурный паттерн проектирования,
 *  который разделяет один или несколько классов на две отдельные иерархии
 * — абстракцию и реализацию, позволяя изменять их независимо друг от друга.
 */

/**
 * Абстракция, определяющая как хранить ServiceApi
 * Этот класс не обязателен, эту же логику можно поместить и в конкретный класс
 * Объект абстракции перенаправляет запросы клиента своему объекту реализации
 */
abstract class UIFramework
{
    private ServiceApi $serviceApi;

    public function __construct(ServiceApi $serviceApi)
    {
        $this->serviceApi = $serviceApi;
    }

    public function getServiceApi(): ServiceApi
    {
        return $this->serviceApi;
    }
}

/**
 * Уточнённая абстракция. (Так рекомендует GOF, но не обязательно)
 * UiFramework управляет только UI элементами на страницах.
 * Взаимодействие с бэкендом вынесено в отдельный API-слой.
 * UI никак не зависит от API.
 * 
 */
class VueFramework extends UIFramework
{
    public function renderAdminPage(): void
    {
        $this->getServiceApi()->getAllUsers();
    }

    public function handleCreateFormSubmit(array $event)
    {
        $this->getServiceApi()->createUser($event['data']);
    }

    public function handleEditFormSubmit(array $event)
    {
        $this->getServiceApi()->editUser($event['uid'], $event['data']);
    }

    public function handleDeleteButtonClick(array $event)
    {
        $this->getServiceApi()->deleteUser($event['uid']);
    }
}

/**
 * Интерфейс к реализациям.
 * Обычно такой интерфейс описывает только примитивные операции,
 * а класс абстракции определяет операции более высокого уровня,
 * основанные на этих примитивах
 */
interface ServiceApi
{
    public function createUser(array $data): bool;
    public function getAllUsers(): array;
    public function editUser(string $uid, array $data): bool;
    public function deleteUser(string $uid): bool;
}

/**
 * Реализация взаимодействия с бэкендом через REST (JSON)
 */
class ServiceInteractionApiViaRest implements ServiceApi
{
    public function createUser(array $data): bool
    {
        echo "[REST]: Создаю пользователя" . PHP_EOL;
        return true;
    }

    public function getAllUsers(): array
    {
        echo "[REST]: Запрашиваю пользователей" . PHP_EOL;
        return [['name' => 'Vasya'], ['name' => 'Ivan'], ['name' => 'Anya']];
    }

    public function editUser(string $uid, array $data): bool
    {
        echo "[REST]: Изменяю пользователя {$uid}" . PHP_EOL;
        return true;
    }

    public function deleteUser(string $uid): bool
    {
        echo "[REST]: Удаляю пользователя {$uid}" . PHP_EOL;
        return true;
    }
}

/**
 * Реализация взаимодействия с бэкендом через SOAP (XML)
 */
class ServiceInteractionApiViaSoap implements ServiceApi
{
    public function createUser(array $data): bool
    {
        echo "[SOAP]: Создаю пользователя" . PHP_EOL;
        return true;
    }

    public function getAllUsers(): array
    {
        echo "[SOAP]: Запрашиваю пользователей" . PHP_EOL;
        return [['name' => 'Vasya'], ['name' => 'Ivan'], ['name' => 'Anya']];
    }

    public function editUser(string $uid, array $data): bool
    {
        echo "[SOAP]: Изменяю пользователя {$uid}" . PHP_EOL;
        return true;
    }

    public function deleteUser(string $uid): bool
    {
        echo "[SOAP]: Удаляю пользователя {$uid}" . PHP_EOL;
        return true;
    }
}

$services = [
    fn() => new ServiceInteractionApiViaRest,
    fn() => new ServiceInteractionApiViaSoap,
];

/**
 * Выбираем случайную реализацию.
 * Это показывает полную независимость абстракции от реализации, т.е.
 * мы можем изменять UI без необходимости трогать слой взаимодействия с API.
 */
$apiService = $services[array_rand($services)]();

/**
 * Клиент должен подать объект реализации в конструктор абстракции, чтобы связать их воедино.
 * После этого он может свободно использовать объект абстракции, забыв о реализации.
 */
$vue = new VueFramework($apiService);

$vue->renderAdminPage();
$vue->handleCreateFormSubmit(['data' => ['name' => 'Ivan333']]);
$vue->handleDeleteButtonClick(['uid' => 1]);
$vue->handleEditFormSubmit(['uid' => 1, 'data' => ['Ivan555']]);