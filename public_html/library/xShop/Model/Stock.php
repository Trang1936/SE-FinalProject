<?php 
class xShop_Model_Stock extends XenForo_Model
{
	public function getStock()
	{
		$stock = $this->_getDb()->fetchAll('
			SELECT stock.*, member.*, item.*, points.* FROM xshop_stock AS stock
			LEFT JOIN xf_user AS member ON (stock.member_id = member.user_id)
			LEFT JOIN xshop_items AS item ON (stock.item_id = item.item_id)
			LEFT JOIN xshop_points AS points ON (stock.member_id = points.user_id)
		');

		return $stock;
	}
	public function getAll()
	{
		$all = $this->_getDb()->fetchAll('
			SELECT * FROM xshop_items
			WHERE item_stock = 0
		');
		
		return $all;
	}
	public function getStockId($member_id)
	{
		return $this->_getDb()->fetchRow('
			SELECT stock.*, member.*, item.*, points.*
				FROM xshop_stock AS stock
				LEFT JOIN xf_user AS member ON (stock.member_id = member.user_id)
				LEFT JOIN xshop_items AS item ON (stock.item_id = item.item_id)
				LEFT JOIN xshop_points AS points ON (stock.member_id = points.user_id)
				WHERE stock.member_id = ?
				', $member_id);
	}
	public function getUserStockId($memberId)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
				FROM xshop_stock
				WHERE member_id = ?
				', $memberId);
		
	}
	public function getStockByUser($member_id)
	{
		$stockByUser = $this->_getDb()->fetchAll('
			SELECT items.*, stock.*
				FROM xshop_stock AS stock
				LEFT JOIN xshop_items AS items ON (stock.item_id = items.item_id)
				WHERE stock.member_id = ?
				ORDER BY stock.display_order
				', $member_id);
		
		return $stockByUser ? $stockByUser : false;
	}
	public function getUserStockCount($member_id)
	{
		return $this->_getDb()->fetchOne('
			SELECT COUNT(*)
				FROM xshop_stock
				WHERE member_id = ?
				', $member_id);
	}
    public function getUserStockByItemId($member_id, $item_id)
    {
        return $this->_getDb()->fetchRow('
            SELECT *
                FROM xshop_stock
                WHERE member_id = ? AND item_id = ?
                ', array($member_id, $item_id));
    }
    public function getUserStockByItemRound($member_id)
    {
        return $this->_getDb()->fetchAll('
            SELECT *
                FROM xshop_stock
                WHERE member_id = ?
                ', $member_id);
    }
    public function getUserStockByPostUser($post_user_id)
    {
    $postbitLimit = XenForo_Application::get('options')->xshop_post_limit;
    
        return $this->_getDb()->fetchAll('
            SELECT stock.stock_display, items.item_img AS image, items.item_name AS image_name
                FROM xshop_stock AS stock
                INNER JOIN xshop_items AS items ON(items.item_id = stock.item_id)
                WHERE stock.member_id = ?
                ORDER BY stock.display_order
                LIMIT ?
                ', array($post_user_id, $postbitLimit));
    }
    public function hasData($id)
    {
        return $this->_getDb()->fetchOne('SELECT COUNT(*) FROM xshop_stock WHERE stock_id = ?', $id);
    }
    public function countStock()
    {
        $stockCount = $this->_getDb()->fetchAll('SELECT COUNT(*) FROM xshop_stock');
        return $stockCount;
    }
}
?>