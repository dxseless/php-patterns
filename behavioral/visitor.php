<?php

/**
 * Visitor - это поведенческий паттерн проектирования, который позволяет добавлять
 * в программу новые операции, не изменяя классы объектов,
 * над которыми эти операции могут выполняться.
 */

/**
 * Интерфейс посетителя.
 * Будет рисовать фигуры
 */
interface PainterInterface
{
    public function drawCircle(Circle $circle): void;
    public function drawRect(Rect $rect): void;
}

/**
 * Интерфейс конкретного элемента.
 * Помимо метода, связанного с посетителем, может (и в реальном коде будет) иметь другие.
 */
interface ShapeInterface
{
    public function accept(PainterInterface $painter);
}

class Painter implements PainterInterface
{
    public function drawCircle(Circle $circle): void
    {
        echo "Drawing: " . $circle::class . PHP_EOL;
        echo "
      ****
   **      **   
  *          *  
 *            * 
 *            * 
  *          *  
   **      **   
      ****
". PHP_EOL;
    }

    public function drawRect(Rect $rect): void
    {
        echo "Drawing: " . $rect::class . PHP_EOL;
        echo "
    **********
    *        *
    *        *
    *        *
    *        *
    *        *
    **********
" . PHP_EOL;
    }
}

class Circle implements ShapeInterface
{
    public function accept(PainterInterface $painter)
    {
        $painter->drawCircle($this);
    }
}

class Rect implements ShapeInterface
{
    public function accept(PainterInterface $painter)
    {
        $painter->drawRect($this);
    }
}

$painter = new Painter;

$circle = new Circle;
$rect = new Rect;

$circle->accept($painter);
echo "==============================================" . PHP_EOL;
$rect->accept($painter);