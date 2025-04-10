<?php

/**
 * Стратегия - поведенческий паттерн проектирования, который определяет семейство объектов,
 * инкапсулирует их и делает их взаимозаменяемыми.
 * 
 * Вместо того, чтобы изначальный класс сам выполнял тот или иной алгоритм,
 * он будет играть роль контекста, ссылаясь на одну из стратегий
 * и делегируя ей выполнение работы.
 * 
 * Важно, чтобы все стратегии имели общий интерфейс. Используя этот интерфейс,
 * контекст будет независимым от конкретных классов стратегий. 
 */

/**
 * Семейство алгоритмов стратегий определяет семейство алгоритмов
 * или вариантов поведения, которые можно повторно использовать в разных контекстах.
 */
interface StrategyInterface
{
    public function execute(): void;
}

class JsonRpcStrategy implements StrategyInterface
{
    public function execute(): void
    {
        echo "[JSON_RPC] Calling" . PHP_EOL;
    }
}

class XmlRpcStrategy implements StrategyInterface
{
    public function execute(): void
    {
        echo "[XML_RPC] Calling" . PHP_EOL;
    }
}

/**
 * Контекст переадресует запросы клиентского кода объекту стратегии
 */
class Context
{
    private StrategyInterface $strategy;

    public function setStrategy(StrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function executeStrategy()
    {
        return $this->strategy->execute();
    }
}

/**
 * Выбор стратегии может зависеть от разных факторов.
 * В данном случае - определяется случайно
 */
$strategies = [
    fn() => new JsonRpcStrategy,
    fn() => new XmlRpcStrategy,
];

$strategy = $strategies[array_rand($strategies)]();

$context = new Context;

$context->setStrategy($strategy);
$context->executeStrategy();