<?php
namespace app\common\controller;

class Common
{
    private function __construct() {}

    public static function mergeSort($arr, $filter = null) {
        $len = count($arr);
        if ($len <= 1) {
            return $arr;
        }
        $half = ($len >> 1) + ($len & 1);
        $arr2d = array_chunk($arr, $half);
        $left = self::mergeSort($arr2d[0]);
        $right = self::mergeSort($arr2d[1]);
        while (count($left) && count($right)) {
            if ($filter) {
                $val = $filter($left[0], $right[0]);
            } else {
                $val = $left[0]["price"] < $right[0]["price"];
            }
            if ($val) {
                $reg[] = array_shift($left);
            } else {
                $reg[] = array_shift($right);
            }
        }
        return array_merge($reg, $left, $right);
    }
}