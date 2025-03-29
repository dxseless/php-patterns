<?php

/**
 * Адаптер - структурный паттерн проектирования, который преобразует интерфейс одного класса
 * в другой интерфейс, на который рассчитаны клиенты.
 * Адаптер обеспечивает совместную работу классов с несовместимыми интерфейсами,
 * которая без него была бы невозможна
 */

/**
 * Сервис с несовместимым интерфейсом.
 * Берёт данные из строки формата cat1=2&cat2=4
 */
class PifagorServiceUrl
{
    public function findGipotenuze(string $stats): int|float
    {
        parse_str($stats, $data);

        $gipotenuze = round(sqrt(pow($data['cat1'], 2) + pow($data['cat2'], 2)), 2);

        return $gipotenuze;
    }
}

/**
 * Определяет зависящий от предметной области интерфейс, именно его
 * мы используем в клиентском коде
 */
interface PifagorServiceArrayInterface
{
    public function getGipotenuze(array $stats): int|float;
}

/**
 * Адаптирует сервис, использующий GET-параметры к нужному интерфейсу,
 * нужный интерфейс работает данными, передающимися через массив
 */
class PifagorServiceUrlAdapterToArray implements PifagorServiceArrayInterface
{
    private PifagorServiceUrl $pifagorServiceUrl;

    public function __construct(PifagorServiceUrl $pifagorServiceUrl)
    {
        $this->pifagorServiceUrl = $pifagorServiceUrl;
    }

    public function getGipotenuze(array $stats): int|float
    {
        $statsStr = "cat1={$stats['cat1']}&cat2={$stats['cat2']}";

        $result = $this->pifagorServiceUrl->findGipotenuze($statsStr);

        return $result;
    }
}

$pifagorServiceUrl = new PifagorServiceUrl;
$pifagorServiceUrlAdapterToArray = new PifagorServiceUrlAdapterToArray($pifagorServiceUrl);

$gipotenuze = $pifagorServiceUrlAdapterToArray->getGipotenuze(['cat1' => 2, 'cat2' => 4]);
echo $gipotenuze . PHP_EOL;