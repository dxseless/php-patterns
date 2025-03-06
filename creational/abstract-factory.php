<?php

/**
 * Абстрактная фабрика - порождающий паттерн проектирования, который позволяет создавать
 * семейства взаимосвязанных или взаимозависимых объектов, не специфицируя их конкретных классов
 */

interface ButtonInterface
{
    public function render(): void;
    public function onClick(): void;
}

interface DialogInterface
{
    public function render(): void;
    public function onClose(): void;
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

class WindowsDialog implements DialogInterface
{
    public function render(): void
    {
        echo '<windows-dialog />' . PHP_EOL;
    }

    public function onClose(): void
    {
        echo 'Closing WindowsDialog' . PHP_EOL;
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
        echo 'Clicked on LinuxButton';
    } 
}

class LinuxDialog implements DialogInterface
{
    public function render(): void
    {
        echo '<linux-dialog />' . PHP_EOL;
    }

    public function onClose(): void
    {
        echo 'Closing LinuxDialog' . PHP_EOL;
    }
}

interface GuiFactoryInterface
{
    public function makeButton(): ButtonInterface;
}

class WindowsGuiFactory implements GuiFactoryInterface
{
    public function makeButton(): ButtonInterface
    {
        return new WindowsButton;
    }

    public function makeDialog(): DialogInterface
    {
        return new WindowsDialog;
    }
}

class LinuxGuiFactory implements GuiFactoryInterface
{
    public function makeButton(): ButtonInterface
    {
        return new LinuxButton;
    }

    public function makeDialog(): DialogInterface
    {
        return new LinuxDialog;
    }
}

$platforms = ['windows', 'linux'];
$currentPlatform = $platforms[array_rand($platforms)];

// Выбираем нужную фабрику исходя из платформы
if ($currentPlatform === 'windows') {
    $guiFactory = new WindowsGuiFactory;
} elseif ($currentPlatform === 'linux') {
    $guiFactory = new LinuxGuiFactory;
}

// Получаем определённую фабрику с общим интерфейсом.
// Исходя из принципа зависимости от абстракции (в нашем случае интерфейса)
// Программе далее должно быть всё равно, фабрика эта WindowsGuiFactory или LinuxGuiFactory
// (Т.к. везде тайпхинтим через интерфейс)
$button = $guiFactory->makeButton();
$dialog = $guiFactory->makeDialog();

var_dump($button);
var_dump($dialog);