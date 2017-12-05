<?php

class xShop_DataWriter_UpdateItems extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_items' => array(
                'item_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
        		'item_name' => array('type' => self::TYPE_STRING),
        		'item_img' => array('type' => self::TYPE_STRING),
        		'item_desc' => array('type' => self::TYPE_STRING),
        		'item_cost' => array('type' => self::TYPE_UINT),
        		'item_cat_id' => array('type' => self::TYPE_UINT),
        		'item_sold' => array('type' => self::TYPE_UINT),
        		'item_stock' => array('type' => self::TYPE_UINT)
            )
        );
    }

protected function _getExistingData($data)
{
    if ($itemId = $this->_getExistingPrimaryKey($data))
    {
        return array('xshop_items' => $this->getModelFromCache('xShop_Model_Items')->getItemId($itemId));
    }
    else if (isset($data['item_type'])) // since we know it must be UNIQUE
    {
        return array('xshop_items' => $this->getModelFromCache('xShop_Model_Items')->getItemByCat($data['item_cat_id']));
    }
    

    return false;
}

    protected function _getUpdateCondition($tableName)
	{
		return 'item_id = ' . $this->_db->quote($this->getExisting('item_id'));
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