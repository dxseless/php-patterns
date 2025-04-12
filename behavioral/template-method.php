<?php

/**
 * Шаблонный метод - поведенческий паттерн проектирования, который определяет
 * основу алгоритма и позволяет подклассам переопределить некоторые шаги алгоритма,
 * не изменяя его структуру в целом.
 * 
 * Паттерн Шаблонный метод предлагает разбить алгоритм на последовательность шагов,
 * описать эти шаги в отдельных методах и вызывать их в одном шаблонном методе друг за другом.
 */

abstract class DocumentAnalyzer
{
    /**
     * @var resource|null $fileDescriptor
     */
    private $fileDescriptor;

    public function __construct(string $filename)
    {
        if (file_exists($filename)) {
            $this->fileDescriptor = fopen($filename, 'r');
        }
    }

    /**
     * Шаблонный метод
     */
    public function analyze()
    {
        $this->baseOperation();

        $this->start();
        $this->body();
        $this->final();

        $operationId = uniqid();
        $this->onEnd($operationId);
    }

    public function baseOperation(): void
    {
        echo "[BASE_OPERATION] calling" . PHP_EOL;
    }

    /**
     * Конкретные шаги, которые для анализа документов отличаются.
     * Должны быть переопределены в подклассах.
     */
    abstract public function start(): void;
    
    abstract public function body(): void;

    abstract public function final(): void;

    /**
     * Хук, к которому могут прицепиться реализации.
     * Хуки предоставляют дополнительные точки расширения
     */
    public function onEnd(string $operationId): void
    {
        echo "[BASE] end" . PHP_EOL;
    }
}

class JsonAnalyzer extends DocumentAnalyzer
{
    public function start(): void
    {
        echo "[JSON_ANALYZER] start" . PHP_EOL;
    }

    public function body(): void
    {
        echo "[JSON_ANALYZER] body" . PHP_EOL;
    }

    public function final(): void
    {
        echo "[JSON_ANALYZER] final" . PHP_EOL;
    }

    public function onEnd(string $operationId): void
    {
        parent::onEnd($operationId);
        echo "[JSON_ANALYZER] finally done" . PHP_EOL;
    }
}

class XmlAnalyzer extends DocumentAnalyzer
{
    public function start(): void
    {
        echo "[XML_ANALYZER] start" . PHP_EOL;
    }

    public function body(): void
    {
        echo "[XML_ANALYZER] body" . PHP_EOL;
    }

    public function final(): void
    {
        echo "[XML_ANALYZER] final" . PHP_EOL;
    }

    public function onEnd(string $operationId): void
    {
        parent::onEnd($operationId);
        echo "[XML_ANALYZER] finally done" . PHP_EOL;
    }
}

$jsonAnalyzer = new JsonAnalyzer("file.json");
$jsonAnalyzer->analyze();

echo '================' . PHP_EOL;

$xmlAnalyzer = new XmlAnalyzer('data.xml');
$xmlAnalyzer->analyze();