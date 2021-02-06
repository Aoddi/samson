<?php

// 1. Реализовать функцию findSimple ($a, $b). $a и $b – целые положительные числа. Результат ее выполнение: массив простых чисел от $a до $b.

/**
 * Функция для поиска простых чисел от $a до $b
 * @param int $a
 * @param int $b
 * @return массив простых чисел 
 */
function findSimple(int $a, int $b)
{
    $primes = [2, 3, 5];

    for ($i = 7; $i <= $b; $i++) {
        $iSqrt = sqrt($i);

        for ($j = 0; $primes[$j] <= $iSqrt; $j++) {
            if ($i % $primes[$j] == 0) continue 2;
        }
        
        $primes[] = $i;
    }

    if ($a < 2) {
        return $primes;
    } else {
        foreach($primes as $key => $num) {
            if ($num >= $a) return array_splice($primes, $key);
        }
    }
}

// 2. Реализовать функцию createTrapeze($a). $a – массив положительных чисел, количество элементов кратно 3. Результат ее выполнение: двумерный массив (массив состоящий из ассоциативных массива с ключами a, b, c). Пример для входных массива [1, 2, 3, 4, 5, 6] результат [[‘a’=>1,’b’=>2,’с’=>3],[‘a’=>4,’b’=>5 ,’c’=>6]]

/**
 * Функция для создания нового двумерного массива с данными трапеции
 * @param array $a массив положительный чисел, количество элементов кратно 3
 * @return array $d двумерный массив
 */
function createTrapeze(array $a)
{
    if (count($a) % 3 !== 0) {
        echo 'Количество элементов в массиве не кратно 3';
    } else {
        $b = ['a', 'b', 'c'];

        while (!empty($a)) {
            $c = array_splice($a, 0, 3);
            $d[] = array_combine($b, $c);
        }

        return $d;
    }
}

// 3. Реализовать функцию squareTrapeze($a). $a – массив результата выполнения функции createTrapeze(). Результат ее выполнение: в исходный массив для каждой тройки чисел добавляется дополнительный ключ s, содержащий результат расчета площади трапеции со сторонами a и b, и высотой c.

/**
 * Функция для подсчета площади трапеции
 * @param array $a массив результата выполнения функции createTrapeze()
 * @return array $a исходный массив в который добавили ключ s (площадь трапеции)
 */
function squareTrapeze(array $a)
{
    foreach ($a as $key => $arr) {
        $s = ($arr['a'] + $arr['b']) / 2 * $arr['c'];
        $a[$key]['s'] = $s;
    }

    return $a;
}

// 4. Реализовать функцию getSizeForLimit($a, $b). $a – массив результата выполнения функции squareTrapeze(), $b – максимальная площадь. Результат ее выполнение: массив размеров трапеции с максимальной площадью, но меньше или равной $b.


/**
 * Функция для сортировки массива по максимальной площади трапеции
 * @param array $a массив результата выполнения функции squareTrapeze()
 * @param int $b - максимальная площадь
 * @return array массив размеров трапеции с максимальной площадью, но меньше или равной $b
 */
function getSizeForLimit(array $a, int $b = 32)
{
    foreach ($a as $arr) {
        if ($arr['s'] <= $b) $c[] = $arr;
    }

    return $c;
}

// 5. Реализовать функцию getMin($a). $a – массив чисел. Результат ее выполнения: минимальное числа в массиве (не используя функцию min, ключи массив может быть ассоциативный).

/**
 * Функция для поиска минимального числа в массиве
 * @param array $a массив с числами
 * @return int $minNum минимальное число в массиве
 */
function getMin($a)
{
    $keys = array_keys($a);
    $firstKey = $keys[0];

    $minNum = $a[$firstKey];

    foreach ($a as $num) {
        if ($minNum > $num) $minNum = $num;
    }

    return $minNum;
}

// 6. Реализовать функцию printTrapeze($a). $a – массив результата выполнения функции squareTrapeze(). Результат ее выполнение: вывод таблицы с размерами трапеций, строки с нечетной площадью трапеции отметить любым способом.

/**
 * Функция для вывода таблицы с размерами трапеций
 * @param array $a массив результата выполнения функции squareTrapeze()
 * @return string HTML с таблицей
 */
function printTrapeze(array $a)
{
    $tr = "";

    foreach ($a as $array) {
        $tr .= "<tr>";
        if (is_int($array['s']) && $array['s'] % 2 !== 0) {
            $tr .= "
                <td><strong>{$array['a']}</strong></td>
                <td><strong>{$array['b']}</strong></td>
                <td><strong>{$array['c']}</strong></td>
                <td><strong>{$array['s']}</strong></td>
            ";
        } else {
            $tr .= "
                <td>{$array['a']}</td>
                <td>{$array['b']}</td>
                <td>{$array['c']}</td>
                <td>{$array['s']}</td>
            ";
        }
        $tr .= "</tr>";
    }

    $table = "
        <table>
        <caption>Таблица размеров трапеции</caption>
        <tr>
            <th>Длина 1-ого основания</th>
            <th>Длина 2-ого основания</th>
            <th>Длина высоты</th>
            <th>Площадь</th>
        </tr>
        $tr
        </table>
    ";

    return $table;
}

// 7. Реализовать абстрактный класс BaseMath содержащий 3 метода: exp1($a, $b, $c) и exp2($a, $b, $c),getValue(). Метода exp1 реализует расчет по формуле a*(b^c). Метода exp2 реализует расчет по формуле (a/b)^c. Метод getValue() возвращает результат расчета класса наследника.

abstract class BaseMath
{
    /**
     * Метод для расчет по формуле a*(b^c)
     * @param int $a
     * @param int $b
     * @param int $c 
     * @return int
     */
    public function exp1(int $a, int $b, int $c)
    {
        return $a * pow($b, $c);
    }

    /**
     * Метод для расчет по формуле a*(b^c)
     * @param int $a
     * @param int $b
     * @param int $c 
     * @return int
     */
    public function exp2(int $a, int $b, int $c)
    {
        return pow(($a / $b), $c);
    }

    abstract public function getValue();
}

// 8. Реализовать класс F1 наследующий методы BaseMath, содержащий конструктор с параметрами ($a, $b, $c) и метод getValue(). Класс реализует расчет по формуле f=(a*(b^c)+(((a/c)^b)%3)^min(a,b,c)).

class F1 extends BaseMath
{
    /**
     * Конструктор
     * @param int $a
     * @param int $b
     * @param int $c
     */
    public function __construct(int $a, int $b, int $c)
    {
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
    }

    /**
     * Метод для расчета по формуле f=(a*(b^c)+(((a/c)^b)%3)^min(a,b,c))
     * @return int 
     */
    public function getValue()
    {
        return $this->exp1($this->a, $this->b, $this->c) + pow(($this->exp2($this->a, $this->b, $this->c) % 3), min($this->a, $this->b, $this->c));
    }
}

$f1 = new F1(5, 2, 3);
$f1->getValue();

findSimple(55, 100);
$array = [1, 2, 3, 4, 5, 6, 7, 8, 9];
shuffle($array);
$array1 = createTrapeze($array);
$array2 = squareTrapeze($array1);
$array3 = getSizeForLimit($array2, 34);
echo printTrapeze($array3);

getMin($array);
getMin([
    'a' => 3,
    'v' => 4,
    'c' => 9,
    'g' => 11,
    '1' => 1337,
    't' => 2,
]);