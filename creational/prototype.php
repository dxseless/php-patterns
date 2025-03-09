<?php

/**
 * Прототип - порождающий паттерн проектирования, задающий виды 
 * создаваемых объектов с помощью экземпляра-прототипа и создаёт
 * новый объекты путём копирования этого прототипа.
 */

/**
 * Дополнительный класс лишь для демонстрации глубокого копирования.
 * Т.к. clone в PHP делает поверхностную копию - должны через
 * __clone дополнительно склонировать объекты из свойств.
 */
class Message
{
    //
}

class Prototype
{
    private string $primitive;

    public Message $message;

    public function __construct()
    {
        $this->message = new Message;
    }

    public function __clone()
    {
        $this->message = clone $this->message;
    }
}

$p1 = new Prototype;
$p2 = clone $p1;

// Т.к. совершаем глубокое клонирование на 36 строке, то ожидаемо увидим false
var_dump($p1->message === $p2->message);