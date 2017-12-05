<?php
class xShop_ControllerPublic_Index_Items extends XenForo_ControllerPublic_Abstract
{
    public function actionIndex()
    {		
    	$this->_checkPerms();
    	$catid = $this->_input->filterSingle('cat_id', XenForo_Input::UINT);
    	$catname = $this->_input->filterSingle('cat_title', XenForo_Input::STRING);
    	
    	$catsModel = $this->getModelFromCache('xShop_Model_Cats');
    	$itemsModel = $this->getModelFromCache('xShop_Model_Items');
    	$pointsModel = $this->getModelFromCache('xShop_Model_Points');
    	$stockModel = $this->getModelFromCache('xShop_Model_Stock');
    	
    	$visitor = XenForo_Visitor::getInstance();
		$visitor_id = $visitor->getUserId();
		$userName = $visitor['username'];
		
		$userStats = $pointsModel->getUserPoints($visitor_id);
        $invCount = $pointsModel->invStock($visitor_id);    	
    	$category = $catsModel->getCatId($catid);
    	$itemsByCat = $itemsModel->getItemByCat($catid);
		$countItems = $itemsModel->getCountItem($catid);
		$userStock = $stockModel->getUserStockId($visitor_id);
		    	
    	$viewParams = array(
    		'itemsByCat' => $itemsByCat,
    		'category' => $category,
    	    'invCount' => $invCount,
        	'username' => $userName,
			'userStats' => $userStats,
        	'visitor_id' => $visitor_id,
    		'countItems' => $countItems,
    		'userStock' => $userStock
    	);
    	
    	return $this->responseView('xShop_ViewPublic_Shop_Items', 'xshop_items', $viewParams);
    }
	protected function _checkPerms()
    {
        if (!xShop_Permissions::canUseXshop()) {
            throw $this->getNoPermissionResponseException();
        }
    	if (!xShop_Permissions::canBuyItems()) {
            throw $this->getNoPermissionResponseException();
        }
    }
}
?>