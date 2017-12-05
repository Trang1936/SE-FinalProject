<?php
	class xShop_NavTab
	{
		public static function portTabs(array &$extraTabs, $selectedTabId)
		{
			$options = XenForo_Application::get('options');
			
			if ($options->xshop_enable)
			{
       			$extraTabs['xshop'] = array(
            		'title' => new XenForo_Phrase('xshop_navtab'),
            		'href'  => XenForo_Link::buildPublicLink('shop'),
       				'selected'  =>  ($selectedTabId == 'xshop'),
            		'linksTemplate' => 'xshop_links',
            		'position'  =>  'middle'
        		);
			}
		}
	}
?>