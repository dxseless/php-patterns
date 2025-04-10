<?php

/**
 * Состояние - поведенческий паттерн проектирования, который позволяет
 * объектам менять поведение в зависимости от своего состояния.
 * 
 * Состояние предлагает создать отдельные классы для каждого состояния,
 * в котором может пребывать объект, а затем вынести туда поведения,
 * соответствующие этим состояниям.
 */

/**
 * Паттерн состояние позволяет трактовать состояние объекта как отдельный объект,
 * который может изменяться независимо от других.
 * 
 * При инкапсуляции каждого перехода и действия в класс состояние становится полноценным объектом.
 * Это улучшает структуру кода и проясняет его назначение.
 */
interface StateInterface
{
    public function renderUi(): void;
    public function initSubsystem(): void;
}

class MaintenanceState implements StateInterface
{
    public function renderUi(): void
    {
        echo "[renderUi]: ×××Состояние обслуживания×××" . PHP_EOL;
    }

    public function initSubsystem(): void
    {
        echo "[initSubsystem]: ×××Состояние обслуживания×××" . PHP_EOL;        
    }
}

class ActiveState implements StateInterface
{
    public function renderUi(): void
    {
        echo "[renderUi]: ...Рисуем интерфейс..." . PHP_EOL;
    }

    public function initSubsystem(): void
    {
        echo "[initSubsystem]: ...Инициализируем подсистему..." . PHP_EOL;        
    }
}

/**
 * Контекст делегирует запросы к состоянию StateInterface
 */
class Context
{
    private StateInterface $state;

    public function setState(StateInterface $state)
    {
        $this->state = $state;
    }

    public function boot()
    {
        $this->state->initSubsystem();
        $this->state->renderUi();
    }
}

$states = [
    fn() => new MaintenanceState,
    fn() => new ActiveState,
];

$currentState = $states[array_rand($states)]();

$context = new Context;
$context->setState($currentState);
$context->boot();