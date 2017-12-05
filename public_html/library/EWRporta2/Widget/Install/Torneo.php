<?php

class EWRporta2_Widget_Install_Torneo
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['EWRtorneo'])) ? $addons['EWRtorneo'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install Torneo widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 5)
			{
				throw new XenForo_Exception('Unable to install Torneo widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}