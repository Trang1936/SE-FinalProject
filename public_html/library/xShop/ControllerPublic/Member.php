<?php

/**
 * Controller for displaying public bookmarks
 *
 * @package xShop
 */
class xShop_ControllerPublic_Member extends XFCP_xShop_ControllerPublic_Member
{
	/**
	 * Member profile page
	 * Shop tab
	 *
	 * @return XenForo_ControllerResponse_View / XenForo_ControllerResponse_Redirect
	 */
	public function actionShop()
	{
		$userId = $this->_input->filterSingle('user_id', XenForo_Input::UINT);
		$user = $this->getHelper('UserProfile')->assertUserProfileValidAndViewable($userId);

    	$pointsModel = $this->getModelFromCache('xShop_Model_Points');
    	$stockModel = $this->getModelFromCache('xShop_Model_Stock');
    	
		$userStats = $pointsModel->getUserPoints($userId);
        $invCount = $pointsModel->invStock($userId);    	
		$stockByUser = $stockModel->getStockByUser($userId);
		$stockCount = $stockModel->getUserStockCount($userId);
		
    	$viewParams = array(
        	'username' => $user['username'],
			'userStats' => $userStats,
        	'visitor_id' => $userId,
    		'userStock' => $stockByUser,
    		'invCount' => $invCount,
    		'stockCount' => $stockCount
    	);
    	
    	if($invCount == 0)
    	{
    		return $this->responseView('xShop_ViewPublic_Shop_Inv', 'xshop_user_no_inv');
    	} else {
    	return $this->responseView('xShop_ViewPublic_Shop_Inv', 'xshop_member_inv', $viewParams);
    	}
	}
}
