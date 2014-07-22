<?php
/**
 * @package		com_adh
 * @subpackage	google-places
 * @brief		The Place Autocomplete service is a web service that returns place predictions in response to an HTTP request. The request specifies a textual search string and optional geographic bounds. The service can be used to provide autocomplete functionality for text-based geographic searches, by returning places such as businesses, addresses and points of interest as a user types.
 * @copyright	Copyright (C) 2010 - 2014 DEGENNES Charles-Antoine <cadegenn@gmail.com>
 * @license		Affero GNU General Public License version 3 or later; see LICENSE.txt
 * @url			https://developers.google.com/places/documentation/autocomplete
 * @url			https://developers.google.com/maps/documentation/javascript/places
 * 
 * @TODO		add "powered by google" logo on bottom of page
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

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');

$params = JComponentHelper::getParams('com_adh');
$API_KEY=$params->get("googlemap_apikey");

?>

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false"></script>

<script type="text/javascript">

// autocomplete for Adresse field
var autocompleteA;
// autocomplete for Ville field
var autocompleteV;
// autocomplete for Pays field
var automcompleteP;

/**
 * @brief	addEvent()	add custom actions when document finishes loading
 */
window.addEvent('domready',function(){
	var adresse = document.getElementById('jform_adresseAC');
	adresse.placeholder = "<?php echo JText::_('COM_ADH_ADRESSE_TIP'); ?>";
	//var cp = document.getElementById('jform_cp');
	//var ville = document.getElementById('jform_villeAC');
	//ville.placeholder = "<?php echo JText::_('COM_ADH_VILLE_DESC'); ?>";
	//var pays = document.getElementById('jform_paysAC');
	//pays.placeholder = "<?php echo JText::_('COM_ADH_PAYS_DESC'); ?>";
	
	// using autocomplete object
	autocompleteA = new google.maps.places.Autocomplete(adresse, { types: ['geocode'] });		
	google.maps.event.addListener(autocompleteA, 'place_changed', function() { getAddressResult('jform_adresseAC'); }); // jform_adresseAC: input to get value from, fillInAddress: callback function
	// using searchbox object
	//var autocompleteA = new google.maps.places.SearchBox(adresse);
	//google.maps.event.addListener(searchBox, 'places_changed', function() { fillInAddress('jform_adresseAC'); });
	//google.maps.event.addListener(autocompleteA, 'places_changed', function() { fillInAddress('jform_adresseAC'); });
	//autocompleteCP = new google.maps.places.Autocomplete(cp, { types: ['(regions)'] });
	//autocompleteV = new google.maps.places.Autocomplete(ville, { types: ['(cities)'] });
	//google.maps.event.addListener(autocompleteV, 'place_changed', function() { fillInAddress('jform_villeAC'); });
	//autocompleteP = new google.maps.places.Autocomplete(pays, { types: ['(regions)'] });
	//google.maps.event.addListener(autocompleteP, 'place_changed', function() { fillInAddress('jform_paysAC'); });
	//$('jform_ville').addEvent('change', getVilles());
});

/**
 * @brief	getAddressResult()	get the result object from an autocomplete object
 * @param	(string)			id of source object 
 * @url							https://developers.google.com/maps/documentation/javascript/reference#PlaceResult
 */
function getAddressResult(from) {
	/**
	 * @brief	fillInAddress()		fill all fields with proper address chunks
	 * @param	{object}			a 'PlaceResult' object
	 * @url		https://developers.google.com/maps/documentation/javascript/places-autocomplete#address_forms
	 * @url		https://developers.google.com/maps/documentation/javascript/examples/places-autocomplete-addressform
	 */
	function fillInAddress(place) {
	//var fillInAddress = function(place) {
		// correspondance adressType => form fields's id
		var idForm = {
			postal_code: 'jform_cp',
			locality: 'jform_ville',
			country: 'jform_pays'
		};
		// name of chunks in google response
		var componentForm = {
			street_number: 'short_name',
			route: 'long_name',
			locality: 'long_name',
			administrative_area_level_1: 'short_name',
			country: 'long_name',
			postal_code: 'short_name'
		};
		// Get each component of the address from the place details
		// and fill the corresponding field on the form.
		for (var i = 0; i < place.address_components.length; i++) {
			var addressType = place.address_components[i].types[0];
			var componentId = idForm[addressType];
			if (idForm[addressType]) {
				var val = place.address_components[i][componentForm[addressType]];
				document.getElementById(componentId).value = val;
				document.getElementById(componentId).readOnly = false;
				//document.getElementById(componentId+'AC').value = val;
			}
		}
		$('jform_adresse').value = place.formatted_address.split(",")[0];
		$('jform_adresse').readOnly = false;
	}

	var place = null;
	// Get the place details from the autocomplete object.
	switch (from) {
		case 'jform_adresseAC' :	place = autocompleteA.getPlace();
									break;
		case 'jform_villeAC' :		place = autocompleteV.getPlace();
									break;
		case 'jform_paysAC' :		place = autocompleteP.getPlace();
									break;
	}
	//var place = autocompleteA.getPlace();
	console.log("%o", place);
	// pressing enter does not create the object with all datas, user must select first occurence with mouse
	// will still get the name, so we will try to geocode it @see http://stackoverflow.com/questions/14601655/google-places-autocomplete-pick-first-result-on-enter-key
	if (!place.hasOwnProperty("address_components")) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode({"address":place.name }, function(results, status) {
			if (status === google.maps.GeocoderStatus.OK) {
				console.log("%o", results);
				place = results[0];
				fillInAddress(place);
				/*var lat = results[0].geometry.location.lat();
				var lng = results[0].geometry.location.lng();
				var placeName = results[0].address_components[0].long_name;
				var latlng = new google.maps.LatLng(lat, lng);
				$("jform_adresseAC").val(firstResult);
				*/	
			} else {
				console.log("%o", status);
			}
		});   
	} else {
		fillInAddress(place);
	}
}

/**
 * @brief	addEvent()	add custom actions when document finishes loading
 */
/*window.addEvent('domready',function(){
	//var service = new google.maps.places.AutocompleteService();
	$('jform_ville').addEvent('change', getVilles());
})

/*
 * @brief	getVilles()		ask google places's service for informations
 * @param	(string)		The text string on which to search. The Place Autocomplete service will return candidate matches based on this string and order results based on their perceived relevance.
 * @return	(array)			list of results from google
 * @since	0.0.21
 * 
 */
/*function getVilles(obj) {
	var service = new google.maps.places.AutocompleteService();
	console.log("%o", $('jform_ville'));
	console.log("%o", service);
	service.getPlacePredictions
}

/*
 * @brief	getVilles()		ask google places's service for informations
 * @param	(string)		The text string on which to search. The Place Autocomplete service will return candidate matches based on this string and order results based on their perceived relevance.
 * @return	(array)			list of results from google
 * @since	0.0.21
 * 
 * @deprecated	0.0.21		Use javascript instead. webservice give cross-domain error : No 'Access-Control-Allow-Origin' header is present on the requested resource. Origin 'http://localhost' is therefore not allowed access.
 */
function getVillesWebService() {
	var input = $('jform_ville').value;
	var type = 'locality';
	var url='https://maps.googleapis.com/maps/api/place/autocomplete/json?key=<?php echo $API_KEY; ?>&sensor=false&input=' + input + '&types=' + type;
	var request = new Request({
		url: url,
		method:'get',
		onSuccess: function(responseText){
			//document.getElementById('system-debug').innerHTML=  responseText;
			//$('jform_marque_id').empty();
			console.log("%o", responseText);
			/*Array.each(JSON.parse(responseText), function(answer, i){
				$('jform_marque_id').set('value',answer.marque_id);
			});*/
		}
	}).send();
}

/*
 * @brief	getPlaces()		ask google places's service for informations
 * @param	(string)		The text string on which to search. The Place Autocomplete service will return candidate matches based on this string and order results based on their perceived relevance.
 * @param	(string)		The types of place results to return.
 * @return	(array)			list of results from google
 * @since	0.0.21
 */
function getPlaces(input, type) {
	var url='https://maps.googleapis.com/maps/api/place/autocomplete/json?key=<?php echo $API_KEY; ?>&input=' + input + '&types=' + type;
	var request = new Request({
		url: url,
		method:'get',
		onSuccess: function(responseText){
			//document.getElementById('system-debug').innerHTML=  responseText;
			//$('jform_marque_id').empty();
			console.log("%o", responseText);
			Array.each(JSON.parse(responseText), function(answer, i){
				$('jform_marque_id').set('value',answer.marque_id);
			});
		}
	}).send();
}

</script>
