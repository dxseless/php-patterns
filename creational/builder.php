<?php

/**
 * Builder - порождающий паттерн проектирования,
 * предлагающий вынести конструирование объекта
 * за пределы его собственного класса, поручив это дело отдельным объектам,
 * которые следует называть "builder".
 * 
 * Примеры из фреймворков: QueryBuilder в Laravel.
*/


/**
 * Классы конструируемых объектов.
 * В реальном коде тут будут нужные свойства и методы
 */
class Ubuntu
{
    //
}

class Windows
{
    //
}

/**
 * Интерфейс строителя объявляет все возможные этапы и шаги
 * конфигурации продукта.
 * Каждый строитель должен реализовывать этот интерфейс, т.к. используем именно
 * его (интерфейс) в определениях типов в различных методах (например, в классе Director)
 */
interface OsBuilderInterface
{
    public function reset(): self;
    public function getResult(): object;
    public function setUi(string $ui): self;
    public function setSystem(array $system): self;
}

class UbuntuBuilder implements OsBuilderInterface
{
    private Ubuntu $ubuntu;

    public function reset(): self
    {
        $this->ubuntu = new Ubuntu;
        return $this;
    }

    public function getResult(): object
    {
        return $this->ubuntu;
    }

    public function setUi(string $ui): self
    {
        echo 'Set ui to ubuntu' . PHP_EOL;
        return $this;
    }

    public function setSystem(array $system): self
    {
        echo 'Set system to ubuntu' . PHP_EOL;
        return $this;
    }
}

class WindowsBuilder implements OsBuilderInterface
{
    private Windows $windows;

    public function reset(): self
    {
        $this->windows = new Windows;
        return $this;
    }

    public function getResult(): object
    {
        return $this->windows;
    }

    public function setUi(string $ui): self
    {
        echo 'Set ui to windows' . PHP_EOL;
        return $this;
    }

    public function setSystem(array $system): self
    {
        echo 'Set system to windows' . PHP_EOL;
        return $this;
    }
}

class Director
{
    public function constructModernOs(OsBuilderInterface $builder)
    {
        $builder->reset()
            ->setUi('Very pretty UI')
            ->setSystem(['type' => 'modern task management system'])
        ;
    }
}

$builders = [
    fn() => new UbuntuBuilder,
    fn() => new WindowsBuilder,
];

$director = new Director;

$osBuilder = $builders[array_rand($builders)]();

$director->constructModernOs($osBuilder);

$modernOs = $osBuilder->getResult();

var_dump($modernOs);