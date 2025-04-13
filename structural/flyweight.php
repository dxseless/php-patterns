
<?php

/**
 * Легковес (Flyweight) - структурный паттерн проектирования
 * 
 * Применяет совместное использование для эффективной поддержки множества мелких объектов.
 * 
 * Состояние, необходимое легковесу для нормальной работы, классифицируется
 * на внутреннее и внешнее. Внутреннее состояние хранится в самом объекте
 * ConcreteFlyweight. Внешнее состояние хранится или вычисляется клиентами.
 * Клиент передаёт его (внешнее состояние) легковесу при вызове операций.
 * 
 * Клиенты не должны создавать экземпляры класса ConcreteFlyweight напрямую,
 * а могут получать их только от объекта FlyweightFactory. Это позволит гарантировать
 * корректное совместное использование.
 * 
 * Так как объекты легковесов будут использованы в разных контекстах, 
 * вы должны быть уверены в том, что их состояние невозможно изменить после создания. 
 * Всё внутреннее состояние легковес должен получать через параметры конструктора. 
 * Он не должен иметь сеттеров и публичных полей.
 * 
 * Легковес применяется в программе, имеющей громадное количество одинаковых объектов. 
 * Этих объектов должно быть так много, чтобы 
 * они не помещались в доступную оперативную память без ухищрений. 
 */

/**
 * От этого класса пойдут несколько общих объектов, хранящих
 * самые тяжёлые данные.
 * В объектах этого класса будет лежать повторяющаяся часть состояния объекта.
 */
class TreeType
{
    private string $name;
    private string $color;
    private string $texture;

    public function __construct(string $name, string $color, string $texture)
    {
        $this->name = $name;
        $this->color = $color;
        $this->texture = $texture;
    }

    public function draw($canvas, $x, $y)
    {
        echo "[TREE]: Рисую на {$canvas} по координатам: x = {$x}; y = {$y}" . PHP_EOL;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getTexture(): string
    {
        return $this->texture;
    }
}

/**
 * Объекты класса Tree содержат в себе ссылку на объект класса TreeType.
 */
class Tree
{
    private TreeType $treeType;

    private float $x;
    private float $y;

    public function __construct(float $x, float $y, TreeType $treeType)
    {
        $this->x = $x;
        $this->y = $y;
        $this->treeType = $treeType;
    }

    public function draw(string $canvas): void
    {
        $this->treeType->draw($canvas, $this->x, $this->y);
    }
}

/**
 * Клиент работает с деревьями через фабрику деревьев,
 * которая скрывает от него сложность кеширования общих данных деревьев.
 */
class TreeFactory
{
    /**
     * GOF говорит, что часто здесь встречается ассоциативное хранилище.
     * 
     * @var TreeType[] $treeTypes
     */
    private static array $treeTypes = [];

    public static function getTreeType(string $name, string $color, string $texture): TreeType
    {
        $existTreeType = self::find($name, $color, $texture);

        if ($existTreeType) {
            return $existTreeType;
        }

        $newTreeType = new TreeType($name, $color, $texture);
        self::add($newTreeType);

        return $newTreeType;
    }

    private static function find(string $name, string $color, string $texture): ?TreeType
    {
        $mustFindKey = self::makeKey($name, $color, $texture);

        foreach (self::$treeTypes as $treeType) {
            $currentKey = self::makeKey(
                $treeType->getName(),
                $treeType->getColor(),
                $treeType->getTexture()
            );

            if ($mustFindKey === $currentKey) {
                return $treeType;
            }
        }

        return null;
    }

    private static function add(TreeType $treeType): void
    {
        $currentKey = self::makeKey(
            $treeType->getName(), 
            $treeType->getColor(), 
            $treeType->getTexture()
        );
        self::$treeTypes[$currentKey] = $treeType;
    }

    private static function makeKey(string $name, string $color, string $texture): string
    {
        $key = "{$name}{$color}{$texture}";
        return $key;
    }
}

/**
 * По сути, обычный клиентский код.
 * Здесь представлен в виде леса, в котором рисуются деревья
 */
class Forest
{
    /**
     * @var Tree[] $trees
     */
    private array $trees = [];

    public function plantTree(float $x, float $y, string $name, string $color, string $texture)
    {
        $treeType = TreeFactory::getTreeType($name, $color, $texture);
        $tree = new Tree($x, $y, $treeType);

        array_push($this->trees, $tree);
    }

    public function draw(string $canvas)
    {
        foreach ($this->trees as $tree) {
            $tree->draw($canvas);
        }
    }

    /**
     * @return Tree[]
     */
    public function getTrees(): array
    {
        return $this->trees;
    }
}

$forest = new Forest();
$trees = [
    [
        'x' => 10,
        'y' => 20,
        'name' => 'Дуб',
        'color' => 'Коричневый',
        'texture' => 'oak.jpg'
    ],
    [
        'x' => 3,
        'y' => 25,
        'name' => 'Дуб',
        'color' => 'Коричневый',
        'texture' => 'oak.jpg'
    ],
    [
        'x' => 7,
        'y' => 50,
        'name' => 'Дуб',
        'color' => 'Коричневый',
        'texture' => 'oak.jpg'
    ],
    [
        'x' => 1,
        'y' => 30,
        'name' => 'Дуб',
        'color' => 'Коричневый',
        'texture' => 'oak.jpg'
    ],
    [
        'x' => 2,
        'y' => 37,
        'name' => 'Дуб',
        'color' => 'Коричневый',
        'texture' => 'oak.jpg'
    ],
];

foreach ($trees as $tree) {
    $forest->plantTree($tree['x'], $tree['y'], $tree['name'], $tree['color'], $tree['texture']);
}

/**
 * Увидим, что все созданные дубы ссылаются на один TreeType, в котором хранятся самые
 * тяжёлые свойства, что позволяет экономить оперативную память.
 */
var_dump($forest->getTrees());

echo PHP_EOL . '===' . PHP_EOL;

$forest->draw('Monitor');
