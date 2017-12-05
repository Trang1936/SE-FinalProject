<?php

class xShop_Listener_LoadClassDataWriter
{
    /**
     * Instruct the system that XenForo_DataWriter_X
     * should be extended by xShop_DataWriter_X
     *
     * @param string $class
     * @param array $extend
     */
    public static function loadClassDataWriter($class, array &$extend)
    {
        if ($class == 'XenForo_DataWriter_DiscussionMessage_Post')
        {
            $extend[] = 'xShop_DataWriter_DiscussionMessage_Post';
        }
        else if ($class == 'XenForo_DataWriter_Poll')
        {
        	$extend[] = 'xShop_DataWriter_Poll';
        }
        else if ($class == 'XenForo_DataWriter_AttachmentData')
        {
        	$extend[] = 'xShop_DataWriter_AttachmentData';
        }
        else if ($class == 'XenForo_DataWriter_User')
        {
        	$extend[] = 'xShop_DataWriter_User';
        }
    }
}