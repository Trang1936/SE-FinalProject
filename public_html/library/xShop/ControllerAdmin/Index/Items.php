<?php

class xShop_ControllerAdmin_Index_Items extends XenForo_ControllerAdmin_Abstract
{
//	public $perms;

	public function actionIndex()
	{
		$items = $this->getModelFromCache('xShop_Model_Items')->getItems();
		
			$viewParams = array(
				'items' => $items
			);
		
		return $this->responseView('xShop_ViewAdmin_Items', 'xshop_items', $viewParams);
	}

	public function actionEdit()
	{
		$model = $this->getModelFromCache('xShop_Model_Items');		
		$catModel = $this->getModelFromCache('xShop_Model_Cats');
		
		$id = $this->_input->filterSingle('item_id', XenForo_Input::UINT);
		$catid = $this->_input->filterSingle('cat_id', XenForo_Input::UINT);

		$itemById = $model->getItemId($id);
		$catById = $catModel->getAllCats();

		$viewParams = array(
			'id' => $id,
			'img' => $itemById['item_img'],
			'title' => $itemById['item_name'],
			'description' => $itemById['item_desc'],
			'cost' => $itemById['item_cost'],
			'type' => $itemById['item_cat_id'],
			'sold' => $itemById['item_sold'],
			'stock' => $itemById['item_stock'],
			'catById' => $catById,
			'itemById' => $itemById,
		);

		return $this->responseView('xShop_ViewAdmin_EditItem', 'xshop_edit_item', $viewParams);
	}		

	public function actionDelete()
	{
		$id = $this->_input->filterSingle('item_id', XenForo_Input::UINT);
		$catid = $this->_input->filterSingle('item_cat_id', XenForo_Input::UINT);
		$model = $this->getModelFromCache('xShop_Model_Items');	
		
		if ($this->isConfirmedPost()) // delete add-on
		{
			$dw = XenForo_DataWriter::create('xShop_DataWriter_Items');
			$dw->setExistingData($id);
			$dw->delete();
			        
			$dc = XenForo_DataWriter::create('xShop_DataWriter_Cats');
        	if ($catid)
            	$dc->setExistingData($catid);
        			$totalCatItems = $dc->get('cat_items');
          		$dc->set('cat_items', $totalCatItems - 1);
          		$dc->save();
          
			return $this->responseRedirect(
                XenForo_ControllerResponse_Redirect::SUCCESS,
                XenForo_Link::buildAdminLink('xshop/items')
            );
		}
		else // show delete confirmation prompt
		{
			$item = $model->getItemId($id);
			$viewParams = array(
				'id' => $id,
				'item' => $item
			);

			return $this->responseView('xShop_ViewAdmin_Item_Delete', 'xshop_item_delete_confirm', $viewParams);
		}
	}

	public function actionAdd()
	{
		$catModel = $this->getModelFromCache('xShop_Model_Cats');
		
		$catById = $catModel->getAllCats();
		
		$viewParams = array(
            'item_id' => null,
        	'item_img' => '',
            'item_name' => '',
            'item_desc' => '',
        	'item_cost' => '',
        	'item_cat_id' => '',
        	'item_sold' => '',
        	'item_stock' => '',
			'catById' => $catById
        );

        return $this->responseView('xShop_ViewAdmin_AddItem', 'xshop_item_add', $viewParams);
	}

    public function actionSave()
    {
        $this->_assertPostOnly();

        $dwInput = $this->_input->filter(array(
            'item_id' => XenForo_Input::UINT,
            'item_img' => XenForo_Input::STRING,
            'item_name' => XenForo_Input::STRING,
            'item_desc' => XenForo_Input::STRING,
            'item_cost' => XenForo_Input::UINT,
            'item_cat_id' => XenForo_Input::UINT,
            'item_sold' => XenForo_Input::UINT,
            'item_stock' => XenForo_Input::UINT
        ));

        $dw = XenForo_DataWriter::create('xShop_DataWriter_Items');
        if ($dwInput['item_id'])
            $dw->setExistingData($dwInput['item_id']);

        $dw->set('item_id', $dwInput['item_id']);
        $dw->set('item_img', $dwInput['item_img']);
        $dw->set('item_name', $dwInput['item_name']);
        $dw->set('item_desc', $dwInput['item_desc']);
        $dw->set('item_cost', $dwInput['item_cost']);
        $dw->set('item_cat_id', $dwInput['item_cat_id']);
        $dw->set('item_sold', $dwInput['item_sold']);
        $dw->set('item_stock', $dwInput['item_stock']);
        $dw->save();
 
        $dc = XenForo_DataWriter::create('xShop_DataWriter_Cats');
        if ($dwInput['item_cat_id'])
            $dc->setExistingData($dwInput['item_cat_id']);
        $totalCatItems = $dc->get('cat_items');
          $dc->set('cat_items', $totalCatItems + 1);
          $dc->save();
 
        $redirectType = ($dwInput['item_id'] ?
            XenForo_ControllerResponse_Redirect::RESOURCE_UPDATED :
            XenForo_ControllerResponse_Redirect::RESOURCE_CREATED);

        return $this->responseRedirect(
            $redirectType,
            XenForo_Link::buildAdminLink('xshop/items')
        );
    }
}