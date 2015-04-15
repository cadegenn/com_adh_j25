<?php

/* 
 *  Copyright (C) 2012-2015 charly
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

// no direct access
defined('_JEXEC') or die;

/**
 * Content Component HTML Helper
 *
 * @static
 * @package		Joomla.Site
 * @subpackage	com_velo
 * @since 1.5
 */
class JHtmlIcon
{
    /**
     * @brief get favicon of any site
     * 
     * @param	(string) url		url of site
     * @return	(string) url		favicon url
     * 
     */
    function getFavicon($url, $method = "google") {
        switch ($method) {
            case "google"   :   $favicon = "http://www.google.com/s2/favicons?domain=".$url;
                                break;
        }
        return $favicon;
    }

    static function create($category, $params) {
		$component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
		$view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
		$uri = JFactory::getURI();

		$url = 'index.php?option='.$component.'&view='.$view.'&view=edit&id=0&return='.base64_encode($uri);

		if ($params->get('show_icons')) {
				$text = JHtml::_('image', 'system/new.png', JText::_('JNEW'), NULL, true);
		} else {
				$text = JText::_('JNEW').'&#160;';
		}

		$button =  JHtml::_('link', JRoute::_($url), $text);

		$output = '<span class="hasTip" title="'.JText::_('COM_CONTENT_CREATE_ARTICLE').'">'.$button.'</span>';
		return $output;
    }

    static function email($item, $params, $attribs = array())
    {
            require_once JPATH_SITE . '/components/com_mailto/helpers/mailto.php';
            $uri	= JURI::getInstance();
            $base	= $uri->toString(array('scheme', 'host', 'port'));
            $template = JFactory::getApplication()->getTemplate();
            //$link	= $base.JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid) , false);
            $link	= $base.'/index.php?option=com_velo&view=monvelo&id='.$item->id;
            $url	= 'index.php?option=com_mailto&tmpl=component&template='.$template.'&link='.MailToHelper::addLink($link);

            $status = 'width=400,height=350,menubar=yes,resizable=yes';

            if ($params->get('show_icons')) {
                    $text = JHtml::_('image', 'system/emailButton.png', JText::_('JGLOBAL_EMAIL'), NULL, true);
            } else {
                    $text = '&#160;'.JText::_('JGLOBAL_EMAIL');
            }

            $attribs['title']	= JText::_('JGLOBAL_EMAIL');
            $attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";

            $output = JHtml::_('link', JRoute::_($url), $text, $attribs);
            return $output;
    }

    /**
     * Display an edit icon for the article.
     *
     * This icon will not display in a popup window, nor if the article is trashed.
     * Edit access checks must be performed in the calling code.
     *
     * @param	string	$component	the component to edit
     * @param	string	$view		the view of the component to edit
     * @param	string	$layout		the layout of the view
     * @param	object	$item		The item in question.
     * @param	object	$params		The item parameters
     * @param	array	$attribs	Not used??
     *
     * @return	string	The HTML for the article edit icon.
     * @since	1.6
     */
    static function edit($component, $view, $layout='edit', $item, $params, $attribs = array())
    {
            // Initialise variables.
            $user	= JFactory::getUser();
            $userId	= $user->get('id');
            $uri	= JFactory::getURI();

            $component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
            $view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
            //$layout = ($layout != '' ? $layout : JRequest::getVar('layout', null, 'get', 'string'));

            // Ignore if in a popup window.
            if ($params && $params->get('popup')) {
                    return;
            }

            // Ignore if the state is negative (trashed).
            if ($item->published < 0) {
                    return;
            }

            JHtml::_('behavior.tooltip');

            // Show checked_out icon if the article is checked out by a different user
            /*if (property_exists($item, 'checked_out') && property_exists($item, 'checked_out_time') && $item->checked_out > 0 && $item->checked_out != $user->get('id')) {
                    $checkoutUser = JFactory::getUser($item->checked_out);
                    $button = JHtml::_('image', 'system/checked_out.png', NULL, NULL, true);
                    $date = JHtml::_('date', $item->checked_out_time);
                    $tooltip = JText::_('JLIB_HTML_CHECKED_OUT').' :: '.JText::sprintf('COM_CONTENT_CHECKED_OUT_BY', $checkoutUser->name).' <br /> '.$date;
                    return '<span class="hasTip" title="'.htmlspecialchars($tooltip, ENT_COMPAT, 'UTF-8').'">'.$button.'</span>';
            }*/

            $url	= 'index.php?option='.$component.'&view='.$view.'&layout='.$layout.'&id='.$item->id.'&return='.base64_encode($uri);
            /*switch ($item->published) {
                    case -2	:	// DELETED => SOLD
                                            $icon = 'edit_sold.png';
                                            $overlib = JText::_('COM_VELO_PART_STATUS_TRASHED');
                                            break;
                    case 0	:	// UNPUBILSHED => IN STOCK
                                            $icon = 'edit_unpublished.png';
                                            $overlib = JText::_('COM_VELO_PART_STATUS_UNPUBLISHED');
                                            break;
                    case 1	:	// PUBILSHED => MOUNTED ON A BIKE
                                            $icon = 'edit.png';
                                            $overlib = JText::_('COM_VELO_PART_STATUS_PUBLISHED');
                                            break;
                    case 2	:	// ARCHIVED => IN THE WISHLIST
                                            $icon = 'edit_unpublished.png';
                                            $overlib = JText::_('COM_VELO_PART_STATUS_ARCHIVED');
                                            break;
                    default	:	// UNPUBILSHED
                                            $icon = 'edit_unpublished.png';
                                            $overlib = JText::_('COM_VELO_PART_STATUS_UNPUBLISHED');
                                            break;
            }
            $attribs['style'] = $item->published ? 'margin: -4px -2px 0 -2px' : 'margin: -4px -2px 0 -4px';		// default images are not 16x16px :-( and worse... are not same size
			 *
			 */
			$icon = "pencil.png";
            $text	= JHtml::_('image', 'system/'.$icon, JText::_('JGLOBAL_EDIT'), $attribs, true);

            /*if ($item->published == 0) {
                    $overlib = JText::_('JUNPUBLISHED');
            }
            else {
                    $overlib = JText::_('JPUBLISHED');
            }*/

            $date = JHtml::_('date', $item->creation_date);
            $author = $item->created_by;

            $overlib .= '&lt;br /&gt;';
            $overlib .= $date;
            $overlib .= '&lt;br /&gt;';
            $overlib .= JText::sprintf('COM_VELO_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

            $button = JHtml::_('link', JRoute::_($url), $text);

            $output = '<span class="hasTip" title="'.JText::_('JACTION_EDIT').' :: '.$overlib.'">'.$button.'</span>';

            return $output;
    }


    static function print_popup($item, $params, $attribs = array())
    {
            //$url  = ContentHelperRoute::getArticleRoute($item->slug, $item->catid);
            $url  = 'index.php?option=com_velo&view=monvelo&id='.$item->id;
            $url .= '&tmpl=component&print=1&layout=default&page='.@ $request->limitstart;

            $status = 'status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=640,height=480,directories=no,location=no';

            // checks template image directory for image, if non found default are loaded
            if ($params->get('show_icons')) {
                    $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
            } else {
                    $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JGLOBAL_PRINT') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
            }

            $attribs['title']	= JText::_('JGLOBAL_PRINT');
            $attribs['onclick'] = "window.open(this.href,'win2','".$status."'); return false;";
            $attribs['rel']		= 'nofollow';

            return JHtml::_('link', JRoute::_($url), $text, $attribs);
    }

    static function print_screen($item, $params, $attribs = array())
    {
            // checks template image directory for image, if non found default are loaded
            if ($params->get('show_icons')) {
                    $text = JHtml::_('image', 'system/printButton.png', JText::_('JGLOBAL_PRINT'), NULL, true);
            } else {
                    $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JGLOBAL_PRINT') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
            }
            return '<a href="#" onclick="window.print();return false;">'.$text.'</a>';
    }

    /**
     * @brief	method		draw icon to add bike component
     * @param type $item	bike object
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function add_component($item, $params, $attribs = array()) {
            $uri	= JFactory::getURI();
            $url  = 'index.php?option=com_velo&view=moncomposant&velo_id='.$item->id.'&layout=edit&return='.base64_encode($uri);
            $icon = 'add.png';

            if ($params->get('show_icons')) {
                    //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                    $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('COM_VELO_ADD_COMPONENT')."' title='".JText::_('COM_VELO_ADD_COMPONENT')."' />";
            } else {
                    $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('COM_VELO_ADD_COMPONENT') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
            }

            return JHtml::_('link', JRoute::_($url), $text, $attribs);
    }

    /**
     * @brief	method		draw icon to add a bike
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function new_bike($params, $attribs = array()) {
            $uri	= JFactory::getURI();
            $url  = 'index.php?option=com_velo&view=monvelo&layout=edit';
            $icon = 'velo_add.png';

            if ($params->get('show_icons')) {
                    //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                    $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('COM_VELO_ADD_VELO')."' title='".JText::_('COM_VELO_ADD_VELO')."' />";
            } else {
                    $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('COM_VELO_ADD_VELO') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
            }

            return JHtml::_('link', JRoute::_($url), $text, $attribs);
    }

    /**
     * @brief	method		draw icon to delete an item
     * @param type $item	object to delete
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function delete($component, $view, $item, $params, $attribs = array()) {
        $uri	= JFactory::getURI();
        $component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
        $view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
        $Itemid = JRequest::getVar('Itemid', 0, 'get','int');
        //$url  = 'index.php?option='.$component.'&view='.$view.'&Itemid='.$Itemid.'&id='.$item->id.'&task='.$view.'.supprimer&'.JUtility::getToken().'=1&return='.base64_encode($uri);
        $url  = 'index.php?option='.$component.'&view='.$view.'&Itemid='.$Itemid.'&id='.$item->id.'&task='.$view.'.supprimer&'.JUtility::getToken().'=1&return='.base64_encode('index.php?option='.$component.'&view='.$view);
        $icon = 'delete.png';

        if ($params->get('show_icons')) {
                //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('JACTION_DELETE')."' title='".JText::_('JACTION_DELETE')."' />";
        } else {
                $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JACTION_DELETE') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
        }

        // confirm before delete
        $attribs['onclick'] = "if (confirm('".JText::_('COM_VELO_AREYOUSURE')."')){
                href = '".$url."';
                window.location.href = encodeURIComponent(href);
        }";

        return JHtml::_('link', '#'/*JRoute::_($url)*/, $text, $attribs);
    }

    /**
     * @brief	method		draw icon to attach an item
     * @param type $item	object to attach
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function attach($component, $view, $item, $params, $attribs = array()) {
        $uri	= JFactory::getURI();
        $component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
        $view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
        $Itemid = JRequest::getVar('Itemid', 0, 'get','int');
        $url  = 'index.php?option='.$component.'&view='.$view.'&Itemid='.$Itemid.'&id='.$item->id.'&task='.$view.'.attacher&'.JUtility::getToken().'=1&return='.base64_encode($uri);
        $icon = 'cog_go.png';

        if ($params->get('show_icons')) {
                //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('JACTION_ATTACH')."' title='".JText::_('JACTION_ATTACH')."' />";
        } else {
                $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JACTION_ATTACH') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
        }

        return JHtml::_('link', JRoute::_($url), $text, $attribs);
    }

    /**
     * @brief	method		draw icon to detach an item
     * @param type $item	object to detach
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function detach($component, $view, $item, $params, $attribs = array()) {
        $uri	= JFactory::getURI();
        $component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
        $view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
        $Itemid = JRequest::getVar('Itemid', 0, 'get','int');
        $url  = 'index.php?option='.$component.'&view='.$view.'&Itemid='.$Itemid.'&id='.$item->id.'&task='.$view.'.detacher&'.JUtility::getToken().'=1&return='.base64_encode($uri);
        $icon = 'cog_delete.png';

        if ($params->get('show_icons')) {
                //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('JACTION_DETACH')."' title='".JText::_('JACTION_DETACH')."' />";
        } else {
                $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JACTION_DETACH') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
        }

        // confirm before delete
        $attribs['onclick'] = "if (confirm('".JText::_('COM_VELO_AREYOUSURE')."')){
                href = '".$url."';
                window.location.href = encodeURIComponent(href);
        }";

        return JHtml::_('link', '#'/*JRoute::_($url)*/, $text, $attribs);
    }

    /**
     * @brief	method		draw icon to duplicate an item
     * @param type $item	object to detach
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function duplicate($component, $view, $item, $params, $attribs = array()) {
        $uri	= JFactory::getURI();
        $component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
        $view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
        $Itemid = JRequest::getVar('Itemid', 0, 'get','int');
        $url  = 'index.php?option='.$component.'&view='.$view.'&Itemid='.$Itemid.'&id='.$item->id.'&task='.$view.'.dupliquer&'.JUtility::getToken().'=1&return='.base64_encode($uri);
        $icon = 'page_copy.png';

        if ($params->get('show_icons')) {
                //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('JACTION_DUPLICATE')."' title='".JText::_('JACTION_DUPLICATE')."' />";
        } else {
                $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JACTION_DUPLICATE') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
        }

        return JHtml::_('link', JRoute::_($url), $text, $attribs);
    }

    /**
     * @brief	method		draw icon to show list of items of same model_id
     * @param type $item	object to detach
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function show_list($component, $view, $item, $params, $attribs = array()) {
        $uri	= JFactory::getURI();
        $component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
        $view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
        $Itemid = JRequest::getVar('Itemid', 0, 'get','int');
        $url  = 'index.php?option='.$component.'&view='.$view.'&Itemid='.$Itemid.'&layout=default_list&id='.$item->id.'&model_id='.$item->model_id.'&return='.base64_encode($uri);
        $icon = 'application_view_detail.png';

        if ($params->get('show_icons')) {
                //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('JACTION_DETAILS')."' title='".JText::_('JACTION_DETAILS')."' />";
        } else {
                $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JACTION_DETAILS') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
        }

        return JHtml::_('link', JRoute::_($url), $text, $attribs);
    }

    /**
     * @brief	method		draw icon to put an item into stock
     * @param type $item	object to detach
     * @param type $params	component parameters (article's parameters from com_content)
     * @param type $attribs	array
     * @return type			HTML code
     */
    static function putInStock($component, $view, $item, $params, $attribs = array()) {
        $uri	= JFactory::getURI();
        $component = ($component != '' ? $component : JRequest::getVar('option', null, 'get', 'string'));
        $view = ($view != '' ? $view : JRequest::getVar('view', null, 'get', 'string'));
        $Itemid = JRequest::getVar('Itemid', 0, 'get','int');
        $url  = 'index.php?option='.$component.'&view='.$view.'&Itemid='.$Itemid.'&id='.$item->id.'&task='.$view.'.stocker&'.JUtility::getToken().'=1&return='.base64_encode($uri);
        $icon = 'lorry_go.png';

        if ($params->get('show_icons')) {
                //$text = JHtml::_('image', 'ico-16x16/'.$icon, JText::_('COM_VELO_ADD_COMPONENT'), NULL, true);
                $text = "<img src='".JURI::base()."/components/com_velo/images/ico-16x16/".$icon."' alt='".JText::_('JACTION_PUTINSTOCK')."' title='".JText::_('JACTION_PUTINSTOCK')."' />";
        } else {
                $text = JText::_('JGLOBAL_ICON_SEP') .'&#160;'. JText::_('JACTION_PUTINSTOCK') .'&#160;'. JText::_('JGLOBAL_ICON_SEP');
        }

        return JHtml::_('link', JRoute::_($url), $text, $attribs);
    }

}
