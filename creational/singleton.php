<?php

/**
 * Singleton - порождающий паттерн проектирования
 * 
 * Класс может запретить создание дополнительных экземплеров, перехватывая
 * запросы на создание новых объектов.
 * И он же способен предоставить доступ  к своему экземпляру.
 */
class Singleton
{
    private static $instance;

    /**
     * Запрещаем создавать новые инстансы через new
     */
    private function __construct()
    {}

    /**
     * Запрещаем клонировать в новые инстансы через clone
    */
    private function __clone()
    {}

    // Предотвращаем десериализацию экземпляра
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    /**
     * Клиенты осуществляют доступ к одиночке исключительно
     * через метод getInstance (название может быть любым)
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}

$one = Singleton::getInstance();
$second = Singleton::getInstance();

// Проверка объекта на идентичность. Даст true, если это один и тот же объект
var_dump($one === $second);