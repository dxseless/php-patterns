<?php
/**
 * Фасад - структурный паттерн проектирования, который
 * предосталвяет единый упрощённый интерфейс к более сложным системным средствам.
 * Фасад упрощает работу большинства программистов, не скрывая низкоуровневую функциональность
 * тем немногим, кому она нужна.
 */

/**
 * Класс фасада.
 * Знает, к каким классам подсистемы адресовать запрос.
 * Делегирует запросы клиентов подходящим объектам внутри подсистемы
 */
class ItAgency
{
    private Developer $developer;
    private Designer $designer;

    public function __construct(Developer $developer, Designer $designer)
    {
        $this->developer = $developer;
        $this->designer = $designer;
    }

    public function startProject()
    {
        $this->developer->startDevelop();
        $this->designer->startDesign();
    }

    public function completeProject()
    {
        $this->developer->stopDevelop();
        $this->designer->stopDevelop();
    }
}

/**
 * Классы подсистемы, ничего не знает о существовании фасада,
 * то есть, не хранят ссылок на него.
 */
class Developer
{
    public function startDevelop()
    {
        echo 'Developer start develop' . PHP_EOL;
    }

    public function stopDevelop()
    {
        echo 'Developer stop develop' . PHP_EOL;
    }
}

class Designer
{
    public function startDesign()
    {
        echo 'Designer start design' . PHP_EOL;
    }

    public function stopDevelop()
    {
        echo 'Designer stop design' . PHP_EOL;
    }
}

$developer = new Developer;
$designer = new Designer;

$itAgency = new ItAgency($developer, $designer);

$itAgency->startProject();
$itAgency->completeProject();