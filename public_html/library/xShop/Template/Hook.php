<?php

class xShop_Template_Hook
{
	// preload the templates
	public static function templateCreate($templateName, array &$params, XenForo_Template_Abstract $template)
	{
			if ($templateName == 'account_wrapper')
			{
				$template->preloadTemplate('xshop_account_wrapper_sidebar_your_account');
			}
			else if ($templateName == 'member_view')
			{
				$template->preloadTemplate('xshop_member_view_tabs_heading');
				$template->preloadTemplate('xshop_member_view_tabs_content');
			}
			if ($templateName == 'message')
			{
				$template->preloadTemplate('xshop_post_display');
			}
	}
	
	
	/**
	 * Called whenever a template hook is encountered (via <xen:hook> tags)
	 *
	 * @param string $name		the name of the template hook being called
	 * @param string $contents	the contents of the template hook block. This content will be the final rendered output of the block. You should manipulate this, such as by adding additional output at the end.
	 * @param array $params		explicit key-value params that have been passed to the hook, enabling content-aware decisions. These will not be all the params that are available to the template
	 * @param XenForo_Template_Abstract $template	the raw template object that has called this hook. You can access the template name and full, raw set of parameters via this object.
	 * @return unknown
	 */
	public static function template($hookName, &$contents, array $hookParams, XenForo_Template_Abstract $template)
	{
		switch ($hookName)
		{
			case 'navigation_tabs_account':
			{
				self::addShopNavTab($contents, $hookParams, $template);
				break;
			}
			case 'account_wrapper_sidebar_your_account':
			{
				self::addShopLink($contents, $hookParams, $template);
				break;
			}
			case 'member_view_tabs_heading':
			{
				self::addShopTab($contents, $hookParams, $template);
				break;
			}
			case 'member_view_tabs_content':
			{
				self::addShopTabContent($contents, $hookParams, $template);
				break;
			}
			case 'message_content':
			{
				self::addImageDisplayPost($contents, $hookParams, $template);
				break;
			}
		}
	}



	// insert the Bookmark link on left side of account page
	private static function addShopLink(&$contents, $hookParams, XenForo_Template_Abstract $template)
	{
		if (XenForo_Visitor::getUserId())
		{
            $hookParams['selectedKey'] = $template->getParam('selectedKey');
			$contents .= $template->create('xshop_account_wrapper_sidebar_your_account', $hookParams);
		}
	}

	//  insert the xshop link at the top navigation list on the account page
	private static function addShopNavTab(&$contents, $hookParams, XenForo_Template_Abstract $template)
	{
		if (XenForo_Visitor::getUserId())
			$contents .= $template->create('xshop_navigation_tabs_account', $hookParams);
	}
		
	//  display 'xshop' tab on member page
	private static function addShopTab(&$contents, $hookParams, XenForo_Template_Abstract $template)
	{
		$hookParams['requestPaths'] = $template->getParam('requestPaths');
		$contents .= $template->create('xshop_member_view_tabs_heading', $hookParams);
	}
	
	//  display 'xshop' member page tab content
	private static function addShopTabContent(&$contents, $hookParams, XenForo_Template_Abstract $template)
	{
		$contents .= $template->create('xshop_member_view_tabs_content', $hookParams);
	}
	private static function addImageDisplayPost(&$contents, $hookParams, XenForo_Template_Abstract $template)
	{
		$contents .= $template->create('xshop_post_display', $hookParams);
	}
}