<?php

class EWRporta2_Widget_Install_Resource
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['XenResource'])) ? $addons['XenResource'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install Resource widget; missing prerequisite Addon.', true);
			}
		}
	}
}