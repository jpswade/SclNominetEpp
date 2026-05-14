<?php

namespace SclNominetEpp\Request\Update\Helper;

class DomainCompareHelper
{
    public static function compare(object $left, object $right): int
    {
        if ($left == $right) {
            return 0;
        }
        return ($left < $right) ? -1 : 1;
    }
}
