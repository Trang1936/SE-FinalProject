<?php

class xShop_DataWriter_Stock extends XenForo_DataWriter
{
    protected function _getFields()
    {
        return array(
            'xshop_stock' => array(
                'stock_id' => array('type' => self::TYPE_UINT, 'autoIncrement' => true),
                'item_id' => array('type' => self::TYPE_UINT),
                'member_id' => array('type' => self::TYPE_UINT),
                'upgrade_id' => array('type' => self::TYPE_UINT),
                'stock_order' => array('type' => self::TYPE_UINT, 'default' => 1),
        		'display_order' => array('type' => self::TYPE_UINT),
        		'stock_display' => array('type' => self::TYPE_UINT)
            )
        );
    }

    protected function _getExistingData($data)
    {
        if ($stockid = $this->_getExistingPrimaryKey($data))
        {
            return array('xshop_stock' => $this->getModelFromCache('xShop_Model_Stock')->getStockId($stockid));
        }
        else if (isset($data['member_id']) && isset($data['item_id'])) // since we know it must be UNIQUE
        {
            return array('xshop_stock' => $this->getModelFromCache('xShop_Model_Stock')->getUserStockByItemId($data['member_id'], $data['item_id']));
        }

        return false;
    }

    protected function _getUpdateCondition($tableName)
    {
        return 'stock_id = ' . $this->_db->quote($this->getExisting('stock_id'));
    }

    protected function _postDelete()
    {
        $model = $this->getModelFromCache('xShop_Model_Stock');

        $deletedId = $this->_existingData['xshop_stock']['stock_id'];
        if ($model->hasData($deletedId))
        {
            $dw->delete();
        }
    }
}