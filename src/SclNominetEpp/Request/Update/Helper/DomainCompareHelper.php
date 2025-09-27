<?php

namespace SclNominetEpp\Request\Update\Helper;

class DomainCompareHelper
{
    public static function compare(object $a, object $b): int
    {
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
}
