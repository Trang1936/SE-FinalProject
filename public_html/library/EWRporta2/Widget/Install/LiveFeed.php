<?php

class EWRporta2_Widget_Install_LiveFeed
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['LiveFeed'])) ? $addons['LiveFeed'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install LiveFeed widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 27)
			{
				throw new XenForo_Exception('Unable to install LiveFeed widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}