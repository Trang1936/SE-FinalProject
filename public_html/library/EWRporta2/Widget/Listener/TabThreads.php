<?php

class EWRporta2_Widget_Listener_TabThreads
{
    public static function widgets($class, array &$extend)
    {
		// EWRporta2_ControllerPublic_Widgets
		$extend[] = 'EWRporta2_Widget_Controller_TabThreads';
    }
}