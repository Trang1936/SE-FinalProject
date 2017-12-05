<?php

class EWRporta2_Widget_Atendo2Featured extends XenForo_Model
{
	public function getCachedData(&$widget, $options)
	{
		if ((!$addon = $this->getModelFromCache('XenForo_Model_AddOn')->getAddOnById('EWRatendo2')) || empty($addon['active']))
		{
			return 'killWidget';
		}
		
		$limit = $options['atendo2featured_limit'];
		
		$occurs = $this->getModelFromCache('EWRatendo2_Model_Occurs')->getOccursByFeatured($limit);
		
		foreach ($occurs AS &$occur)
		{
			$address = explode(", ", $occur['event_address']);
			
			if (count($address) > 2)
			{
				unset($address[0]);
			}
			
			$occur['event_address'] = implode(", ", $address);
		}
		
		return $occurs;
	}
	
	public function getUncachedData($widget, &$options)
	{
		foreach ($widget['wCached'] AS $key => &$occur)
		{
			$occur = $this->getModelFromCache('EWRatendo2_Model_Occurs')->prepareOccur($occur, false);
		}
		
		return $widget['wCached'];
	}
}