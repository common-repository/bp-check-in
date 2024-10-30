jQuery(document).ready(function ($) {
	'use strict';

	var autocomplete1;
	var autocomplete2;
	function initialize() {
		if (navigator.geolocation) {

			var options = {
				enableHighAccuracy: true,
				timeout: 5000,
				maximumAge: 0
			};

			navigator.geolocation.getCurrentPosition(success, error, options);
		} else {
			x.innerHTML = "Geolocation is not supported by this browser.";
		}

		var loc_xprof = document.getElementById(bpchk_public_js_obj.bpchk_loc_xprof);
		if (loc_xprof) {
			var autocomplete3 = new google.maps.places.Autocomplete(loc_xprof);
			google.maps.event.addListener(
				autocomplete3, 'place_changed', function () {
					var place3 = autocomplete3.getPlace();
					var latitude3 = place3.geometry.location.lat();
					var longitude3 = place3.geometry.location.lng();
					bpchk_loc_xprof_ajax_save(latitude3, longitude3);
				}
			);
		}
	}
	function error(e) {
		alert(e.message);
		console.log("error code:" + e.code + 'message: ' + e.message);
	}
	function success(position) {
		var lat = position.coords.latitude;
		var lng = position.coords.longitude;

		var myLocation = new google.maps.LatLng(lat, lng);

		var mapOptions = {
			center: myLocation,
			zoom: 13,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		/*start google map api code*/
		if (document.getElementById('checkin-by-autocomplete-map')) {
			var map = new google.maps.Map(
				document.getElementById('checkin-by-autocomplete-map'),
				mapOptions
			);

			var marker = new google.maps.Marker(
				{
					position: myLocation,
					map: map,
					title: "you are here",
					draggable: true,
					
				}
			);
			map.setZoom(11);
				
			reverseGeocoder(map, marker, myLocation);

			google.maps.event.addListener(marker, 'dragend', function (evt) {
				jQuery("#bpchk-checkin-place-lat").val(evt.latLng.lat().toFixed(6));
				jQuery("#bpchk-checkin-place-lng").val(evt.latLng.lng().toFixed(6));
				map.panTo(evt.latLng);
				reverseGeocoder(map, marker, evt.latLng);
			});

			// Create the search box and link it to the UI element.
			var input = document.getElementById('bpchk-autocomplete-place');
			var searchBox = new google.maps.places.SearchBox(input);
			// map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
			// Bias the SearchBox results towards current map's viewport.
			map.addListener(
				'bounds_changed', function () {
					searchBox.setBounds(map.getBounds());
				}
			);

			var markers = [];
			// Listen for the event fired when the user selects a prediction and retrieve
			// more details for that place.
			searchBox.addListener(
				'places_changed', function () {
					var places = searchBox.getPlaces();

					if (places.length == 0) {
						return;
					}

					// Clear out the old markers.
					markers.forEach(
						function (marker) {
							marker.setMap(null);
						}
					);
					markers = [];

					// For each place, get the icon, name and location.
					var bounds = new google.maps.LatLngBounds();
					places.forEach(
						function (place) {
							if (!place.geometry) {
								console.log("Returned place contains no geometry");
								return;
							}
							var icon = {
								url: place.icon,
								size: new google.maps.Size(71, 71),
								origin: new google.maps.Point(0, 0),
								anchor: new google.maps.Point(17, 34),
								scaledSize: new google.maps.Size(25, 25)
							};

							// Create a marker for each place.
							markers.push(
								new google.maps.Marker(
									{
										map: map,
										icon: icon,
										title: place.name,
										position: place.geometry.location
									}
								)
							);

							if (place.geometry.viewport) {
								// Only geocodes have viewport.
								bounds.union(place.geometry.viewport);
							} else {
								bounds.extend(place.geometry.location);
							}

							var latitude1 = place.geometry.location.lat();
							var longitude1 = place.geometry.location.lng();
							$('#bpchk-checkin-place-lat').val(latitude1);
							$('#bpchk-checkin-place-lng').val(longitude1);							
						}
					);
					map.fitBounds(bounds);
				}
			);
			/*end google map api code*/
		}
	}
	// google.maps.event.addDomListener(window, 'load', initialize);


	function reverseGeocoder(map, marker, latlng){
		let address = document.getElementById('bpchk-autocomplete-place');
		const geocoder = new google.maps.Geocoder();
		const infowindow = new google.maps.InfoWindow();

		geocoder.geocode({ location: latlng }).then((response) => {
			if (response.results[0]) {
				// centers the map on markers coords
				map.setCenter(marker.position);
				// adds the marker on the map
				marker.setMap(map);
				infowindow.close();
				infowindow.setContent(response.results[0].formatted_address);				
				infowindow.open(map, marker);
				address.value = response.results[0].formatted_address;
				jQuery('#bpchk-checkin-place-lat').val(latlng.lat());
				jQuery('#bpchk-checkin-place-lng').val(latlng.lng());

			} else {
				window.alert("No results found");
			}
		}).catch((e) => console.log("Geocoder failed due to: " + e));
		
	}

	function bpchk_loc_xprof_ajax_save(latitude3, longitude3) {

		var place = $('#' + bpchk_public_js_obj.bpchk_loc_xprof).val();

		var data = {
			'action': 'bpchk_save_xprofile_location',
			'place': place,
			'latitude': latitude3,
			'longitude': longitude3
		}

		$.ajax(
			{
				dataType: "JSON",
				url: bpchk_public_js_obj.ajaxurl,
				type: 'POST',
				data: data,
				nonce: bpchk_public_js_obj.bpchk_ajax_nonce,
				success: function (response) {

				},
			}
		);
	}

	// Open the tabs - my places
	var acc = document.getElementsByClassName("bpchk-myplace-accordion");
	var i;
	for (i = 0; i < acc.length; i++) {
		acc[i].onclick = function () {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight) {
				panel.style.maxHeight = null;
			} else {
				panel.style.maxHeight = panel.scrollHeight + "px";
			}
		}
	}
	$(document).on(
		'click', '.bpchk-myplace-accordion', function () {
			return false;
		}
	);

	$('#bpchk-add-place-visit-date').datepicker({ dateFormat: 'yy-mm-dd', minDate: 0 });

	// Open the checkin panel when clicked
	$(document).on(
		'click', '.bpchk-allow-checkin', function () {
			$('.bp-checkin-panel').slideToggle(500);
			$('.bpquotes-bg-selection-div').hide();
			$( '.bpolls-polls-option-html' ).hide();
			window.onload = initialize();
			
			if ( $('.quote-btn').length != 0 ) {
				$( '.bg-type-input' ).val( '' );
				$( '.bg-type-value' ).val( '' );
				$( '#whats-new, #bppfa-whats-new' ).removeClass( 'quotesimg-bg-selected' );
				$( '#whats-new, #bppfa-whats-new' ).removeClass( 'quotescolors-bg-selected' );
				$( "#whats-new, #bppfa-whats-new" ).css( "background-image", '' );
				$( "#whats-new, #bppfa-whats-new" ).css( "background", '' );
				$( "#whats-new, #bppfa-whats-new" ).css( "color", '' );
				$( '.bpquotes-selection' ).css( 'pointerEvents', 'auto' );
			}
			if ( $( '.bpolls-input').legnth != 0 ) {
				$( '.bpolls-input').each(function(){
					$(this).val('');
				});
			}
		}
	);
	
	
	// Show the places panel
	$(document).on(
		'click', '#bpchk-show-places-panel', function () {
			$('.bpchk-places-fetched').slideToggle(500);
			// $('.bpchk-places-fetched, #bpchk-add-as-place, #bpchk-add-my-place-label').show();
		}
	);

	$(
		function () {
			$("#accordion").accordion({ collapsible: true, heightStyle: "content" });
		}
	);

	$("#aw-whats-new-submit").click(
		function () {
			$('.bpchk-select-place-to-checkin').each(
				function () {
					$(this).html('Select this location');
				}
			);
			$('.bpchk-checkin-temp-location').remove();
			if ($('.bp-checkin-panel').is(':visible')) {
				$('.bp-checkin-panel').slideToggle(500);
			}
			if ($('.bpchk-places-fetched').is(':visible')) {
				$('.bpchk-places-fetched').slideToggle(500);
			}

		}
	);

	jQuery(document).on('click', '.bpcp-checkin-trash', function (e) {
		var parent_id = jQuery(this).parent().parent().attr('id');
		var attr_key = jQuery(this).attr('attr-key');
		jQuery.post(
			ajaxurl,
			{
				'action': 'bpchk_delete_user_checkin_location',
				'checkin_id': attr_key,
				success: function (response) {
					jQuery('#' + parent_id).next().remove();
					jQuery('#' + parent_id).remove();
					location.reload();
				},
			}
		);
	});

	$(document).on('focus', '#whats-new, #bppfa-whats-new', function () {

		if ($(".rtmedia-plupload-container .bpchk-marker-container").length == 0 && $(".rtmedia-plupload-container").length != 0) {
			// $(".bpchk-marker-container").appendTo(".rtmedia-plupload-container");

		}
		if ($(".rtmedia-plupload-container").length != 0) {
			// $('#whats-new-options .bpchk-marker-container').hide();
		}
		//$( '.bp-checkin-panel' ).appendTo('#whats-new-options');

		jQuery('.bpchk-marker-container').click(function () {
			jQuery('.bpquotes-bg-selection-div').hide();
			jQuery('.bpolls-polls-option-html').hide();
		});
	});

	/**
	 * Manage map icon with BuddyBoss Plateform
	 */
	$(document).on('click focus', '#whats-new', function () {
		if (bpchk_public_js_obj.buddyboss) {
			$('.bpchk-marker-container').appendTo('#whats-new-toolbar');
			$('.bp-checkin-panel').appendTo('#whats-new-attachments');
			if ( $('.whats-new-form-footer #whats-new-toolbar .bpchk-marker-container').length == 0 ) {
				$('.bpchk-marker-container').appendTo($('.whats-new-form-footer #whats-new-toolbar'));
			}
		}

	});
	
	if (bpchk_public_js_obj.buddyboss  ) {
		var bb_checkins_Interval;
		function bb_checkins_icon_push() {
			bb_checkins_Interval = setInterval( function() {

				if (bpchk_public_js_obj.buddyboss && $('#whats-new-form:not(.focus-in) #whats-new-toolbar .bp-checkins-icon-placeholder').length == 0) {
					$('#whats-new-form:not(.focus-in) #whats-new-toolbar').append('<div class="post-elements-buttons-item bp-checkins-icon-placeholder"><div class="checkins-icon bp-tooltip" data-bp-tooltip-pos="up" data-bp-tooltip="' + bpchk_public_js_obj.add_checkin_text + '"><i class="wb-icons wb-icon-map-pin bpchk-allow-checkin"></i></div></div>');
				}
			}, 10);

		}
		bb_checkins_icon_push();
		
		$(document).on('click', '.bb-model-close-button, .activity-update-form-overlay', function(){
			clearInterval(bb_checkins_Interval);
			bb_checkins_icon_push();
		});

		/* jQuery Ajax prefilter*/

		$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
			try {
				if ( originalOptions.data == null || typeof ( originalOptions.data ) == 'undefined' || typeof ( originalOptions.data.action ) == 'undefined' ) {
					return true;
				}
			} catch ( e ) {
				return true;
			}
			if ( originalOptions.data.action == 'post_update' ) {
				clearInterval(bb_checkins_Interval);
				bb_checkins_icon_push();
			}

		} );
	}
	
	$( document ).on ( 'click', '#aw-whats-new-reset', function(){
		$('#bpchk-autocomplete-place').val('');
		$('#bpchk-checkin-place-lat').val('');
		$('#bpchk-checkin-place-lng').val('');
	});

});
