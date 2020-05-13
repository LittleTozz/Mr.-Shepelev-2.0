<?php
// показывать сообщения об ошибках 
ini_set('display_errors', 1);
error_reporting(E_ALL); 

// URL домашней страницы 
$home_url="http://localhost/api/";

// страница указана в параметре URL, страница по умолчанию одна 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$category = isset($_GET['category']) ? $_GET['category'] : 0;

// установка количества записей на странице 
$records_per_page = 10;

// расчёт для запроса предела записей 
$from_record_num = ($records_per_page * $page) - $records_per_page;

// установить часовой пояс по умолчанию 
date_default_timezone_set('Europe/Moscow');

// переменные, используемые для JWT 
$key = "your_secret_key";
$iss = "http://any-site.org";
$aud = "http://any-site.com";
$iat = 1356999524;
$nbf = 1357000000;
?>