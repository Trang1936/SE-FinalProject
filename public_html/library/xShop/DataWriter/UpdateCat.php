<?php

class xShop_DataWriter_UpdateCat extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_cat' => array(
                'cat_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
        		'cat_title' => array('type' => self::TYPE_STRING),
        		'cat_description' => array('type' => self::TYPE_STRING),
        		'cat_sold' => array('type' => self::TYPE_UINT),
        		'cat_profit' => array('type' => self::TYPE_UINT),
        		'cat_items' => array('type' => self::TYPE_UINT),
        		'cat_active' => array('type' => self::TYPE_UINT)
            )
        );
    }

protected function _getExistingData($data)
{
    if ($catId = $this->_getExistingPrimaryKey($data))
    {
        return array('xshop_cat' => $this->getModelFromCache('xShop_Model_Cats')->getUpdateCatId($catId));
    }

    return false;
}

    protected function _getUpdateCondition($tableName)
	{
		return 'cat_id = ' . $this->_db->quote($this->getExisting('cat_id'));
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