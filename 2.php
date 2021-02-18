<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/samson/dbConnect.php');

/**
 * Функция для поиска второй подстроки и инверсии её
 * @param string $a
 * @param string $b подстрока которую нужно найти в $a
 * @return string инверсия второй подстроки
 */
function convertString(string $a, string $b): string
{
    $arr = explode(' ', $a);
    $stringLowerCase = mb_strtolower($b);
    $counter = 0;

    foreach ($arr as $key => $word) {
        $wordLowerCase = mb_strtolower($word);
        $wordTrim = trim($wordLowerCase, "\x20..\x2F\x3A..\x3F");

        if ($stringLowerCase === $wordTrim) $counter++;
        if ($counter == 2) {
            $wordArray = preg_split('/(?<!^)(?!$)/u', $word);
            $wordArrayRev = array_reverse($wordArray);
            $arr[$key] = implode($wordArrayRev);
            break;
        }
    }

    return implode(' ', $arr);
}

// var_dump(convertString('меня зовут Меня Артём. У Меня есть кошка, а также у меня есть Пёс', 'меня'));

/**
 * Функция сортирует массив по возрастанию значния $b
 * @param array $a входной массив
 * @param string $b ключ
 * @return array 
 */
function mySortForKey(array $a, string $b): array
{
    foreach ($a as $key => $arr) {
        if (!$arr[$b]) {
            throw new Exception('Нет ключа "' .  $b . '" в массиве с индексом - ' . $key);
        }
    }

    usort($a, function ($c, $d) use ($b) {
        return $c[$b] <=> $d[$b];
    });

    return $a;
}

try {
    // var_dump(mySortForKey([['a' => 2, 'b' => 1], ['d' => 1, 'b' => 3], ['d' => 9, 'b' => 3], ['a' => 4, 'a' => 6], ['a' => 7, 'b' => 2], ['a' => 0, 'b' => 1]], 'b'));
} catch (Exception $e) {
    echo $e->getMessage();
}

// Реализовать функцию importXml($a). $a – путь к xml файлу (структура файла приведена ниже). Результат ее выполнения: прочитать файл $a и импортировать его в созданную БД.

/**
 * Функция для чтения xml файла и отправки данных в БД
 * @param string $a путь к файлу
 */
function importXml(string $a)
{
    if (file_exists($a)) {
        $str = file_get_contents($a);
        $xmlStr = iconv('UTF-8', 'windows-1251', $str);
        $xml = new SimpleXMLElement($xmlStr);

        foreach ($xml->Товар as $product) {
            if (!searchProduct((int)$product['Код'])) {
                addProduct((int)$product['Код'], (string)$product['Название']);
            }

            $productId = getProductId((int)$product['Код']);

            foreach ($product->Цена as $price) {
                if (!searchPrice($productId, (string)$price['Тип'])) {
                    addPrice((string)$price['Тип'], (float)$price, $productId);
                }
            }

            foreach ((array)$product->Свойства as $property => $value) {
                $attr = $product->Свойства->$property->attributes();

                if ($attr['ЕдИзм']) $value = $value . (string)$attr;

                $values = (array)$value;

                foreach ($values as $value) {
                    if (!searchProperty($productId, $property, $value)) {
                        addProperty($productId, $property, $value);
                    }
                }
            }

            foreach ((array)$product->Разделы as $categories) {
                foreach ((array)$categories as $name) {
                    if (!searchCategory($name)) {
                        addCategory($name);
                    }

                    $categoryId = getCategoryId($name);

                    if (!searchProductInCategory($categoryId, $productId)) {
                        addProductInCategory($categoryId, $productId);
                    }
                }
            }
        }
    } else {
        throw new Exception("Файл - $a не найден.");
    }
}
try {
    importXml('prices.xml');
} catch (Exception $e) {
    echo $e->getMessage();
}