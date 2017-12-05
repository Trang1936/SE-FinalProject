<?php

class xShop_DataWriter_UserPoints extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_points' => array(
                'points_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
                'user_id' => array('type' => self::TYPE_UINT),
        		'points_total' => array('type' => self::TYPE_UINT),
        		'points_earned' => array('type' => self::TYPE_UINT)
            )
        );
    }

protected function _getExistingData($data)
{
    if ($pointsId = $this->_getExistingPrimaryKey($data))
    {
        return array('xshop_points' => $this->getModelFromCache('xShop_Model_Points')->getUserPointsId($pointsId));
    }
    else if (isset($data['user_id'])) // since we know it must be UNIQUE
    {
        return array('xshop_points' => $this->getModelFromCache('xShop_Model_Points')->getUserPoints($data['user_id']));
    }

    return false;
}

    protected function _getUpdateCondition($tableName)
	{
		return 'points_id = ' . $this->_db->quote($this->getExisting('points_id'));
	}

/*    protected function _postDelete()
	{

		$model = $this->getModelFromCache('xShop_Model_Stock');

		$deletedId = $this->_existingData['xshop_stock']['member_id'];
		if ($model->hasData($deletedId))
		{
			$dw->delete();
		}
    }*/
}