<?php
/**
* @version    $Id$ 
* @package    Missingt
* @copyright  Copyright (C) 2008 Julien Vonthron. All rights reserved.
* @license    GNU/GPL, see LICENSE.php
* Missingt is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.controller');

/**
 * Joomla Missingt Component Controller
 *
 * @package		Missingt
 * @since 0.1
 */
class MissingtControllerComponents extends JController
{
  function __construct()
  {
    parent::__construct();
    
		$this->registerTask( 'apply', 'save' );		
  }
  
  function display()
  {
    JRequest::setVar('view', 'components');
    parent::display();
  }

  function parse()
  {
    JRequest::setVar('view', 'component');
    JRequest::setVar('layout', 'form');
  	parent::display();
  }
	
  function export()
	{		
		JRequest::setVar('view', 'component');
		JRequest::setVar('layout', 'export');
		JRequest::setVar('format', 'raw');
		JRequest::setVar('tmpl', 'component');
		parent::display();
	}

	function cancel()
	{
		$this->setRedirect( 'index.php?option=com_missingt&view=components' );
	}
	
  function save()
	{
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		$cid = $cid[0];
  	$post = MissingtAdminHelper::getRealPOST();
		// message type for redirect
		$type = 'message';
		
		$model = $this->getModel('component');

		if ($model->store($post)) {
			$msg = JText::_( 'COM_MISSINGT_FILE_SAVED_SUCCESS' );
		} else {
			$msg = JText::_( 'COM_MISSINGT_FILE_SAVED_FAILURE' ).$model->getError();
			$type = 'error';
		}
		
		if ( $this->getTask() == 'save' ) {
			$link = 'index.php?option=com_missingt&view=components';
		}
		else {
			$link = 'index.php?option=com_missingt&controller=components&task=parse&cid[]='.$cid
			      . '&location='.JRequest::getVar('location')
			      ;
		}
		$this->setRedirect($link, $msg, $type);
		$this->redirect();
	}
}
?>
