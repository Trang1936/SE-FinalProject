<?php

class EWRporta2_Widget_Install_SiropuChat
{
	public static function installCode($existingWidget, $widgetData)
	{
		if (!$existingWidget)
		{
			$addons = XenForo_Application::get('addOns');
			$addon = (!empty($addons['siropu_chat'])) ? $addons['siropu_chat'] : 0;
		
			if (!$addon)
			{
				throw new XenForo_Exception('Unable to install SiropuChat widget; missing prerequisite Addon.', true);
			}
			
			if ($addon < 30)
			{
				throw new XenForo_Exception('Unable to install SiropuChat widget; prerequisite Addon is out of date.', true);
			}
		}
	}
}