<?php

/**
 * Простая фабрика - порождающий паттерн проектирования, который используется для создания объектов.
 * Он предоставляет единое место для создания объектов, что упрощает управление
 * созданием объектов и их конфигурацией.
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

class GuiFactory
{
    private string $platform;

    public function __construct(string $platform)
    {
        $this->platform = strtolower($platform);
    }

    public function makeButton(): ButtonInterface
    {
        switch ($this->platform) {
            case 'windows':
                return new WindowsButton;
            case 'linux':
                return new LinuxButton;
        }
    }
}

// Симулируем запуск на разных платформах
$platforms = ['windows', 'linux'];
$currentPlatform = $platforms[array_rand($platforms)];

$guiFactory = new GuiFactory($currentPlatform);

// Получаем определённую кнопку с общим интерфейсом
$button = $guiFactory->makeButton();

var_dump($button);