<?php 
class xShop_Model_Items extends XenForo_Model
{
	public function getItems()
	{
		$items = $this->_getDb()->fetchAll('
			SELECT *
					FROM xshop_items
		');

		return $items;
	}
	public function getItemId($item_id)
	{
		$itemById = $this->_getDb()->fetchRow('
			SELECT item_id, item_img, item_name, item_desc, item_cost, item_cat_id, item_sold, item_stock
				FROM xshop_items
				WHERE item_id = ?
				', $item_id);
		
		return $itemById ? $itemById : false;
	}
	public function getItemByCat($catid)
	{
		$itemsByCat = $this->_getDb()->fetchAll('
			SELECT *
				FROM xshop_items
				WHERE item_cat_id = ?
				', $catid);
		
		return $itemsByCat;
	}
	public function getCountItem($catid)
	{
		return $this->_getDb()->fetchOne('
			SELECT COUNT(*)
				FROM xshop_items
				WHERE item_cat_id = ?
				', $catid);
	}
    public function hasData($id)
    {
        return $this->_getDb()->fetchOne('SELECT COUNT(*) FROM xshop_items WHERE item_id = ?', $id);
    }
}
?>