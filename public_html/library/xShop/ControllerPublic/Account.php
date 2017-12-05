<?php

/**
 * ControllerPublic class for displaying xShop items
 *
 * @package xShop
 */
class xShop_ControllerPublic_Account extends XFCP_xShop_ControllerPublic_Account
{
	public function actionShop()
	{
		$visitor = XenForo_Visitor::getInstance();
		$userId = $visitor['user_id'];
		$username = $visitor['username'];
	
    	$pointsModel = $this->getModelFromCache('xShop_Model_Points');
    	$stockModel = $this->getModelFromCache('xShop_Model_Stock');
    	
		$userStats = $pointsModel->getUserPoints($userId);
        $invCount = $pointsModel->invStock($userId);    	
		$stockByUser = $stockModel->getStockByUser($userId);
		$stockCount = $stockModel->getUserStockCount($userId);
		
    	$viewParams = array(
        	'username' => $username,
			'userStats' => $userStats,
        	'visitor_id' => $userId,
    		'userStock' => $stockByUser,
    		'invCount' => $invCount,
    		'stockCount' => $stockCount
    	);
    	
    	return $this->responseView('xShop_ViewPublic_Shop_Inv', 'xshop_inv', $viewParams);
	}
}