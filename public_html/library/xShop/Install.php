<?php

class xShop_Install
{
    public static function installShop($existingAddOn)
    {
    	$db = XenForo_Application::get('db'); 
 		$db->query("CREATE TABLE IF NOT EXISTS `xshop_cat` (
  				`cat_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  				`cat_title` VARCHAR(255),
  				`cat_description` VARCHAR(255),
  				`cat_sold` INT(11),
  				`cat_profit` INT(11),
  				`cat_items` INT(11),
  				`cat_active` INT ( 1 ))
				ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
				");
 		$db->query("INSERT INTO `xshop_cat` (`cat_id`, `cat_title`, `cat_description`, `cat_sold`, `cat_profit`, `cat_items`, `cat_active`) VALUES
				(1, 'Test Cat', 'Get them while they are hot, items that is.', 0, 0, 1, 1)");
  		$db->query("CREATE TABLE IF NOT EXISTS `xshop_items` (
				`item_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
				`item_img` TEXT,
				`item_name` TEXT,
				`item_desc` TEXT,
				`item_cost` INT( 11 ) DEFAULT 0,
				`item_cat_id` TEXT,
				`item_sold` INT( 11 ) DEFAULT 0,
				`item_stock` INT( 11 ) DEFAULT 50)
				ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
				");
  		$db->query("INSERT INTO `xshop_items` (`item_id`, `item_img`, `item_name`, `item_desc`, `item_cost`, `item_cat_id`, `item_sold`, `item_stock`) VALUES
  				(1, '001.gif', 'Test Item', 'Testing items only.', 1, 1, 0, 50)");
  		$db->query("CREATE TABLE IF NOT EXISTS `xshop_stock` (
				`stock_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`item_id` INT( 11 ) DEFAULT 0,
				`member_id` INT( 11 ) NOT NULL ,
				`upgrade_id` INT( 15 ) DEFAULT 0 ,
				`stock_order` INT( 11 ) DEFAULT 0,
				`display_order` INT( 11 ) DEFAULT 1,
				`stock_display` INT( 1 ) DEFAULT 0)
				ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
				");
  		$db->query("CREATE TABLE IF NOT EXISTS `xshop_points` (
  				`points_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  				`user_id` INT( 11 ),
  				`points_total` INT( 11 ),
  				`points_earned` INT( 11 ))
				ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci
				");
    }
    public static function uninstallShop(array $addonInfo)
    {
    	$db = XenForo_Application::get('db'); 
        $db->query('DROP TABLE xshop_cat');
        $db->query('DROP TABLE xshop_items');
        $db->query('DROP TABLE xshop_stock');
        $db->query('DROP TABLE xshop_points');
    }
}
?>