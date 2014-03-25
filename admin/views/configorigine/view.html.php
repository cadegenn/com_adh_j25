<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * origine View
 * 
 */
class adhViewConfigOrigine extends JView
{
    /**
     * display method of Hello view
     * @return void
     */
    public function display($tpl = null) 
    {
        // get the Data
        $form = $this->get('Form');
        $item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) 
        {
                JError::raiseError(500, implode('<br />', $errors));
                return false;
        }
        // Assign the Data
        $this->form = $form;
        $this->item = $item;

        // Set the toolbar
        $this->addToolBar();
        // Load styleSheet
        $this->addDocStyle();
		// Load scripts
		$this->addScripts();

		// Ajouter le sous menu
		ADHHelper::addSubmenu('origines');	// => admin/helpers/adh.php
		
        // Display the template
        parent::display($tpl);		// -> ./tmpl/edit.php
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() 
    {
        $input = JFactory::getApplication()->input;
        $input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);
		
        JToolBarHelper::title($isNew ? JText::_('COM_ADH').' : '.JText::_('COM_ADH_MANAGER_ORIGINE_NEW')
                                     : JText::_('COM_ADH').' : '.JText::_('COM_ADH_MANAGER_ORIGINE_EDIT'));
        JToolBarHelper::save('configOrigine.save');                                      // --> administrator/components/com_adh/controllers/configorigine.php::save();
        JToolBarHelper::cancel('configOrigine.cancel', $isNew   ? 'JTOOLBAR_CANCEL'      // --> administrator/components/com_adh/controllers/configorigine.php::cancel();
														        : 'JTOOLBAR_CLOSE');
    }

    /**
     * Add the stylesheet to the document.
     */
    protected function addDocStyle()
    {
        $doc = JFactory::getDocument();
        $doc->addStyleSheet('components/com_adh/css/admin.css');
    }
    
    /**
     * Add the scripts to the document.
     */
    protected function addScripts()
    {
        $doc = JFactory::getDocument();
        $doc->addScript('components/com_adh/js/fonctions.js');
    }
    
}
