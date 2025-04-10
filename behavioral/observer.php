<?php

/**
 * Наблюдатель - поведенческий паттерн проектирования, который позволяет одним объектам
 * следить за изменениями в других объектах.
 * 
 * (наблюдатель/подписчик) в контексте описания в комментариях используются взаимозаменяемо
 * т.к. данный паттерн так же известен как Publish-Subscribe (издатель-подписчик)
 * 
 * Паттерн Наблюдатель позволяет любому объекту с интерфейсом подписчика зарегистрироваться на
 * получение оповещений о событиях, происходящих в объектах-издателях.
 * 
 * Наблюдатель передаёт уведомление всем заинтересованным подписчикам.
 */

/**
 * Интерфейс подписчика.
 */
interface SubscriberInterface
{
    public function update(array $context): void;
}

class LoggerSubscriber implements SubscriberInterface
{
    public function update(array $context): void
    {
        $message = $context['exception']->getMessage();
        echo "[LOGGER]: $message" . PHP_EOL;
    }
}

class RemoteNotifySubscriber implements SubscriberInterface
{
    public function update(array $context): void
    {
        $message = $context['exception']->getMessage();
        echo "[REMOTE NOTIFY]: $message" . PHP_EOL;
    }
}

/**
 * Интерфейс Publisher'а.
 * Стоит отметить, что подписчик может подписываться на несколько Publisher'ов.
 */
interface PublisherInterface
{
    public function subscribe(SubscriberInterface $subscriber): void;
    public function unsubscribe(SubscriberInterface $subscriber): void;
    public function notify(): void;
}

/**
 * Publisher. Располагает информацией о своих подписчиках.
 * За субъектом может следить любое число подписчиков.
 */
class ErrorBoundary implements PublisherInterface
{
    private array $subscribers = [];

    public function subscribe(SubscriberInterface $subscriber): void
    {
        array_push($this->subscribers, $subscriber);
    }

    public function unsubscribe(SubscriberInterface $subscriber): void
    {
        $index = array_search($subscriber, $this->subscribers);
        array_splice($this->subscribers, $index, 1);
    }

    public function notify(): void
    {
        foreach ($this->subscribers as $subscriber) {
            $context = [
                'exception' => new \Exception("Error with id: " . uniqid())
            ];

            $subscriber->update($context);
        }
    }
}

$errorBoundary = new ErrorBoundary;

$logger = new LoggerSubscriber;
$remoteNotifier = new RemoteNotifySubscriber;

$errorBoundary->subscribe($logger);
$errorBoundary->subscribe($remoteNotifier);

$errorBoundary->notify();