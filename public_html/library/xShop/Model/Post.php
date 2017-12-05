<?php

/**
* Model for posts.
*
* @package xShop
*/
class xShop_Model_Post extends XFCP_xShop_Model_Post
{

    public function preparePost(array $post, array $thread, array $forum, array $nodePermissions = null, array $viewingUser = null)
    {
        $post = parent::preparePost($post, $thread, $forum, $nodePermissions, $viewingUser);

        $post_user_id = $post['user_id'];
        $stockModel = $this->_getStockModel();
        $userStockPost = $stockModel->getUserStockByPostUser($post_user_id);

        $msgImg = array();
        foreach ($userStockPost AS $posty)
        {
            if ($posty['stock_display'])
              $msgImg[$posty['image_name']] = $posty['image'];
        }

        if (!empty($msgImg))
            $post['msgImg'] = $msgImg;

        return $post;
    }

    protected function _getStockModel()
    {
        return $this->getModelFromCache('xShop_Model_Stock');
    }
}