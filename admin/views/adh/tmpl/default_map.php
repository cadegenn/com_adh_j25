<?php

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

$params = JComponentHelper::getParams('com_adh');
switch ($params->get("map_provider")) {
	case 'googlemap-v3'	:	require(JPATH_COMPONENT_ADMINISTRATOR . '/js/googlemap-v3.php');
							break;
	case 'osm-leaflet'	:	require(JPATH_COMPONENT_ADMINISTRATOR . '/js/osm-leaflet.php');
							break;
}
?>

<script type="text/javascript">

/**
 * TRES IMPORTANT !!
 * le 'domready' s'exécute AVANT le 'windows.onload'
 * Il faut que les scripts google soient chargés AVANT d'initialiser quoi que ce soit (une carte, un marker, etc...)
 */
window.addEvent('domready',function(){
	<?php switch ($params->get("map_provider")) :
		case 'googlemap-v3'	: ?>
			/*var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = "http://maps.googleapis.com/maps/api/js?key=<?php echo $GOOGLEMAP_API_KEY; ?>&sensor=false&callback=initialize";
			document.body.appendChild(script);*/
			//loadScript();
		<?php break;
		case 'osm-leaflet'	: ?>
			initialize();
		<?php break;
	endswitch; ?>
});

function loadScript() {
	//mgr = new MarkerManager(map);
	<?php foreach($this->adherents as $i => $item) : ?>
			createMarkerFromAddress("<?php echo($item->address); ?>", '', "<?php echo(addslashes($item->nom)); ?>", '', 'index.php?option=<?php echo JRequest::getVar('option', '0', 'get', 'string'); ?>&view=adherent&layout=edit&id=<?php echo $item->id; ?>', false);
	<?php endforeach; ?>
	//map.addControl(new GLargeMapControl3D());
	//mgr.addMarkers(markers, 1);
	//mgr.refresh();
}

window.onload = loadScript;

</script>

<a name='map' id='map'></a>
<div id='map_canvas' height='100%'></div><br />

