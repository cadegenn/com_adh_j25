/* 
 *  Copyright (C) 2012-2014 charly
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

/**
 * @see	http://phpjs.org/functions/addslashes:303
 */
function addslashes (str) {
    // Escapes single quote, double quotes and backslash characters in a string with backslashes  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/addslashes
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Ates Goral (http://magnetiq.com)
    // +   improved by: marrtins
    // +   improved by: Nate
    // +   improved by: Onno Marsman
    // +   input by: Denny Wardhana
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   improved by: Oskar Larsson H�gfeldt (http://oskar-lh.name/)
    // *     example 1: addslashes("kevin's birthday");
    // *     returns 1: 'kevin\'s birthday'
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}
function stripslashes (str) {
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Ates Goral (http://magnetiq.com)
    // +      fixed by: Mick@el
    // +   improved by: marrtins
    // +   bugfixed by: Onno Marsman
    // +   improved by: rezna
    // +   input by: Rick Waldron
    // +   reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +   input by: Brant Messenger (http://www.brantmessenger.com/)
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: stripslashes('Kevin\'s code');
    // *     returns 1: "Kevin's code"
    // *     example 2: stripslashes('Kevin\\\'s code');
    // *     returns 2: "Kevin\'s code"
    return (str + '').replace(/\\(.?)/g, function (s, n1) {
        switch (n1) {
        case '\\':
            return '\\';
        case '0':
            return '\u0000';
        case '':
            return '';
        default:
            return n1;
        }
    });
}

/**
 * @brief	check if element exist in DOM
 */
//var elementExist = function( el ) { while ( el = el.parentNode ) if ( el === document ) return true; return false; }
/*var element =  document.getElementById('elementId');
if (typeof(element) != 'undefined' && element != null)
{
  // exists.
}*/
function elementExist(elementId) {
	var element = document.getElementById(elementId);
	if (typeof(element) != 'undefined' && element != null) {
		//console.log("element "+elementId+" is NOT undefined NOR null.")
		return true;
	}
	//console.log("element "+elementId+" IS undefined OR null.")
	return false;
}

/**
 * Method to copy content of a whole container to another
 * 
 * @param	{string}	src		id of source container
 * @param	{string}	tgt		id of target container
 * @param	{string}	srctag	filter source elements on id
 * @param	{string}	tgttag	apply change to objects with id tgttag
 * 
 */
function adh_details_copy(src, tgt, srctag, tgttag) {
	var srcObj = document.getElementById(src);
	var tgtObj = document.getElementById(tgt);
	
	var els = srcObj.getElementsByTagName('*');
	for (var i = els.length; i--;) {
		var elsrc = els[i];
		if (elsrc.id.search(srctag) > -1) {
			//console.dir(elsrc);
			var eltgt = tgtObj.getElementById(elsrc.id.replace(srctag, tgttag));
			//console.dir(eltgt);
			switch (elsrc.tagName) {
				case "INPUT" :	// we need to handle radio buttons differently
								if (elsrc.type == "radio") {
									eltgt.checked = elsrc.checked;
								} else {
									eltgt.value = elsrc.value;
								}
								//console.dir(eltgt);
								break;
				case "SELECT":	eltgt.selectedIndex = elsrc.selectedIndex;
								break;
				default :		//console.dir(elsrc);
								break;
			}
		}
	}
}