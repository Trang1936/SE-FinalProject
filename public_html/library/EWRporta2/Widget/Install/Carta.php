<?php

class EWRporta2_Widget_Install_Carta
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['EWRcarta'])) ? $addons['EWRcarta'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install Carta widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 40)
			{
				throw new XenForo_Exception('Unable to install Carta widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}