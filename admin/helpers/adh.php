<?php
/**
 * @package		com_adh
 * @subpackage	com_adh
 * @brief		helper
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
defined('_JEXEC') or die;
 
/**
 * adh component helper.
 */
abstract class ADHHelper {
	
	/*
	 * @var	$params		parameters of component
	 */
	protected static $params;
	/**
	 * @brief	__construct()	constructor of the class
	 */
	public function __construct() {
		self::$params = JComponentHelper::getParams('com_adh');
	}
	
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
			case 'anomalies' :	JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_ANOMALIES_1'), 'index.php?option=com_adh&view=1anomalies', true);
								JSubMenuHelper::addEntry(JText::_('COM_ADH_SUBMENU_ANOMALIES_2'), 'index.php?option=com_adh&view=2anomalies', true);
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
	
	/**
	 * @brief	get all the tarifs availables
	 * 
	 */
	static function getTarifs() {
		// Create a new query object.		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		// Select some fields
		$query->select('*');
		// From the hello table
		$query->from('#__adh_tarifs');
		$query->where('published = 1');
		$query->order('tarif ASC');
		
		$db->setQuery($query);
		$db->execute();
		return $db->loadObjectList();
	}

	/**
	 * @brief	buildBulletinAdhesionUser()		build a membership form suitable to be send by email. build only user part.
	 * @param	(int)		$userId				user's id from the database
	 * @return	(string)	$body				body of the email in HTML format
	 */
	public static function buildBulletinAdhesionUser($userId) {
		$user = new AdhUser($userId);
		self::$params = JComponentHelper::getParams('com_adh');
		
		$body = "";
		//$body = '<pre>'.var_dump($user).'</pre>';
		$body .= '<h2>'.JText::_('COM_ADH_BULLETIN_ADHESION_TXT').'</h2>';
		$body .= '<table>';
		if ($user->personne_morale) {
			$body .= '<tr><th align="left">'.  JText::_('COM_ADH_PERS_MORALE_LABEL').'</th><td>'.$user->personne_morale.'</td></tr>';
		}
		$body .= '<tr><th align="left">'.  JText::_('COM_ADH_NOM_LABEL').'</th><td>'.strtoupper($user->nom).'</td><th align="left">'.  JText::_('COM_ADH_PRENOM_LABEL').'</th><td>'.$user->prenom.'</td>';
		$myDateTime = DateTime::createFromFormat('Y-m-d', $user->date_naissance);
		// 2015.04.25, DCA code below do not work !
		//$myDateTime = new JDate();
		//$myDateTime->createFromFormat('Y-m-d', $user->date_naissance);
		$body .= '<tr><th align="left">'.  JText::_('COM_ADH_DATE_NAISSANCE_LABEL').'</th><td>'.$myDateTime->format('d/m/Y').'</td><th align="left">'.  JText::_('COM_ADH_PROFESSION_LABEL').'</th><td>'.$user->profession->label.'</td>';
		$body .= '<tr><th align="left">'.  JText::_('COM_ADH_EMAIL_LABEL').'</th><td>'.$user->email.'</td></tr>';
		$body .= '<tr><th align="left">'.  JText::_('COM_ADH_TELEPHONE_LABEL').'</th><td>'.$user->telephone.'</td><th align="left">'.  JText::_('COM_ADH_GSM_LABEL').'</th><td>'.$user->gsm.'</td>';
		$body .= '<tr><th align="left" style="vertical-align: top;">'.  JText::_('COM_ADH_ADRESSE_LABEL').'</th><td>'.$user->adresse.'<br />'.($user->adresse2 != '' ? $user->adresse2.'<br />' : '').$user->cp.' '.$user->ville.' '.$user->pays.'</td></tr>';
		$body .= '</table><br /><br />';
		
		//$body .= '<pre>'.var_dump(self::$params).'</pre>';
		$body .= JText::sprintf('COM_ADH_ADHERER_ORIGINE_TXT', "<span class='apl'>".self::$params->get('nom_assoc')."</span>")."<br />";
		$body .= '<span style="padding-left: 50px;">'.$user->origine->label.' / '.$user->origine_text.'</span><br /><br />';
		
		$body .= '<input type="checkbox" disabled="disabled" '.($user->imposable ? 'checked="checked"' : '').'/> '.JText::_('COM_ADH_ADHERER_IMPOSABLE_LABEL').'<br />';
		$body .= '<small>'.JText::_('COM_ADH_ADHERER_IMPOSABLE_DESC').'</small><br /><br />';
		
		return $body;
	}
	
	/**
	 * @brief	buildBulletinAdhesionCotiz()	build a membership form suitable to be send by email. build only tarifs part.
	 * @param	(int)		$cotizId			cotisation's id from the database
	 * @return	(string)	$body				body of the email in HTML format
	 */
	public static function buildBulletinAdhesionCotiz($cotizId) {
		$cotiz = new AdhCotiz($cotizId);
		self::$params = JComponentHelper::getParams('com_adh');
		
		$body = "";
		//$body = '<pre>'.var_dump($cotiz).'</pre>';
		$body .= '<span>'.JText::sprintf('COM_ADH_SOUTENIR_TXT', "<span class='apl'>".self::$params->get('nom_assoc')."</span>")."</span><br />";
		$tarifs = self::getTarifs();
		$body .= '<table>';
		foreach($tarifs as $i => $tarif) {
			if ($tarif->id == $cotiz->tarif_id) { $checked = 'checked="checked"'; $style = "font-weight: bold;"; } else { $checked = ''; $style = "font-weight: normal;"; }
			$body .= '<tr>';
			$body .= '<td><input type="radio" value="'.$tarif->id.'" disabled="disabled" '.$checked.' > <label style="'.$style.'">'.$tarif->label.'</label></td>';
			if (substr($tarif->label,strlen($tarif->label)-3,3)=="...") { // => signifie que ce montant comporte un seuil minimum, que l'on peut augmenter
				$body .= '<td align="right"><small>('.$tarif->tarif.' '.$tarif->symbol.')</small> <label style="'.$style.'">'.$cotiz->montant.' '.$tarif->symbol.'</label></td>';
			} else {
				$body .= '<td align="right"><label style="'.$style.'">'.$tarif->tarif.' '.$tarif->symbol.'</label></td>';
			}
			$body .= '</tr>';
		}
		$body .= '</table><br />';
		
		return $body;
	}
	
	/**
	 * @brief	buildBulletinAdhesionConfirm()	build a membership form suitable to be send by email. build only confirmation part.
	 * @param	(int)		$cotizId			cotisation's id from the database
	 * @return	(string)	$body				body of the email in HTML format
	 */
	public static function buildBulletinAdhesionConfirm($cotizId) {
		$cotiz = new AdhCotiz($cotizId);
		$contacts = JContact::getFeatured();
		$contact = $contacts[0];
		self::$params = JComponentHelper::getParams('com_adh');
		
		$body = "<div class='enveloppe'";
		switch ($cotiz->mode_paiement) {
			case 'chèque' :		$body .= '<span>'.JText::sprintf('COM_ADH_ADHERER_REGLEMENT_CHEQUE_DESC', "<span class='apl'>".self::$params->get('nom_assoc')."</span>");
								$body .= JText::_('COM_ADH_ADHERER_REGLEMENT_CHEQUE_DESC2')."</span><br /><br /></br />";
								$body .= '<address>'.$contact->name.'<br />'.$contact->address.'<br />'.$contact->postcode.' '.$contact->suburb.' '.$contact->country.'</address><br />';
								break;
			case 'virement' :	$body .= '<span>'.JText::sprintf('COM_ADH_ADHERER_REGLEMENT_VIREMENT_DESC',  JRoute::_('index.php?option=com_contact&view=contact&id='.$contact->id));
								$body .= JText::_('COM_ADH_ADHERER_REGLEMENT_VIREMENT_DESC2')."</span><br /><br /></br />";
								break;
		}
		
		$body .= "</div>";
		$body .= "<br />".JText::_('JDate').' '.JFactory::getDate();
		$body .= "<br />";
		$body .= "<br /><small>user agent : ".$_SERVER['HTTP_USER_AGENT']."</small>";
		
		return $body;
	}
}
?>
