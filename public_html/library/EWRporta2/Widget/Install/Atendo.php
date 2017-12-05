<?php

class EWRporta2_Widget_Install_Atendo
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['EWRatendo'])) ? $addons['EWRatendo'] : 0;
			
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install Atendo widget; missing prerequisite Addon.', true);
			}
		}
	}
}