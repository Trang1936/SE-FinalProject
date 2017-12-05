<?php

class xShop_ControllerAdmin_Index_Stock extends XenForo_ControllerAdmin_Abstract
{
//	public $perms;

	public function actionIndex()
	{
		$stock = $this->getModelFromCache('xShop_Model_Stock')->getStock();
		$stockCount = $this->getModelFromCache('xShop_Model_Stock')->countStock();
		
			$viewParams = array(
				'stock' => $stock,
				'stockCount' => $stockCount
			);
		
		return $this->responseView('xShop_ViewAdmin_Stock', 'xshop_stock', $viewParams);
	}

	public function actionEdit()
	{
		$model = $this->getModelFromCache('xShop_Model_Stock');		
		$id = $this->_input->filterSingle('stock_id', XenForo_Input::UINT);

		$stockById = $model->getStockId($id);

		$viewParams = array(
			'id' => $id,
			'iid' => $stockById['item_id'],
			'mid' => $stockById['member_id'],
			'uid' => $stockById['upgrade_id'],
			'order' => $stockById['stock_order'],
			'username' => $stockById['username'],
			'earned' => $stockById['points_earned'],
			'total' => $stockById['points_total'],
			'item' => $stockById['item_name'],
			'pid' => $stockById['points_id'],
			'stockById' => $stockById
		);

		return $this->responseView('xShop_ViewAdmin_EditStock', 'xshop_edit_stock', $viewParams);
	}		

	public function actionDelete()
	{
		$id = $this->_input->filterSingle('stock_id', XenForo_Input::UINT);
		$model = $this->getModelFromCache('xShop_Model_Stock');	
		
		if ($this->isConfirmedPost())
		{
			$dw = XenForo_DataWriter::create('xShop_DataWriter_Stock');
			$dw->setExistingData($id);
			$dw->delete();
			
			return $this->responseRedirect(
                XenForo_ControllerResponse_Redirect::SUCCESS,
                XenForo_Link::buildAdminLink('xshop/stock')
            );
		}
		else
		{
			$stock = $model->getStockId($id);
			$viewParams = array(
				'id' => $id,
				'stock' => $stock
			);

			return $this->responseView('xShop_ViewAdmin_Stock_Delete', 'xshop_stock_delete_confirm', $viewParams);
		}
	}	
	public function actionSave()
	{
		$this->_assertPostOnly();

		$dwInput = $this->_input->filter(array(
			'stock_id' => XenForo_Input::UINT,
			'item_id' => XenForo_Input::STRING,
			'member_id' => XenForo_Input::STRING,
			'upgrade_id' => XenForo_Input::STRING,
			'stock_order' => XenForo_Input::UINT
		));

		$dw = XenForo_DataWriter::create('xShop_DataWriter_Stock');
		if ($dwInput['stock_id'])
            $dw->setExistingData($dwInput['stock_id']);
		
		$dw->set('stock_id', $dwInput['stock_id']);
		$dw->set('item_id', $dwInput['item_id']);
		$dw->set('member_id', $dwInput['member_id']);
		$dw->set('upgrade_id', $dwInput['upgrade_id']);
		$dw->set('stock_order', $dwInput['stock_order']);
		$dw->save();
		
		$redirectType = ($dwInput['stock_id'] ?
            XenForo_ControllerResponse_Redirect::RESOURCE_UPDATED :
            XenForo_ControllerResponse_Redirect::RESOURCE_CREATED);
            
		return $this->responseRedirect(
			$redirectType,
			XenForo_Link::buildAdminLink('xshop/stock')
		);
	}
}