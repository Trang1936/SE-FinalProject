<?php 
class xShop_Model_Points extends XenForo_Model
{
	public function getPoints()
	{
		$pts = $this->_getDb()->fetchAll('
			SELECT member.*,  points.* FROM xshop_points AS points
			LEFT JOIN xf_user AS member ON (points.user_id = member.user_id)
		');

		return $pts;
	}

	public function getPointsId($points_id)
	{
		$pointsById = $this->_getDb()->fetchRow('
			SELECT member.*, points.*
				FROM xshop_points AS points
				LEFT JOIN xf_user AS member ON (points.user_id = member.user_id)
				WHERE points_id = ?
				', $points_id);
		
		return $pointsById ? $pointsById : false;
	}
	public function getUserPointsId($points_id)
	{
		return $this->_getDb()->fetchRow('
			SELECT *
				FROM xshop_points
				WHERE points_id = ?
				', $points_id);
		
	}
    public function hasData($id)
    {
        return $this->_getDb()->fetchOne('SELECT COUNT(*) FROM xshop_points WHERE points_id = ?', $id);
    }
	public function getUserPoints($userId)
	{
		$userPoints = $this->_getDb()->fetchRow('
			SELECT *
				FROM xshop_points
				WHERE user_id = ?
				', $userId);
		
		return $userPoints;
	}
	public function invStock($visitor_id)
	{
		return $this->_getDb()->fetchOne('
			SELECT COUNT(*)
				FROM xshop_stock
				WHERE member_id = ?
				', $visitor_id);
	}
	public function allStock($visitor_id)
	{
		$stock = $this->_getDb()->fetchAll('
			SELECT *
				FROM xshop_stock
				WHERE member_id = ?
				', $visitor_id);
		
		return $stock;
	}
	
	
	public function assignUserPoints($userId, $type)
	{
		$dw = $this->_getPointsDataWriter();
		$options = XenForo_Application::get('options');
		
		switch ($type)
		{
			case 'post':
			{
				$numPoints = $options->xshop_currency_posts;
				break;
			}
			case 'thread':
			{
				$numPoints = $options->xshop_currency_thread;
				break;
			}
			case 'poll':
			{
				$numPoints = $options->xshop_currency_poll;
				break;
			}
			case 'attachment':
			{
				$numPoints = $options->xshop_currency_upload;
				break;
			}
			case 'register':
			{
				$numPoints = $options->xshop_currency_register;
				break;
			}
			default: // unknown type
			{
				return false;
			}
		}
		
		
		// for a new insert
		$pointsEarned = $totalPoints = $numPoints;
		
		$userPoints = $this->getUserPoints($userId);
		
		if (!empty($userPoints)) // update
		{
			if ($type == 'register') // user_state has changed to 'valid' but since we already have a table for this user means they have not just registered
				return false;
				
			$dw->setExistingData(array('user_id' => $userId));
			$totalPoints += $userPoints['points_total'];
			$pointsEarned += $userPoints['points_earned'];
		}
				
		$dw->set('user_id', $userId);
        $dw->set('points_total', $totalPoints);
        $dw->set('points_earned', $pointsEarned);
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
	
	/**
	 * @return xShop_DataWriter_UserPoints
	 */
	protected function _getPointsDataWriter()
	{
		return XenForo_DataWriter::create('xShop_DataWriter_UserPoints');
	}
}
?>