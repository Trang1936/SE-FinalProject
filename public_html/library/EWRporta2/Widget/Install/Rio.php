<?php

class EWRporta2_Widget_Install_Rio
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['EWRrio'])) ? $addons['EWRrio'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install Rio widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 5)
			{
				throw new XenForo_Exception('Unable to install Rio widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}