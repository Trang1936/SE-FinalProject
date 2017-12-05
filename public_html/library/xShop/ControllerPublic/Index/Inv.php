<?php
class xShop_ControllerPublic_Index_Inv extends XenForo_ControllerPublic_Abstract
{
    public function actionIndex()
    {		
    	$this->_checkPerms();
    	$pointsModel = $this->getModelFromCache('xShop_Model_Points');
    	$stockModel = $this->getModelFromCache('xShop_Model_Stock');
    	
    	$visitor = XenForo_Visitor::getInstance();
		$visitor_id = $visitor->getUserId();
		$userName = $visitor['username'];
		
		$userStats = $pointsModel->getUserPoints($visitor_id);
        $invCount = $pointsModel->invStock($visitor_id);    	
		$stockByUser = $stockModel->getStockByUser($visitor_id);
		$stockCount = $stockModel->getUserStockCount($visitor_id);
		    	
    	$viewParams = array(
        	'username' => $userName,
			'userStats' => $userStats,
        	'visitor_id' => $visitor_id,
    		'userStock' => $stockByUser,
    		'invCount' => $invCount,
    		'stockCount' => $stockCount
    	);
    	if(!$stockCount)
    	{
    	return $this->responseView('xShop_ViewPublic_Shop_Inv', 'xshop_no_inv', $viewParams);
    	} else {
    	return $this->responseView('xShop_ViewPublic_Shop_Inv', 'xshop_inv', $viewParams);
    	}
    }
    public function actionSave()
    {
      $this->_assertPostOnly();

      $order = array();
      $display = array();

      $userId = XenForo_Visitor::getUserId();
      $stockModel = $this->_getStockModel();
      $userStock = $stockModel->getUserStockByItemRound($userId);
      if (empty($userStock))
          return $this->responseError('No such User Stock');

      foreach ($userStock AS $stock)
      {
              $stockId = $stock['stock_id'];
            $displayOrder = $this->_input->filterSingle('display_order_'.$stockId, XenForo_Input::UINT);
            $stockDisplay = $this->_input->filterSingle('stock_display_'.$stockId, XenForo_Input::UINT);

            $itemId = $stock['item_id'];

            $ds = XenForo_DataWriter::create('xShop_DataWriter_Stock');
            $ds->setExistingData(array('member_id' => $userId, 'item_id' => $itemId));

            $stockvars = array('display_order' => $displayOrder, 'stock_display' => $stockDisplay);

            // save
            $ds->bulkSet($stockvars);
            $ds->preSave();

            if ($ds->hasErrors())
            {
                $errors = $ds->getErrors();
                $errorKey = reset($errors);
                if ($errorKey)
                {
                    $errorKey = $errorKey instanceof XenForo_Phrase ? $errorKey : new XenForo_Phrase($errorKey);
                    return $this->responseError(new XenForo_Phrase($errorKey));
                }
            }

            $ds->save();
      }
 
    $redirectType = ($userId ?
                    XenForo_ControllerResponse_Redirect::RESOURCE_UPDATED :
                    XenForo_ControllerResponse_Redirect::RESOURCE_CREATED);

    return $this->responseRedirect(
                    $redirectType,
                    XenForo_Link::buildPublicLink('shop/inv')
        );
    }
	protected function _checkPerms()
    {
        if (!xShop_Permissions::canUseXshop()) {
            throw $this->getNoPermissionResponseException();
        }
    }
    protected function _getStockModel()
    {
        return $this->getModelFromCache('xShop_Model_Stock');
    }
}
?>