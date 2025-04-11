<?php

/**
 * Memento — это поведенческий паттерн проектирования,
 * который позволяет сохранять и восстанавливать прошлые состояния объектов,
 * не раскрывая подробностей их реализации.
 * 
 * Memento - это объект, в котором сохраняется внутренее состояние другого объекта - хозяина.
 * Только хозяину разрешено помещать в Memento информацию и извлекать её оттуда, для
 * других объектов Memento непрозрачен.
 */

class Memento
{
    private State $state;

    public function __construct(State $state)
    {
        $this->state = $state;
    }

    public function getState(): State
    {
        return $this->state;
    }
}

class State
{
    /**
     * Memento должен хранить только те данные,
     * которые необходимы для восстановления состояния.
     */
    private DateTime $dateTime;
    private string $text;

    public function __construct(DateTime $dateTime, string $text)
    {
        $this->dateTime = $dateTime;
        $this->text = $text;
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getText(): string
    {
        return $this->text;
    }
}

class Editor
{
    private State $state;
    private DateTime $dateTime;
    private string $text;

    public function __construct()
    {
        $this->dateTime = new DateTime;
        $this->text = '';
    }

    public function setText(string $text)
    {
        $this->text = $text;

        $this->state = new State(clone $this->dateTime, $this->text);
        $this->onTextChange();
    }

    public function onTextChange()
    {
        $this->dateTime = new DateTime;
    }

    public function saveToMemento()
    {
        return new Memento($this->state);
    }

    public function restoreFromMemento(Memento $memento)
    {
        $this->dateTime = $memento->getState()->getDateTime();
        $this->text = $memento->getState()->getText();
    }

    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    public function getText(): string
    {
        return $this->text;
    }
}

$editor = new Editor;

$editor->setText('Hello');

$mementoWithHello = $editor->saveToMemento();

$editor->setText('Hello World');

echo $editor->getText() . PHP_EOL; // Hello World

$editor->restoreFromMemento($mementoWithHello);

echo $editor->getText() . PHP_EOL; // Hello