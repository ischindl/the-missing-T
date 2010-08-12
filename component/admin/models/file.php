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

jimport('joomla.application.component.model');

/**
 * Joomla Missingt Component file Model
 *
 * @author Julien Vonthron <julien.vonthron@gmail.com>
 * @package   Missingt
 * @since 0.1
 */
class MissingtModelFile extends JModel
{
 /**
   * id
   *
   * @var string
   */
  var $_id = null;

  /**
   * venue data array
   *
   * @var array
   */
  var $_data = null;

  /**
   * Constructor
   *
   * @since 0.9
   */
  function __construct()
  {
    parent::__construct();

    $array = JRequest::getVar('cid',  0, '', 'array');
    $this->setId($array[0]);
  }

  /**
   * Method to set the identifier
   *
   * @access  public
   * @param int event identifier
   */
  function setId($id)
  {
    // Set venue id and wipe data
    $this->_id      = $id;
    $this->_data  = null;
  }

  /**
   * Logic for the event edit screen
   *
   * @access public
   * @return array
   * @since 0.9
   */
  function &getData()
  {
    if (empty($this->_data))
    {
    	$res = new stdclass();
    	// original file
    	$helper = & JRegistryFormat::getInstance('INI');
			$object = $helper->stringToObject(file_get_contents($this->_id));
			$res->from = get_object_vars($object);
			
			// target file
			$to = JRequest::getVar('to', '', 'request', 'string');
			$filename = basename($this->_id);
			$pospoint = strpos($filename, '.');
			$target = $to.substr($filename, $pospoint);
			$path = dirname(dirname($this->_id)).DS.$to.DS.$target;
			if (file_exists($path))
			{
				$object = $helper->stringToObject(file_get_contents($path));
				$res->to = get_object_vars($object);
			}
			else {
				$res->to = array();
			}
			$this->_data = $res;
    }

    return $this->_data;
  }

  /**
   * Method to store the venue
   *
   * @access  public
   * @return  boolean True on success
   * @since 1.5
   */
  function store($data)
  {
    $user   = & JFactory::getUser();
    $config   = & JFactory::getConfig();

    $row  =& $this->getTable('file', 'MissingtTable');

    // bind it to the table
    if (!$row->bind($data)) {
      JError::raiseError(500, $this->_db->getErrorMsg() );
      return false;
    }

    // sanitise id field
    $row->id = (int) $row->id;

    $nullDate = $this->_db->getNullDate();

    //update item order
    if (!$row->id) {
      $row->ordering = $row->getNextOrder();
    }

    // Make sure the data is valid
    if (!$row->check()) {
      $this->setError($row->getError());
      return false;
    }

    // Store it in the db
    if (!$row->store()) {
      JError::raiseError(500, $this->_db->getErrorMsg() );
      return false;
    }

    return $row->id;
  }
}
?>
