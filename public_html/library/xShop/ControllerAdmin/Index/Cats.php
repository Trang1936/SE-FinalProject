<?php

class xShop_ControllerAdmin_Index_Cats extends XenForo_ControllerAdmin_Abstract
{
//	public $perms;

	public function actionIndex()
	{
		$categories = $this->getModelFromCache('xShop_Model_Cats')->getCategories();
		
			$viewParams = array(
				'categories' => $categories
			);
		
		return $this->responseView('xShop_ViewAdmin_Cats', 'xshop_cats', $viewParams);
	}

	public function actionEdit()
	{
		$model = $this->getModelFromCache('xShop_Model_Cats');		
		$id = $this->_input->filterSingle('cat_id', XenForo_Input::UINT);

		$catById = $model->getCatId($id);
		$active = $catById['cat_active'];

		$viewParams = array(
			'id' => $id,
			'title' => $catById['cat_title'],
			'description' => $catById['cat_description'],
			'sold' => $catById['cat_sold'],
			'profit' => $catById['cat_profit'],
			'items' => $catById['cat_items'],
			'active' => $catById['cat_active'],
			'catById' => $catById,
		);

		return $this->responseView('xShop_ViewAdmin_EditCat', 'xshop_edit_cat', $viewParams);
	}		

	public function actionDelete()
	{
		$id = $this->_input->filterSingle('cat_id', XenForo_Input::UINT);
		$model = $this->getModelFromCache('xShop_Model_Cats');	
		
		if ($this->isConfirmedPost()) // delete add-on
		{
			$dw = XenForo_DataWriter::create('xShop_DataWriter_Cats');
			$dw->setExistingData($id);
			$dw->delete();
			
			return $this->responseRedirect(
                XenForo_ControllerResponse_Redirect::SUCCESS,
                XenForo_Link::buildAdminLink('xshop/cats')
            );
		}
		else // show delete confirmation prompt
		{
			$cat = $model->getCatId($id);
			$viewParams = array(
				'id' => $id,
				'cat' => $cat
			);

			return $this->responseView('xShop_ViewAdmin_Cat_Delete', 'xshop_cat_delete_confirm', $viewParams);
		}
	}

	public function actionCreate()
	{
        $viewParams = array(
            'cat_id' => null,
            'cat_title' => '',
            'cat_description' => '',
        	'cat_sold' => '',
        	'cat_profit' => '',
        	'cat_items' => '',
        	'cat_active' => ''
        );

        return $this->responseView('xShop_ViewAdmin_CreateCat', 'xshop_cat_create', $viewParams);
	}

	public function actionSave()
	{
		$this->_assertPostOnly();

		$dwInput = $this->_input->filter(array(
			'cat_id' => XenForo_Input::UINT,
			'cat_title' => XenForo_Input::STRING,
			'cat_description' => XenForo_Input::STRING,
			'cat_sold' => XenForo_Input::UINT,
			'cat_profit' => XenForo_Input::UINT,
			'cat_items' => XenForo_Input::UINT,
			'cat_active' => XenForo_Input::UINT
		));

		$dw = XenForo_DataWriter::create('xShop_DataWriter_Cats');
		if ($dwInput['cat_id'])
            $dw->setExistingData($dwInput['cat_id']);
		
		$dw->set('cat_id', $dwInput['cat_id']);
		$dw->set('cat_title', $dwInput['cat_title']);
		$dw->set('cat_description', $dwInput['cat_description']);
		$dw->set('cat_sold', $dwInput['cat_sold']);
		$dw->set('cat_profit', $dwInput['cat_profit']);
		$dw->set('cat_items', $dwInput['cat_items']);
		$dw->set('cat_active', $dwInput['cat_active']);
		$dw->save();
		
		$redirectType = ($dwInput['cat_id'] ?
            XenForo_ControllerResponse_Redirect::RESOURCE_UPDATED :
            XenForo_ControllerResponse_Redirect::RESOURCE_CREATED);
            
		return $this->responseRedirect(
			$redirectType,
			XenForo_Link::buildAdminLink('xshop/cats')
		);
	}
}