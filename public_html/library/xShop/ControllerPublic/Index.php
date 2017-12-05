<?php
class xShop_ControllerPublic_Index extends XenForo_ControllerPublic_Abstract
{
    public function actionIndex()
    {	
    	$this->_checkPerms();
		$this->canonicalizeRequestUrl(
			XenForo_Link::buildPublicLink('shop', null)
		);
		
        $pointsModel = $this->getModelFromCache('xShop_Model_Points');
        $catsModel = $this->getModelFromCache('xShop_Model_Cats');
        $stockModel = $this->getModelFromCache('xShop_Model_Stock');
        
		$visitor = XenForo_Visitor::getInstance();
		$visitor_id = $visitor->getUserId();
		$userName = $visitor['username'];
		
        $userStats = $pointsModel->getUserPoints($visitor_id);
        $invCount = $pointsModel->invStock($visitor_id);
        $shopCat = $catsModel->getActiveCategories();
        $userStock = $stockModel->getUserStockId($visitor_id);
        
        $viewParams = array(
        	'shopCat' => $shopCat,
        	'invCount' => $invCount,
        	'username' => $userName,
			'userStats' => $userStats,
        	'visitor_id' => $visitor_id,
        	'userStock' => $userStock
        );

        return $this->responseView('xShop_ViewPublic_Shop', 'xshop_index', $viewParams);
    }
	protected function _checkPerms()
    {
        if (!xShop_Permissions::canUseXshop()) {
            throw $this->getNoPermissionResponseException();
        }
    }
}
?>