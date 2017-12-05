<?php

class xShop_Listener_LoadClassController
{
    /**
     * Instruct the system that XenForo_ControllerPublic_X
     * should be extended by xShop_ControllerPublic_X
     *
     * @param string $class
     * @param array $extend
     */
    public static function loadClassController($class, array &$extend)
    {
        if ($class == 'XenForo_ControllerPublic_Account')
        {
            $extend[] = 'xShop_ControllerPublic_Account';
        }
        else if ($class == 'XenForo_ControllerPublic_Member')
        {
            $extend[] = 'xShop_ControllerPublic_Member';
        }
    }
}