<?php
class xShop_ControllerPublic_Index_Buy extends XenForo_ControllerPublic_Abstract
{
    public function actionIndex()
    {
        $this->_checkUse();
        $itemid = $this->_input->filterSingle('item_id', XenForo_Input::UINT);
        $item_name = $this->_input->filterSingle('item_name', XenForo_Input::STRING);

        $itemsModel = $this->getModelFromCache('xShop_Model_Items');
        $pointsModel = $this->getModelFromCache('xShop_Model_Points');
        $categoryModel = $this->_getCategoryModel();

        $visitor = XenForo_Visitor::getInstance();
        $visitor_id = $visitor->getUserId();
        $userName = $visitor['username'];

        $userStats = $pointsModel->getUserPoints($visitor_id);
        $allInv = $pointsModel->invStock($visitor_id);
        $stock = $pointsModel->allStock($visitor_id);
        $item = $itemsModel->getItemId($itemid);

        $category = $categoryModel->getCatId($item['item_cat_id']);

        $viewParams = array(
            'allInv' => $allInv,
            'username' => $userName,
            'userStats' => $userStats,
            'visitor_id' => $visitor_id,
            'stock' => $stock,
            'item' => $item,
            'category' => $category
        );

        return $this->responseView('xShop_ViewPublic_Shop_Buy', 'xshop_buy_confirm', $viewParams);
    }

    public function actionConfirm()
    {
      $this->_assertPostOnly();
        $this->_checkUse();
        $this->_checkBuy();
      $dwInput = $this->_input->filter(array(
        'item_id' => XenForo_Input::UINT,
        'item_name' => XenForo_Input::STRING,
        'item_cost' => XenForo_Input::UINT,
        'item_stock' => XenForo_Input::UINT,
        'item_sold' => XenForo_Input::UINT,
        'points_id' => XenForo_Input::UINT,
        'points_earned' => XenForo_Input::UINT,
        'stock_order' => XenForo_Input::UINT,
        'member_id' => XenForo_Input::UINT,
        'stock_id' => XenForo_Input::UINT,
        'cat_id' => XenForo_Input::UINT,
    ));

        $userId = $dwInput['member_id'];
        $catId = $dwInput['cat_id'];
        $itemId = $dwInput['item_id'];
        $item_cost = $dwInput['item_cost'];

        $pointsModel = $this->_getPointsModel();
        $points_exists = $pointsModel->getUserPoints($userId);

        // ENSURE USER CAN PAY FOR NEW ITEM
        if ($points_exists['points_total'] >= $item_cost)
        {
            // UPDATE ITEM STOCK IF EXISTS OTHERWISE ERROR
            $itemModel = $this->_getItemsModel();
            $item_exists = $itemModel->getItemId($itemId);
            if ($item_exists) // update
            {
                $dt = XenForo_DataWriter::create('xShop_DataWriter_UpdateItems');
                $dt->setExistingData($itemId);

                // fetch existing values
                $item_sold = $item_exists['item_sold'];
                $item_stock = $item_exists['item_stock'];

                // set new values
                $new_sold = $item_sold + 1;
                $new_stock = $item_stock - 1;

                // save
                $itemvars = array('item_sold' => $new_sold, 'item_stock' => $new_stock);
                $dt->bulkSet($itemvars);
                $dt->preSave();

                if ($dt->hasErrors())
                {
                    $errors = $dt->getErrors();
                    $errorKey = reset($errors);
                    if ($errorKey)
                    {
                        $errorKey = $errorKey instanceof XenForo_Phrase ? $errorKey : new XenForo_Phrase($errorKey);
                        return $this->responseError(new XenForo_Phrase($errorKey));
                    }
                }

                $dt->save();
            }
            else
            {
                // return error
                return $this->responseError('No such Item');
            }

            // SAVE NEW CATEGORY SOLD TOTAL AND PROFIT TOTAL IF EXISTS
            $catModel = $this->_getCategoryModel();
            $cat_exists = $catModel->getCatId($catId);
            if ($cat_exists) // update
            {
                $de = XenForo_DataWriter::create('xShop_DataWriter_UpdateCat');
                  $de->setExistingData($catId);

                  // fetch existing values
                $cat_sold = $cat_exists['cat_sold'];
                $cat_profit = $cat_exists['cat_profit'];

                  // set new values
                $new_total = $cat_sold + 1;
                $new_profit = $cat_profit + $item_cost;

                // save
                $catvars = array('cat_sold' => $new_total, 'cat_profit' => $new_profit);
                $de->bulkSet($catvars);
                $de->preSave();

                if ($de->hasErrors())
                {
                    $errors = $de->getErrors();
                    $errorKey = reset($errors);
                    if ($errorKey)
                    {
                        $errorKey = $errorKey instanceof XenForo_Phrase ? $errorKey : new XenForo_Phrase($errorKey);
                        return $this->responseError(new XenForo_Phrase($errorKey));
                    }
                }

                $de->save();
            }
            else
            {
                // return error
                return $this->responseError('No such Category');
            }

            // UPDATE OR INSERT NEW STOCK TOTAL
            $stockModel = $this->_getStockModel();
            $stock_exists = $stockModel->getUserStockByItemId($userId, $itemId);

            $ds = XenForo_DataWriter::create('xShop_DataWriter_Stock');
            if ($stock_exists) // update
            {
                $ds->setExistingData(array('member_id' => $userId, 'item_id' => $itemId));
                // fetch existing values
                  $stock_total = $stock_exists['stock_order'];
                  $stockvars = array('stock_order' => $stock_total + 1);
            }
            else
            {
                $stockvars = array('member_id' => $userId, 'item_id' => $itemId, 'stock_order' => 1);
            }

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
 
            // UPDATE TOTAL POINTS IF EXISTS
            if ($points_exists) // update
            {
                $dw = XenForo_DataWriter::create('xShop_DataWriter_UserPoints');
                $dw->setExistingData(array('user_id' => $userId));

                // fetch existing values
                $points_total = $points_exists['points_total'];

                // set new values
                $new_total = $points_total - $item_cost;

                // save
                $dw->set('points_total', $new_total);
                $dw->preSave();

                if ($dw->hasErrors())
                {
                    $errors = $dw->getErrors();
                    $errorKey = reset($errors);
                    if ($errorKey)
                    {
                        $errorKey = $errorKey instanceof XenForo_Phrase ? $errorKey : new XenForo_Phrase($errorKey);
                        return $this->responseError(new XenForo_Phrase($errorKey));
                    }
                }

                $dw->save();
            }
            else
            {
                // return error
                return $this->responseError('No such User Points');
            }
        }
        else // cannot pay for item
        {
            // return error
            return $this->responseError('xshop_not_enough_points');
        }

        $redirectType = ($userId ?
                    XenForo_ControllerResponse_Redirect::RESOURCE_UPDATED :
                    XenForo_ControllerResponse_Redirect::RESOURCE_CREATED);

        return $this->responseRedirect(
                    $redirectType,
                    XenForo_Link::buildPublicLink('shop/inv')
        );
    }

    protected function _getPointsModel()
    {
        return $this->getModelFromCache('xShop_Model_Points');
    }

    protected function _getStockModel()
    {
        return $this->getModelFromCache('xShop_Model_Stock');
    }

    protected function _getCategoryModel()
    {
        return $this->getModelFromCache('xShop_Model_Cats');
    }

    protected function _getItemsModel()
    {
        return $this->getModelFromCache('xShop_Model_Items');
    }
    protected function _checkUse()
    {
        if (!xShop_Permissions::canUseXshop()) {
            throw $this->getNoPermissionResponseException();
        }
    }
    protected function _checkBuy()
    {
        if (!xShop_Permissions::canBuyItems()) {
            throw $this->getNoPermissionResponseException();
        }
    }
    protected function _checkSell()
    {
        if (!xShop_Permissions::canSellItems()) {
            throw $this->getNoPermissionResponseException();
        }
    }
}
?>