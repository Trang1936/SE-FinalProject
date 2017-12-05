<?php

/**
* Data writer for posts.
*
* @package xShop
*/
class xShop_DataWriter_DiscussionMessage_Post extends XFCP_xShop_DataWriter_DiscussionMessage_Post
{
	/**
	 * Post-save handling, after the transaction is committed.
	 */
	protected function _postSaveAfterTransaction()
	{
		// assign points if the message is visible, and is a new insert,
		// or is an update where the message state has changed from 'moderated'
		if ($this->get('message_state') == 'visible')
		{
			if ($this->isInsert() || $this->getExisting('message_state') == 'moderated')
			{
				$pointsModel = $this->getModelFromCache('xShop_Model_Points');
		
				// add points to this user's total
				if ($this->get('position') == 0) // new thread
				{
					$pointsModel->assignUserPoints($this->get('user_id'), 'thread');
				}
				else // new post
				{
					$pointsModel->assignUserPoints($this->get('user_id'), 'post');
				}
			}
		}
		
		parent::_postSaveAfterTransaction();
	}
}