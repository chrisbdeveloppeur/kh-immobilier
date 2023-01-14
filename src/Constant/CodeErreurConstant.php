<?php


namespace App\Constant;


final class CodeErreurConstant
{
    const EMAIL_NO_CONFIRMED = 10;
    const EMAIL_NOT_FOUND = 1;
    const INCORRECT_PASSWORD = 2;

    static function getConstants() {
        $oClass = new \ReflectionClass(__CLASS__);
        return $oClass->getConstants();
    }
}