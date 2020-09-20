<?php
namespace Jgauthi\Component\Bootstrap\Html;

class Notification
{
    static public function html(string $type, string $message): string
    {
        return '<div class="alert alert-'. $type .'" role="alert">'.$message.'</div>';
    }

    static public function success(string $message): string
    {
        return self::html('success', $message);
    }

    static public function warning(string $message): string
    {
        return self::html('warning', $message);
    }

    static public function error(string $message): string
    {
        return self::html('danger', $message);
    }

    static public function info(string $message): string
    {
        return self::html('info', $message);
    }

    static public function message(string $message): string
    {
        return self::html('primary', $message);
    }

    static public function message2(string $message): string
    {
        return self::html('secondary', $message);
    }
}