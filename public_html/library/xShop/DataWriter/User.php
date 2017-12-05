<?php

/**
* Data writer for user.
*
* @package xShop
*/
class xShop_DataWriter_User extends XFCP_xShop_DataWriter_User
{
		/**
	 * Post-save handling.
	 */
	protected function _postSave()
	{
		parent::_postSave();
		
		$addPoints = false;
		if ($this->get('user_state') == 'valid')
		{
			if ($this->isInsert())
			{
				$addPoints = true;
			}
			else if ($this->isChanged('user_state'))
			{
				$previousState = $this->getExisting('user_state');
				if ($previousState == 'moderated' || $previousState == 'email_confirm')
				{
					$addPoints = true;
				}
			}
		}
		
		if ($addPoints)
		{
			$this->getModelFromCache('xShop_Model_Points')->assignUserPoints($this->get('user_id'), 'register');	
		}
	}
}