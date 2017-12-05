<?php

class EWRporta2_Widget_Install_Medio
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['EWRmedio'])) ? $addons['EWRmedio'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install Medio widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 60)
			{
				throw new XenForo_Exception('Unable to install Medio widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}