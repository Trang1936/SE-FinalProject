<?php

class EWRporta2_Widget_Install_TaigaChat
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['TaigaChat'])) ? $addons['TaigaChat'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install TaigaChat widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 34)
			{
				throw new XenForo_Exception('Unable to install TaigaChat widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}