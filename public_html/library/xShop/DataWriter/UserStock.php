<?php

class xShop_DataWriter_UserStock extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_stock' => array(
                'stock_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
                'item_id' => array('type' => self::TYPE_UINT),
        		'member_id' => array('type' => self::TYPE_UINT),
        		'upgrade_id' => array('type' => self::TYPE_UINT, 'default' => 0),
        		'stock_order' => array('type' => self::TYPE_UINT),
            )
        );
    }

protected function _getExistingData($data)
{
    if ($memberId = $this->_getExistingPrimaryKey($data))
    {
        return array('xshop_stock' => $this->getModelFromCache('xShop_Model_Stock')->getStockId($memberId));
    }
    else if (!is_array($data))
    {
    	return false;
    }
    else if (isset($data['member_id'], $data['item_id'])) // since we know it must be UNIQUE
    {
    	$itemId = $data['item_id'];
    	$memberId = $data['member_id'];
        return array('xshop_stock' => $this->getModelFromCache('xShop_Model_Stock')->getUserStockId($memberId));
    }

    return false;
}

    protected function _getUpdateCondition($tableName)
	{
		return 'stock_id = ' . $this->_db->quote($this->getExisting('stock_id'));
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