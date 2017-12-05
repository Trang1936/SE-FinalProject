<?php

/**
* Data writer for polls.
*
* @package xShop
*/
class xShop_DataWriter_Poll extends XFCP_xShop_DataWriter_Poll
{
	/**
	 * Post-save handling.
	 */
	protected function _postSave()
	{
		if ($this->isInsert())
		{
			$threadId = $this->get('content_id');
			$threadModel = $this->getModelFromCache('XenForo_Model_Thread');
			$thread = $threadModel->getThreadById($threadId);
			
			$pointsModel = $this->getModelFromCache('xShop_Model_Points');
			$pointsModel->assignUserPoints($thread['user_id'], 'poll');
		}
		
		parent::_postSave();
	}
}