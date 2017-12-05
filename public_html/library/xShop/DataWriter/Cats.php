<?php

class xShop_DataWriter_Cats extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_cat' => array(
                'cat_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
                'cat_title' => array('type' => self::TYPE_STRING),
        		'cat_description' => array('type' => self::TYPE_STRING),
        		'cat_sold' => array('type' => self::TYPE_UINT, 'default' => 0),
        		'cat_profit' => array('type' => self::TYPE_UINT, 'default' => 0),
                'cat_items' => array('type' => self::TYPE_UINT, 'default' => 0),
        		'cat_active' => array('type' => self::TYPE_UINT, 'default' => 1)
            )
        );
    }

    protected function _getExistingData($data)
    {
        if (!$catid = $this->_getExistingPrimaryKey($data, 'cat_id'))
        {
            return false;
        }
        return array('xshop_cat' => $this->getModelFromCache('xShop_Model_Cats')->getCatId($catid));
    }

    protected function _getUpdateCondition($tableName)
	{
		return 'cat_id = ' . $this->_db->quote($this->getExisting('cat_id'));
	}

    protected function _postDelete()
	{
		$model = $this->getModelFromCache('xShop_Model_Cats');

		$deletedId = $this->_existingData['xshop_cat']['cat_id'];
		if ($model->hasData($deletedId))
		{
			$dw->delete();
		}
    }
}