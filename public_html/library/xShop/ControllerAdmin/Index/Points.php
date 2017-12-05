<?php

class xShop_ControllerAdmin_Index_Points extends XenForo_ControllerAdmin_Abstract
{
//	public $perms;

	public function actionIndex()
	{
		$pts = $this->getModelFromCache('xShop_Model_Points')->getPoints();
		
			$viewParams = array(
				'pts' => $pts
			);
		
		return $this->responseView('xShop_ViewAdmin_Points', 'xshop_points', $viewParams);
	}

	public function actionEdit()
	{
		$model = $this->getModelFromCache('xShop_Model_Points');		
		$id = $this->_input->filterSingle('points_id', XenForo_Input::UINT);

		$pointsById = $model->getPointsId($id);

		$viewParams = array(
			'id' => $id,
			'username' => $pointsById['username'],
			'mid' => $pointsById['user_id'],
			'earned' => $pointsById['points_earned'],
			'total' => $pointsById['points_total'],
			'pointsById' => $pointsById
		);

		return $this->responseView('xShop_ViewAdmin_EditPoints', 'xshop_edit_points', $viewParams);
	}		

	public function actionSave()
	{
		$this->_assertPostOnly();

		$dwInput = $this->_input->filter(array(
			'points_id' => XenForo_Input::UINT,
			'user_id' => XenForo_Input::UINT,
			'points_total' => XenForo_Input::UINT,
			'points_earned' => XenForo_Input::UINT
		));

		$dw = XenForo_DataWriter::create('xShop_DataWriter_Points');
		if ($dwInput['points_id'])
            $dw->setExistingData($dwInput['points_id']);
		
		$dw->set('points_id', $dwInput['points_id']);
		$dw->set('user_id', $dwInput['user_id']);
		$dw->set('points_total', $dwInput['points_total']);
		$dw->set('points_earned', $dwInput['points_earned']);
		$dw->save();
		
		$redirectType = ($dwInput['points_id'] ?
            XenForo_ControllerResponse_Redirect::RESOURCE_UPDATED :
            XenForo_ControllerResponse_Redirect::RESOURCE_CREATED);
            
		return $this->responseRedirect(
			$redirectType,
			XenForo_Link::buildAdminLink('xshop/points')
		);
	}
}