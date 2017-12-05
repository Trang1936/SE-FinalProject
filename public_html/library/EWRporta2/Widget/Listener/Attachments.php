<?php

class EWRporta2_Widget_Listener_Attachments
{
    public static function widgets($class, array &$extend)
    {
		// EWRporta2_ControllerPublic_Widgets
		$extend[] = 'EWRporta2_Widget_Controller_Attachments';
    }
}