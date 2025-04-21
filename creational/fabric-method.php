<?php

/**
 * Фабричный метод - порождающий паттерн проектирования, который позволяет создавать объекты,
 * не указывая конкретный класс создаваемого объекта.
 *
 * В отличие от абстрактной фабрики, которая фокусируется на создании семейства объектов,
 * фабричный метод фокусируется на создании объектов одного типа.
 */

interface ButtonInterface
{
    public function render(): void;
    public function onClick(): void;
}

class WindowsButton implements ButtonInterface
{
    public function render(): void
    {
        echo '<windows-button />' . PHP_EOL;
    }

    public function onClick(): void
    {
        echo 'Clicked on WindowsButton' . PHP_EOL;
    }
}

class LinuxButton implements ButtonInterface
{
    public function render(): void
    {
        echo '<linux-button />' . PHP_EOL;
    }

    public function onClick(): void
    {
        echo 'Clicked on LinuxButton' . PHP_EOL;
    } 
}

abstract class ButtonFactory
{
    abstract public function createButton(): ButtonInterface;

    public function renderButton(): void
    {
        $button = $this->createButton();
        $button->render();
    }

    public function clickButton(): void
    {
        $button = $this->createButton();
        $button->onClick();
    }
}

class WindowsButtonFactory extends ButtonFactory
{
    public function createButton(): ButtonInterface
    {
        return new WindowsButton();
    }
}

class LinuxButtonFactory extends ButtonFactory
{
    public function createButton(): ButtonInterface
    {
        return new LinuxButton();
    }
}

$platforms = ['windows', 'linux'];
$currentPlatform = $platforms[array_rand($platforms)];

if ($currentPlatform === 'windows') {
    $buttonFactory = new WindowsButtonFactory();
} elseif ($currentPlatform === 'linux') {
    $buttonFactory = new LinuxButtonFactory();
}

$buttonFactory->renderButton();
$buttonFactory->clickButton();