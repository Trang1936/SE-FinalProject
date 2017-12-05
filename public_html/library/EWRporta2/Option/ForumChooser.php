<?php

class EWRporta2_Option_ForumChooser
{
	public static function renderOption(XenForo_View $view, $fieldPrefix, array $preparedOption, $canEdit)
	{
		$editLink = $view->createTemplateObject('option_list_option_editlink', array(
			'preparedOption' => $preparedOption,
			'canEditOptionDefinition' => $canEdit
		));

		$nodeModel = XenForo_Model::create('XenForo_Model_Node');
		$forumOptions = $nodeModel->getNodeOptionsArray($nodeModel->getAllNodes(), false, '(unspecified)');

		return $view->createTemplateObject('EWRporta2_option_template_multiSelect', array(
			'fieldPrefix' => $fieldPrefix,
			'listedFieldName' => $fieldPrefix . '_listed[]',
			'preparedOption' => $preparedOption,
			'formatParams' => $forumOptions,
			'editLink' => $editLink
		));
	}

	public static function verifyOption(array &$options, XenForo_DataWriter $dw, $fieldName)
	{
		if (empty($options))
		{
			$options[0] = 0;
		}

		return true;
	}
}