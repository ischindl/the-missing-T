<?php
/**
* @version    $Id$ 
* @package    Missingt
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
*/

class MissingtAdminHelper {

  function buildMenu()
  {
    $user = & JFactory::getUser();
    
    $view = JRequest::getVar('view');
    $controller = JRequest::getVar('controller');
    
    //Create Submenu
    JSubMenuHelper::addEntry( JText::_( 'HOME' ), 'index.php?option=com_missingt', ($view == ''));
//    JSubMenuHelper::addEntry( JText::_( 'YYYY' ), 'index.php?option=com_missingt&view=yyyy', ($view == 'yyyy'));
  }
  
  function getRealPOST() 
  {
    $pairs = explode("&", file_get_contents("php://input"));
    $vars = array();
    foreach ($pairs as $pair) {
        $nv = explode("=", $pair);
        $name = urldecode($nv[0]);
        $value = urldecode($nv[1]);
        $vars[$name] = $value;
    }
    return $vars;
	}  
}
?>