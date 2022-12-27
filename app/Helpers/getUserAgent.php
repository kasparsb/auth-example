<?php
namespace App\Helpers;

function getUserAgent() {
    return empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
}