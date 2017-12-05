<?php
class xShop_ControllerAdmin_Index extends XenForo_ControllerAdmin_Abstract
{
// MAIN PAGE
	public function actionIndex()
	{		
		return $this->responseView('xShop_ViewAdmin_Development_Index', 'xshop');
	}
}