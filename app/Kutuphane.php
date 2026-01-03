<?php

if (!function_exists('view')) {
    function view($path, $data = [])
    {
        extract($data);
        $fullPath = __DIR__ . '/../gorunumler/' . $path . '.php';
        if (file_exists($fullPath)) {
            require $fullPath;
        } else {
            echo "View bulunamadi: $path";
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($url)
    {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('url')) {
    function url($path = '')
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        return "$protocol://$host/" . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path)
    {
        return url('assets/' . ltrim($path, '/'));
    }
}

function guvenli_html($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
