<?php
/**
 * Listener for load_class_model code event
 */

class xShop_Listener_Post
{
	/**
	 * If we are getting the post model, extend it with our custom class that holds a method
	 * for the images.
	 *
	 * @param string The name of the class to be created
	 * @param array A modifiable list of classes that wish to extend the class.
	 */
	public static function listen($class, array &$extend)
	{
		if ($class == 'XenForo_Model_Post')
		{
			$extend[] = 'xShop_Model_Post';
		}		
	}

}
