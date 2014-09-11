<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		com_adh helps you manage the people within an association
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * 
 * @TODO		
 */

/** 
 *  Copyright (C) 2012-2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 *  com_adh is a joomla! 2.5 component [http://www.volontairesnature.org]
 *  
 *  This file is part of com_adh.
 * 
 *     com_adh is free software: you can redistribute it and/or modify
 *     it under the terms of the Affero GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     com_adh is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     Affero GNU General Public License for more details.
 * 
 *     You should have received a copy of the Affero GNU General Public License
 *     along with com_adh.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
 
/**
 * 1anomalie Controller
 */
class adhController1anomalie extends JControllerForm {

	/**
	 * Method to save a record. taken from JControllerForm::save() function
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   11.1
	 */
	public function save($key = null, $urlVar = null)
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		//echo("<pre>"); var_dump($_POST); echo("</pre>");
		//echo("<pre>"); var_dump($_GET); echo("</pre>");
		//die();
		
		// Initialise variables.
		$app   = JFactory::getApplication();
		$lang  = JFactory::getLanguage();
		$model = $this->getModel();
		$table = $model->getTable();
		$recordUser1Id = JRequest::getVar('user1id', 0, 'get', 'int');
		$recordUser2Id = JRequest::getVar('user2id', 0, 'get', 'int');
		$checkin = property_exists($table, 'checked_out');
		$context = "$this->option.edit.$this->context";
		$task = $this->getTask();

		// first try to retrieve jform1 data
		$data  = JRequest::getVar('jform1', array(), 'post', 'array');
		// if $data is null, try to getch data from jform2
		if (!empty($data)) {
			//$data  = JRequest::getVar('jform1', array(), 'post', 'array');
			// set correct record's id
			$recordId = $recordUser1Id;
			// set correct context
			$context .= ".user1";
		} else {
			$data  = JRequest::getVar('jform2', array(), 'post', 'array');
			// set correct record's id
			$recordId = $recordUser2Id;
			// set correct context
			$context .= ".user2";
		}

		//echo("<pre>"); var_dump($urlVar); echo("</pre>");
		//echo("<pre>"); var_dump($data); echo("</pre>");
		//die();

		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		//$recordId = JRequest::getInt($urlVar);

		if (!$this->checkEditId($context, $recordId))
		{
			// Somehow the person just went to the form and tried to save it. We don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $recordId));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Populate the row id from the session.
		$data[$key] = $recordId;

		// The save2copy task needs to be handled slightly differently.
		if ($task == 'save2copy')
		{
			// Check-in the original row.
			if ($checkin && $model->checkin($data[$key]) === false)
			{
				// Check-in failed. Go back to the item and display a notice.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');

				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);

				return false;
			}

			// Reset the ID and then treat the request as for Apply.
			$data[$key] = 0;
			$task = 'apply';
		}

		// Access check.
		if (!$this->allowSave($data, $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_SAVE_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Validate the posted data.
		// Sometimes the form needs some posted data, such as for plugins and modules.
		$form = $model->getForm($data, false);

		if (!$form)
		{
			$app->enqueueMessage($model->getError(), 'error');

			return false;
		}

		// Test whether the data is valid.
		$validData = $model->validate($form, $data);

		// Check for validation errors.
		if ($validData === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState($context . '.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		// Attempt to save the data.
		if (!$model->save($validData))
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Redirect back to the edit screen.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		// Save succeeded, so check-in the record.
		if ($checkin && $model->checkin($validData[$key]) === false)
		{
			// Save the data in the session.
			$app->setUserState($context . '.data', $validData);

			// Check-in failed, so go back to the record and display a notice.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
					. $this->getRedirectToItemAppend($recordId, $urlVar), false
				)
			);

			return false;
		}

		$this->setMessage(
			JText::_(
				($lang->hasKey($this->text_prefix . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS')
					? $this->text_prefix
					: 'JLIB_APPLICATION') . ($recordId == 0 && $app->isSite() ? '_SUBMIT' : '') . '_SAVE_SUCCESS'
			)
		);

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the record data in the session.
				$recordId = $model->getState($this->context . '.id');
				$this->holdEditId($context, $recordId);
				$app->setUserState($context . '.data', null);
				$model->checkout($recordId);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
						. $this->getRedirectToItemAppend($recordId, $urlVar), false
					)
				);
				break;

			case 'save2new':
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect back to the edit screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
						. $this->getRedirectToItemAppend(null, $urlVar), false
					)
				);
				break;

			default:
				// Clear the record id and data from the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState($context . '.data', null);

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . '&view=' . $this->view_list
						. $this->getRedirectToListAppend(), false
					)
				);
				break;
		}

		// Invoke the postSave method to allow for the child class to access the model.
		$this->postSaveHook($model, $validData);

		return true;
	}
	
	/**
	 * Method to move existing cotisation record to another adherent
	 * 
	 */
	public function moveCotiz() {
		// Initialise variables.
		$model = $this->getModel();
		$recordUser1Id = JRequest::getVar('user1id', 0, 'get', 'int');
		$recordUser2Id = JRequest::getVar('user2id', 0, 'get', 'int');
		$adherent_id = JRequest::getVar('adherent_id', 0, 'post', 'int');
		$cid = JRequest::getVar('cid', array(), 'post', 'array');

		if ($adherent_id == $recordUser1Id) {
			// user ask to move cotiz from user1 to user2
			$moved = $model->moveCotiz($cid, $recordUser2Id);
		}
		if ($adherent_id == $recordUser2Id) {
			// user ask to move cotiz from user2 to user1
			$moved = $model->moveCotiz($cid, $recordUser1Id);
		}
		
		if ($moved > 0) {
			$this->setMessage(JText::sprintf('COM_ADH_N_ITEMS_MOVED', $moved, count($cid)));
		} else {
			// delete failed, display a notice but allow the user to see the record.
			$this->setError(JText::sprintf('COM_ADH_APPLICATION_DELETE_FAILED', $recordId, $model->getError()));
			$this->setMessage($this->getError(), 'error');
		}
		
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
				. $this->getRedirectToItemAppend($recordUser1Id, $urlVar), false
			)
		);

		return true;
	}
	
	/*
	 * Method to delete a cotiz
	 */
	public function deleteCotiz() {
		// Initialise variables.
		$model = $this->getModel();
		$table = $model->getTable();
		$recordUser1Id = JRequest::getVar('user1id', 0, 'get', 'int');
		$recordUser2Id = JRequest::getVar('user2id', 0, 'get', 'int');
		$cotiz_id = JRequest::getVar('cotiz_id', 0, 'get', 'int');

		if ($model->deleteCotiz($cotiz_id)) {
			$this->setMessage(JText::sprintf('COM_ADH_N_ITEMS_DELETED', 1));
		} else {
			// delete failed, display a notice but allow the user to see the record.
			$this->setError(JText::sprintf('COM_ADH_APPLICATION_DELETE_FAILED', $recordId, $model->getError()));
			$this->setMessage($this->getError(), 'error');
		}
		
		$this->setRedirect(
			JRoute::_(
				'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
				. $this->getRedirectToItemAppend($recordUser1Id, $urlVar), false
			)
		);

		return true;
	}
	
	/**
	 * Method to merge existing record. taken from JControllerForm::edit() function
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 * (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   11.1
	 */
	public function merge($key = null, $urlVar = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$table = $model->getTable();
		$cid = JRequest::getVar('cid', array(), 'post', 'array');
		$context = "$this->option.merge.$this->context";

		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$key = $table->getKeyName();
		}

		// To avoid data collisions the urlVar may be different from the primary key.
		if (empty($urlVar))
		{
			$urlVar = $key;
		}

		// Get the previous record id (if any) and the current record id.
		$recordUser1Id = (int) (count($cid) ? $cid[0] : JRequest::getInt($urlVar));
		$recordUser2Id = (int) (count($cid) ? $cid[1] : JRequest::getInt($urlVar));
		$checkin = property_exists($table, 'checked_out');

		// Access check.
		if (!$this->allowEdit(array($key => $recordUser1Id), $key))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_EDIT_NOT_PERMITTED'));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);

			return false;
		}

		// Attempt to check-out the new record for editing and redirect.
		if ($checkin && !$model->checkout($recordUser1Id))
		{
			// Check-out failed, display a notice but allow the user to see the record.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_CHECKOUT_FAILED', $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
					. $this->getRedirectToItemAppend($recordUser1Id, $urlVar), false
				)
			);

			return false;
		}
		else
		{
			// Check-out succeeded, push the new record id into the session.
			$this->holdEditId($context, $recordUser1Id);
			$this->holdEditId($context, $recordUser2Id);
			$app->setUserState($context . '.data', null);

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
					. $this->getRedirectToItemAppend($recordUser1Id, $urlVar), false
				)
			);

			return true;
		}
	}
	
	public function deleteUser() {
		// Initialise variables.
		$model = $this->getModel();
		$recordUser1Id = JRequest::getVar('user1id', 0, 'get', 'int');
		$recordUser2Id = JRequest::getVar('user2id', 0, 'get', 'int');
		$context = "$this->option.edit.$this->context";

		// first try to retrieve jform1 data
		$data  = JRequest::getVar('jform1', array(), 'post', 'array');
		// if $data is null, try to getch data from jform2
		if (!empty($data)) {
			//$data  = JRequest::getVar('jform1', array(), 'post', 'array');
			// set correct record's id
			$recordId = $recordUser1Id;
			// set correct context
			$context .= ".user1";
		} else {
			$data  = JRequest::getVar('jform2', array(), 'post', 'array');
			// set correct record's id
			$recordId = $recordUser2Id;
			// set correct context
			$context .= ".user2";
		}
		
		if ($model->deleteUser($recordId)) {
			$this->setMessage(JText::sprintf('COM_ADH_N_ITEMS_DELETED', 1));
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list
					. $this->getRedirectToListAppend(), false
				)
			);
		} else {
			// delete failed, display a notice but allow the user to see the record.
			$this->setError(JText::sprintf('COM_ADH_APPLICATION_DELETE_FAILED', $recordId, $model->getError()));
			$this->setMessage($this->getError(), 'error');

			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_item . '&user1id='.$recordUser1Id.'&user2id='.$recordUser2Id
					. $this->getRedirectToItemAppend($recordUser1Id, $urlVar), false
				)
			);
		}
		
	}
}
