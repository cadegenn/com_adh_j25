<?php
// No direct access to this file
defined('_JEXEC') or die;
 
/**
 * adh component helper.
 */
abstract class ADHHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		switch ($submenu) {
			case 'adherents' :
			case 'cotisations':	JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_ADHERENTS'), 'index.php?option=com_adh&view=adherents', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_COTISATIONS'), 'index.php?option=com_adh&view=cotisations', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_GROUPES'), 'index.php?option=com_adh&view=groupes', true);
								break;
			case 'extractions':	JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_EXTRACTIONS_EMAILS'), 'index.php?option=com_adh&view=extractEmails', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_EXTRACTIONS_ADDRESS'), 'index.php?option=com_adh&view=extractAddresses', true);
								break;
			case 'config'	 :	JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_CONFIG_ORIGINES'), 'index.php?option=com_adh&view=configOrigines', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_CONFIG_PROFESSIONS'), 'index.php?option=com_adh&view=configProfessions', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_CONFIG_TARIFS'), 'index.php?option=com_adh&view=configTarifs', true);
								break;
			case 'import'	 :	JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_IMPORT_ADHERENTS'), 'index.php?option=com_adh&view=importV2Adherents', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_IMPORT_ORIGINES'), 'index.php?option=com_adh&view=importV2Origines', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_IMPORT_PROFESSIONS'), 'index.php?option=com_adh&view=importV2Professions', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_IMPORT_TARIFS'), 'index.php?option=com_adh&view=importV2Tarifs', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_IMPORT_COTISATIONS'), 'index.php?option=com_adh&view=importV2Cotisations', true);
								break;
		}
		// set some global property
		/*$document = JFactory::getDocument();
		//$document->addStyleDeclaration('.icon-48-categories ' .
		//                               '{background-image: url(../components/com_adh/images/ico-48x48/adh.png);}');
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_ADH_ADMINISTRATION_CATEGORIES'));
		}*/
	}
}
?>
