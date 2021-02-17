<?php

/**
 * Функция для подключения к БД
 */
function connect()
{
    static $connection = null;

    if ($connection === null) {
        $connection = mysqli_connect('mysqldb', 'test_samson', 'test_samson', 'test_samson') or die('connection Error');
        mysqli_set_charset($connection, 'utf8');
    }

    return $connection;
}

/**
 * Функция для получения id товара
 * @param int $code товара
 */
function getProductId(int $code)
{
    $result = mysqli_query(connect(), "SELECT id FROM products WHERE code = '$code'");

    return mysqli_fetch_assoc($result)['id'];
}

/**
 * Функция для получения id категории
 * @param string $name категории
 */
function getCategoryId(string $name)
{
    $result = mysqli_query(connect(), "SELECT id FROM categories WHERE name = '$name'");

    return mysqli_fetch_assoc($result)['id'];
}

/**
 * Функция для проверки налаичия строк в талице products
 * @param int $code
 */
function searchProduct(int $code)
{
    $result = mysqli_query(connect(), "SELECT 1 FROM products WHERE code = '$code' LIMIT 1");
    return mysqli_fetch_assoc($result);
}

/**
 * Функция для проверки налаичия строк в талице prices
 * @param int $id - product_id
 * @param string $type
 */
function searchPrice(int $id, string $type)
{
    // проверить по типу
    $result = mysqli_query(connect(), "SELECT 1 FROM prices WHERE product_id = '$id' AND type = '$type' LIMIT 1");

    return mysqli_fetch_assoc($result);
}

/**
 * Функция для проверки налаичия строк в талице prices
 * @param int $id - product_id
 * @param string $property 
 */
function searchProperty(int $id, string $property, string $value)
{
    $result = mysqli_query(connect(), "SELECT 1 FROM properties WHERE product_id = '$id' AND property = '$property' AND value = '$value' LIMIT 1");

    return mysqli_fetch_assoc($result);
}

/**
 * Функция для проверки наличия строки в таблицу categories
 * @param string $name 
 */
function searchCategory(string $name)
{
    $result = mysqli_query(connect(), "SELECT 1 FROM categories WHERE name = '$name' LIMIT 1");

    return mysqli_fetch_assoc($result);
}

/**
 * Функция для проверки наличия строки в таблице category_product
 * @param int $categoryId
 * @param int $productId
 */
function searchProductInCategory(int $categoryId, int $productId)
{
    $result = mysqli_query(connect(), "SELECT 1 FROM category_product WHERE category_id = '$categoryId' AND product_id = '$productId' LIMIT 1");

    return mysqli_fetch_assoc($result);
}

/**
 * Функция для добавления данных в таблицу products
 * @param int $code
 * @param string $name
 */
function addProduct(int $code, string $name)
{
    mysqli_query(connect(), "INSERT products SET code = '$code', name = '$name'");
}

/**
 * Функция доя добавления данных в таблицу prices
 * @param string $type
 * @param float $price
 * @param int $id
 */
function addPrice(string $type, float $price, int $id)
{
    mysqli_query(connect(), "INSERT prices SET type = '$type', price = '$price', product_id = '$id'");
}

/**
 * Функция для добавления данных в таблицу properties
 * @param int $id 
 * @param string $property
 * @param string $value
 */
function addProperty(int $id, string $property, string $value)
{
    mysqli_query(connect(), "INSERT properties SET property = '$property', value = '$value', product_id = '$id'");
}

/**
 * Функция дла добавления данных в таблицу categories
 * @param string $name
 */
function addCategory(string $name)
{
    mysqli_query(connect(), "INSERT categories SET name = '$name'");
}

/**
 * Функция для добавления данных в таблицу category_product
 * @param int $categoryId
 * @param int $productId
 */
function addProductInCategory(int $categoryId, int $productId)
{
    mysqli_query(connect(), "INSERT category_product SET category_id = '$categoryId', product_id = '$productId'");
}