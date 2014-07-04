<?php
/**
 * @package		com_adh
 * @subpackage	
 * @brief		controls component helper.
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
 * controls component helper.
 * 
 * host all queries/access to database outside of the model 
 * in order to build GUI controls with its values
 * e.g. when a site needs to display all categories for example
 */
abstract class ADHcontrols {
	/**
	 * connect to db
	 */

	/**
	 * @brief	buildSelectCotizTypesAnomalies()		build a 'select' DOM control to list types of abnormalitites in database
	 * @return	(string)		DOM control
	 */
	static public function buildSelectCotizTypesAnomalies($state = 1) {
		$control = "<select id='anomalies_search' name='anomalies_search' onchange='this.form.submit();'>";
		$control .= "<option value='0'>-- ".JText::_('COM_ADH_ANOMALIE_SELECT_LABEL')." --</option>";
		for ($i = 1; $i <= 2; $i++) {
			$control .= "<option value='".$i."' ".($i == $state ? "selected" : "").">".JText::_('COM_ADH_2ANOMALIE_SELECT_OPTION'.$i)."</option>";
		}
		$control .= "</select>";
		return $control;
	}
	
	/**
	 * @brief	buildSelectAdhTypesAnomalies()		build a 'select' DOM control to list types of abnormalitites in database
	 * @return	(string)		DOM control
	 */
	static public function buildSelectAdhTypesAnomalies($state = 1) {
		$control = "<select id='anomalies_search' name='anomalies_search' onchange='this.form.submit();'>";
		$control .= "<option value='0'>-- ".JText::_('COM_ADH_ANOMALIE_SELECT_LABEL')." --</option>";
		for ($i = 1; $i <= 4; $i++) {
			$control .= "<option value='".$i."' ".($i == $state ? "selected" : "").">".JText::_('COM_ADH_1ANOMALIE_SELECT_OPTION'.$i)."</option>";
		}
		$control .= "</select>";
		return $control;
	}
	
	/**
	 * extract categories of sites
	 */
	static public function selectChantiersCategories($select = 0) {
		$db = JFactory::getDbo();
		//$query = $db->getQuery(true);
		$query = "SELECT * FROM `#__adh_chantiers_categories` WHERE published = 1 ORDER BY id";
		//$result = mysql_query($query) or die('Query failed: ' . mysql_error().'<br />QUERY = '.nl2br($query));
		$db->setQuery($query);
		$db->execute();
		$rows = $db->loadAssocList();
		echo("<select id='jform_catid' name='jform[catid]'>");
		//while ($row = mysql_fetch_assoc($result)) {
		foreach ($rows as $row) {
			echo("<option value=".$row['id']." ".($row['id']==$select ? "selected" : "")." >".$row['nom']."</option>");
		}
		echo("</select>");
		//mysql_free_result($result);
	}

	/**
	 * @brief	construit le control pour sélectionner le titre d'une personne (M., Mmme, etc...)
	 * 
	 * @param	string		select	le choix sélectionné par défaut
	 * @return	control		le control complet
	 * @deprecated since version 0.0.23 use field definition instead : $this->form->getField("titre")->input
	 */
	static public function buildSelectTitres($select = "M.") {
		$control = "<select id='jform_titre' name='jform[titre]'>";
		$titres = array('Mme', 'M.', 'Association', 'Société');
		foreach ($titres as $titre) {
			$control .= "<option value='".$titre."' ".($titre == $select ? "selected" : "").">".$titre."</option>";
		}
		$control .= "</select>";
		
		return $control;
	}
	
	/**
	 * @brief	get all Years recorded into table Table
	 * @param	string $table table name to get data from
	 * @param	string $column	column name of date type
	 * @param	string [optional] order		set sort order (ASC or DESC)
	 * @param	string [optional] selected	set default value
	 * @param	string [optional] control	set control to be updated on calue change
	 * @return	select object
	 */
	static public function selectYearsFromTable($table, $column, $order = "DESC", $selected/*, $control*/) {
		$db = JFactory::getDbo();
		$query = "SELECT YEAR(".$column.") as year FROM ".$table." GROUP BY year ORDER BY year ".$order;
		$db->setQuery($query);
		$db->execute();
		$rows = $db->loadAssocList();
		//echo ("<select name='adh_select_years' id='adh_select_years' onchange='this.form.submit();'>");
		echo ("<select name='year_search' id='year_search' onchange='this.form.submit();'>");
		echo ("<option value='' ".($selected == "" ? " selected" : "")."></option>");
		foreach ($rows as $row) {
			echo ("<option value='".$row['year']."' ".($row['year'] == $selected ? " selected" : "").">".$row['year']."</option>");
		}
		echo ("</select>");
	}
	
	/**
	 * @brief	get all values from Column recorded into table Table
	 * @param	string $table table name to get data from
	 * @param	string $column	column name of date type
	 * @param	string [optional] order		set sort order (ASC or DESC)
	 * @param	string [optional] selected	set default value
	 * @param	string [optional] control	set control to be updated on calue change
	 * @return	select object
	 */
	static public function selectColumnFromTable($table, $column, $order = "DESC", $selected/*, $control*/) {
		$db = JFactory::getDbo();
		$query = "SELECT ".$column." FROM ".$table." AS t GROUP BY LOWER(".$column.") ORDER BY LOWER(".$column.") ".$order;
		$db->setQuery($query);
		$db->execute();
		$rows = $db->loadAssocList();
		echo ("<select name='".$column."_search' id='".$column."_search' onchange='this.form.submit();'>");
		echo ("<option value='' ".($selected == "" ? " selected" : "")."></option>");
		foreach ($rows as $row) {
			echo ("<option value='".$row[$column]."' ".($row[$column] == $selected ? " selected" : "").">".$row[$column]."</option>");
		}
		echo ("</select>");
	}
	
}
?>
