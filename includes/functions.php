<?php
session_start();

function __($key) {
    static $translations = null;

    if ($translations === null) {
        $lang = $_SESSION['lang'] ?? 'en';
        $path = __DIR__ . "/../lang/{$lang}.php";

        if (!file_exists($path)) {
            $path = __DIR__ . "/../lang/en.php";
        }

        $translations = include $path;
    }

    return $translations[$key] ?? $key;
}
