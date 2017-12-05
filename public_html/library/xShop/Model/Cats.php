<?php 
class xShop_Model_Cats extends XenForo_Model
{
	public function getCategories()
	{
		$categories = $this->_getDb()->fetchAll('
			SELECT *
					FROM xshop_cat
		');

		return $categories;
	}
	public function getAllCats()
	{
		return $this->fetchAllKeyed('
			SELECT *
				FROM xshop_cat
				ORDER BY cat_title
				', 'cat_id');
	}
	public function getActiveCategories()
	{
		$shopCat = $this->_getDb()->fetchAll('
			SELECT *
					FROM xshop_cat
					WHERE cat_active = 1
		');

		return $shopCat;
	}
	public function getCatId($cat_id)
	{
		$catById = $this->_getDb()->fetchRow('
			SELECT cat_id, cat_title, cat_description, cat_sold, cat_profit, cat_items, cat_active
				FROM xshop_cat
				WHERE cat_id = ?
				', $cat_id);
		
		return $catById ? $catById : false;
	}
	public function getUpdateCatId($cat_id)
	{
		$catById = $this->_getDb()->fetchRow('
			SELECT cat_id, cat_title, cat_description, cat_sold, cat_profit, cat_items, cat_active
				FROM xshop_cat
				WHERE cat_id = ?
				', $cat_id);
		
		return $catById ? $catById : false;
	}
    public function hasData($id)
    {
        return $this->_getDb()->fetchOne('SELECT COUNT(*) FROM xshop_cat WHERE cat_id = ?', $id);
    }
}
?>