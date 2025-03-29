<?php
/**
 * Декоратор - структурный паттерн проектирования, который
 * динамически добавляет объекту новые обязанности.
 * Является гибкой альтернативой порождению подклассов с целью расширения функциональности.
 * 
 * Декоратор добавляет новые возможности к существующему коду, при этом
 * никак этот существующий код не меняет.
 */

/**
 * Декоратор следует интерфейсу декорируемого объекта, поэтому
 * его присутствие прозрачно для клиентов компонента
 */
interface CodeExecutorInterface
{
    public function run(string $code): void;
}

class CodeExecutor implements CodeExecutorInterface
{
    public function run(string $code): void
    {
        eval($code);
    }
}

/**
 * Хранит ссылку на объект компонента и следует интерфейсу этого компонента
 */
abstract class CodeExecutorAbstractDecorator implements CodeExecutorInterface
{
    private CodeExecutor $codeExecutor;

    public function __construct(CodeExecutor $codeExecutor)
    {
        $this->codeExecutor = $codeExecutor;
    }

    public function run(string $code): void
    {
        $this->codeExecutor->run($code);
    }
}

/**
 * Добавляет дополнительные возможности к существующему коду компонента,
 * при этом не изменяет существующий код компонента.
 */
class CodeExecutorTimerDecorator extends CodeExecutorAbstractDecorator
{
    public function run(string $code): void
    {
        $start = microtime(true);

        parent::run($code);

        $end = microtime(true);
        $result = $end - $start;

        echo "Code executed by " . $result . PHP_EOL;
    }
}

class CodeExecutorPrettifierDecorator extends CodeExecutorAbstractDecorator
{
    public function run(string $code): void
    {
        echo "========== START ==========" . PHP_EOL;

        try {
            parent::run($code);
            echo "========== SUCCESS ==========" . PHP_EOL;
        } catch (\Throwable $e) {
            echo "========== ERROR: {$e->getMessage()} ==========" . PHP_EOL;
        } finally {
            echo "========== FINAL ==========" . PHP_EOL;
        }
    }
}

$codeExecutor = new CodeExecutor;

$timerDecorator = new CodeExecutorTimerDecorator($codeExecutor);
$timerDecorator->run('sleep(1);2 + 2;');

$prettifierDecorator = new CodeExecutorPrettifierDecorator($codeExecutor);
$prettifierDecorator->run('2 + 2;');
// $prettifierDecorator->run("throw new Error('Some error message');");