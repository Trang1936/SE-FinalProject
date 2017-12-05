<?php

class xShop_DataWriter_Items extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_items' => array(
                'item_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
        		'item_img' => array('type' => self::TYPE_STRING),
                'item_name' => array('type' => self::TYPE_STRING),
        		'item_desc' => array('type' => self::TYPE_STRING),
        		'item_cost' => array('type' => self::TYPE_UINT, 'default' => 0),
        		'item_cat_id' => array('type' => self::TYPE_UINT, 'default' => 0),
                'item_sold' => array('type' => self::TYPE_UINT, 'default' => 0),
        		'item_stock' => array('type' => self::TYPE_UINT, 'default' => 1)
            )
        );
    }

    protected function _getExistingData($data)
    {
        if (!$itemid = $this->_getExistingPrimaryKey($data, 'item_id'))
        {
            return false;
        }
        return array('xshop_items' => $this->getModelFromCache('xShop_Model_Items')->getItemId($itemid));
    }

    protected function _getUpdateCondition($tableName)
	{
		return 'item_id = ' . $this->_db->quote($this->getExisting('item_id'));
	}

    protected function _postDelete()
	{
		$model = $this->getModelFromCache('xShop_Model_Items');

		$deletedId = $this->_existingData['xshop_items']['item_id'];
		if ($model->hasData($deletedId))
		{
			$dw->delete();
		}
    }
}