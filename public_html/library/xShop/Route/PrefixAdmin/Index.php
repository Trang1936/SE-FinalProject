<?php
class xShop_Route_PrefixAdmin_Index implements XenForo_Route_Interface
{
    public function match($routePath, Zend_Controller_Request_Http $request, XenForo_Router $router)
    {
        $components = explode('/', $routePath);
        $subPrefix = strtolower(array_shift($components));
        $subSplits = explode('.', $subPrefix);

        $controllerName = '';
        $action = '';
        $intParams = '';
        $strParams = '';
        $slice = false;

        switch ($subPrefix)
        {
        	case 'cats':		$controllerName = '_Cats';		$intParams = 'cat_id';		$title = 'cat_title';	$slice = true;		break;
            case 'items':        $controllerName = '_Items';    $intParams = 'item_id';        $slice = true;    break;
            case 'stock':        $controllerName = '_Stock';        $intParams = 'stock_id';            $slice = true;    break;
            case 'points':			$controllerName = '_Points';	$intParams = 'points_id';		$slice = true;		break;
            default :
                if (is_numeric(end($subSplits))) { $controllerName = ''; $intParams = ''; }
        }

        $routePathAction = ($slice ? implode('/', array_slice($components, 0, 2)) : $routePath).'/';
        $routePathAction = str_replace('//', '/', $routePathAction);

        if ($strParams)
        {
            $action = $router->resolveActionWithStringParam($routePathAction, $request, $strParams);
        }
        else
        {
            $action = $router->resolveActionWithIntegerParam($routePathAction, $request, $intParams);
        }

        $action = $router->resolveActionAsPageNumber($action, $request);
        return $router->getRouteMatch('xShop_ControllerAdmin_Index'.$controllerName, $action, 'xshop', $routePath);
    }

    public function buildLink($originalPrefix, $outputPrefix, $action, $extension, $data, array &$extraParams)
    {
        $components = explode('/', $action);
        $subPrefix = strtolower(array_shift($components));

        $intParams = '';
        $strParams = '';
        $title = '';
        $slice = false;

        switch ($subPrefix)
        {
        	case 'cats':		$controllerName = '_Cats';		$intParams = 'cat_id';		$title = 'cat_title';		$slice = true;		break;
            case 'items':        $controllerName = '_Items';    $intParams = 'item_id';		$title = 'item_name';	$slice = true;    break;
            case 'stock':        $controllerName = '_Stock';        $intParams = 'stock_id';	$slice = true;    break;
            case 'points':			$controllerName = '_Points';	$intParams = 'points_id';	$slice = true;		break;
            default:            $intParams = '';        $title = '';
        }

        if ($slice)
        {
            $outputPrefix .= '/'.$subPrefix;
            $action = implode('/', $components);
        }

        $action = XenForo_Link::getPageNumberAsAction($action, $extraParams);

        if ($strParams)
        {
            return XenForo_Link::buildBasicLinkWithStringParam($outputPrefix, $action, $extension, $data, $strParams);
        }
        else
        {
            return XenForo_Link::buildBasicLinkWithIntegerParam($outputPrefix, $action, $extension, $data, $intParams, $title);
        }
    }
}