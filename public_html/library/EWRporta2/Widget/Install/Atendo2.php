<?php

class EWRporta2_Widget_Install_Atendo2
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['EWRatendo2'])) ? $addons['EWRatendo2'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install Atendo2 widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 4)
			{
				throw new XenForo_Exception('Unable to install Atendo2 widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}