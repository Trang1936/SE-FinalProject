<?php

class EWRporta2_Listener_BbCode
{
    public static function formatter($class, array &$extend)
    {
		// XenForo_BbCode_Formatter_Base
        $extend[] = 'EWRporta2_BbCode_Formatter';
    }
}