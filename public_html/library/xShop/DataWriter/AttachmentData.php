<?php

/**
* Data writer for attachments.
*
* @package xShop
*/
class xShop_DataWriter_AttachmentData extends XFCP_xShop_DataWriter_AttachmentData
{
	/**
	 * Post-save handling.
	 */
	protected function _postSave()
	{
		parent::_postSave();
			
		$pointsModel = $this->getModelFromCache('xShop_Model_Points');
		$pointsModel->assignUserPoints($this->get('user_id'), 'attachment');
	}
}