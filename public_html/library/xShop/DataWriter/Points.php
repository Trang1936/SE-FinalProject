<?php

class xShop_DataWriter_Points extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_points' => array(
                'points_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
        		'user_id' => array('type' => self::TYPE_UINT),
                'points_total' => array('type' => self::TYPE_UINT, 'default' => 0),
        		'points_earned' => array('type' => self::TYPE_UINT, 'default' => 0)
            )
        );
    }

    protected function _getExistingData($data)
    {
        if (!$pointsid = $this->_getExistingPrimaryKey($data, 'points_id'))
        {
            return false;
        }
        return array('xshop_points' => $this->getModelFromCache('xShop_Model_Points')->getPointsId($pointsid));
    }

    protected function _getUpdateCondition($tableName)
	{
		return 'points_id = ' . $this->_db->quote($this->getExisting('points_id'));
	}

    protected function _postDelete()
	{
		$model = $this->getModelFromCache('xShop_Model_points');

		$deletedId = $this->_existingData['xshop_points']['points_id'];
		if ($model->hasData($deletedId))
		{
			$dw->delete();
		}
    }
}