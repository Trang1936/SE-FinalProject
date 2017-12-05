<?php

final class xShop_Permissions
{
	const PERMISSIONS_GROUP = 'xshop';
	private static $permissions = array(
		'canUseXshop' => 'xshop_can_use',
		'canBuyItems' => 'xshop_can_buy',
		'canSellItems' => 'xshop_can_sell',
		'canDonatePoints' => 'xshop_can_donate_points',
		'canDonateItems' => 'xshop_can_donate_items'
	);

	public static function get($key)
	{
		return self::$permissions[$key];
	}

	public static function canUseXshop()
	{
		$visitor = XenForo_Visitor::getInstance();

		return $visitor->hasPermission(
			self::PERMISSIONS_GROUP,
			self::get('canUseXshop')
		);
	}

	public static function canBuyItems()
	{
		$visitor = XenForo_Visitor::getInstance();

		// only logged in user can create albums
		if ($visitor->getUserId())
		{
			return $visitor->hasPermission(
				self::PERMISSIONS_GROUP,
				self::get('canBuyItems')
			);
		} else {
			return false;
		}
	}

	public static function canSellItems()
	{
		$visitor = XenForo_Visitor::getInstance();

		if ($visitor->getUserId())
		{
			return $visitor->hasPermission(
				self::PERMISSIONS_GROUP,
				self::get('canSellItems')
			);
		} else {
			return false;
		}
	}
	
	public static function canDonatePoints()
	{
		$visitor = XenForo_Visitor::getInstance();
		
		if ($visitor->getUserId())
		{
			return $visitor->hasPermission(
				self::PERMISSIONS_GROUP,
				self::get('canDonatePoints')
			);
		} else {
			return false;
		}
	}
	
	public static function canDonateItems()
	{
		$visitor = XenForo_Visitor::getInstance();
		
		if ($visitor->getUserId())
		{
			return $visitor->hasPermission(
				self::PERMISSIONS_GROUP,
				self::get('canDonateItems')
			);
		} else {
			return false;
		}
	}

}