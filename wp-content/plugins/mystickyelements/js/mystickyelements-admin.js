( function( $ ) {
	"use strict";
	$(document).ready(function(){

		/* Apply Wp Color Picker */
		var myOptions = {
			change: function(event, ui){
				var color_id = $(this).attr('id');
				var slug = $(this).data('slug');
				var soical_icon = $(this).data('soical-icon');
				var color_code = ui.color.toString();
				if ( color_id === 'submit_button_background_color'){
					//$('#contact-form-submit-button').css('background-color', color_code );
				}
				if ( color_id === 'submit_button_text_color'){
					//$('#contact-form-submit-button').css('color', color_code );
				}

				if ( color_id === 'tab_background_color'){
					$('.mystickyelements-contact-form .mystickyelements-social-icon').css('background-color', color_code );
				}
				if ( color_id === 'tab_text_color'){
					$('.mystickyelements-contact-form .mystickyelements-social-icon').css('color', color_code );
				}

				if ( color_id === 'minimize_tab_background_color'){
					$('span.mystickyelements-minimize').css('background-color', color_code );
				}

				if ( typeof slug !== 'undefined' ){
					$('.mystickyelements-social-icon.social-' + slug ).css('background', color_code );
					$('.social-channels-item .social-channel-input-box .social-' + slug ).css('background', color_code );
					social_icon_live_preview_color_css();
				}
				if ( typeof soical_icon !== 'undefined' ){
					if ( soical_icon == 'line' ) {
						$('.mystickyelements-social-icon.social-' + soical_icon + ' svg .fil1' ).css('fill', color_code );
						$('.social-channels-item .social-channel-input-box .social-' + soical_icon + ' svg .fil1' ).css('fill', color_code );
					} else if (  soical_icon == 'qzone' ) {
						$('.mystickyelements-social-icon.social-' + soical_icon + ' svg .fil2' ).css('fill', color_code );
						$('.social-channels-item .social-channel-input-box .social-' + soical_icon + ' svg .fil2').css('fill', color_code );
					} else {
						$('.mystickyelements-social-icon.social-' + soical_icon + ' i' ).css('color', color_code );
						$('.social-channels-item .social-channel-input-box .social-' + soical_icon ).css('color', color_code );
					}
				}
			}
	    };
		$('.mystickyelement-color').wpColorPicker(myOptions);

		if ( $( "#contact-form-send-leads option:selected" ).val() === 'mail') {
			$('#contact-form-send-mail').show();
		}

		$('#contact-form-send-leads').on( 'change', function() {
			if ( $(this).val() === 'mail' ) {
				$('#contact-form-send-mail').show();
			} else {
				$('#contact-form-send-mail').hide();
			}
		});

		$(document).on("change", "input[name='contact-form[direction]']", function(){
			if($(this).val() == "RTL") {
				$(".mystickyelements-fixed").addClass("is-rtl");
			} else {
				$(".mystickyelements-fixed").removeClass("is-rtl");
			}
		});

		$( '#myStickyelements-contact-form-enabled' ).on( 'click', function() {
			if( $(this).prop("checked") == true ){
				$( '#myStickyelements-preview-contact' ).show();
				//$( '.myStickyelements-preview-ul' ).removeClass( 'remove-contact-field' );
				$( '.mystickyelements-contact-form' ).removeClass( 'mystickyelements-contact-form-hide' );
			}else {
				$( '#myStickyelements-preview-contact' ).hide();
				//$( '.myStickyelements-preview-ul' ).addClass( 'remove-contact-field' );
				$( '.mystickyelements-contact-form' ).addClass( 'mystickyelements-contact-form-hide' );
			}
			myStickyelements_mobile_count();
		});

		/* Social Chanel Privew */
		$( ".social-channel-view-desktop" ).live( "click", function() {
			var social_channel_tab_desktop = $(this).data( 'social-channel-view' );
			if($(this).prop("checked") == false ){
				$('ul.myStickyelements-preview-ul li.mystickyelements-social-' + social_channel_tab_desktop).removeClass('element-desktop-on');
			} else {
				$('ul.myStickyelements-preview-ul li.mystickyelements-social-' + social_channel_tab_desktop).addClass('element-desktop-on');
			}
			mystickyelements_border_radius();
		});

		$( ".social-channel-view-mobile" ).live( "click", function() {
			var social_channel_tab_mobile = $(this).data( 'social-channel-view' );
			if($(this).prop("checked") == false ){
				$('ul.myStickyelements-preview-ul li.mystickyelements-social-' + social_channel_tab_mobile).removeClass('element-mobile-on');
			} else {
				$('ul.myStickyelements-preview-ul li.mystickyelements-social-' + social_channel_tab_mobile).addClass('element-mobile-on');
			}
			mystickyelements_border_radius();
		});

		/* Append Social Channels tab */
		$(".social-channel").on( "click", function(){
			var social_channel = $(this).data( 'social-channel' );
			var len = $(".myStickyelements-social-channels-lists input[name^='social-channels']:checked").length;

			/* Remove Social Channel */
			if($(this).prop("checked") == false){
				$('.social-channels-item[data-slug=' + social_channel +']').remove();
				$('.social-channel[data-social-channel=' + social_channel + ' ]').prop("checked", false);
				mysticky_social_channel_order();

				/* remove from preview */
				$('ul.myStickyelements-preview-ul li.mystickyelements-social-' + social_channel).remove();
				social_icon_live_preview_color_css();
				mystickyelements_border_radius();
            }

			/* When user add more than 2 then return and display upgrade now message. */
			if ( $('.social-channels-item').length >= 2 || len > 2 ) {
				$('.social-channel[data-social-channel=' + social_channel + ' ]').prop("checked", false);
				$('.social-channel-popover').show().effect('shake', { times: 3 }, 600);
				return;
			}

			/* Add  Social Channel */
			if( $(this).prop("checked") == true ){
                jQuery.ajax({
					url: ajaxurl,
					type:'post',
					data: 'action=mystickyelement-social-tab&social_channel=' + social_channel +'&is_ajax=true',
					success: function( data ){
						$('.social-channels-tab').append(data);
						$('.mystickyelement-color').wpColorPicker(myOptions);
						mysticky_social_channel_order();
						mystickyelements_border_radius();
						social_icon_live_preview_color_css();
						//$('#mystickyelements-preview-description').show();

						$('.social-channel-fontawesome-icon').select2({
													allowClear: true,
													templateSelection: stickyelement_iconformat,
													templateResult: stickyelement_iconformat,
													allowHtml: true
												});
					},
				});

            }
		});

		/* Social Channel Delete */
		$('.social-channel-close').live( 'click', function(){
			var chanel_name = $(this).data('slug');
			$('.social-channels-item[data-slug=' + chanel_name +']').remove();
			$('.social-channel[data-social-channel=' + chanel_name + ' ]').prop("checked", false);
			mysticky_social_channel_order();
			mystickyelements_border_radius();

			/* remove from preview */
			$('ul.myStickyelements-preview-ul li.mystickyelements-social-' + chanel_name).remove();
			social_icon_live_preview_color_css();

		});

		jQuery('.social-channels-tab').sortable({
			items:'.social-channels-item',
			cursor:'move',
			scrollSensitivity:40,
			placeholder: "mystickyelements-state-highlight social-channels-item",
			stop:function(event,ui){
				mysticky_social_channel_order();
				mystickyelements_border_radius();
			}
		});

		$('.myStickyelements-channel-view .social-setting').live( 'click', function(){
			var chanel_name = $(this).data('slug');
			$('.social-channels-item[data-slug=' + chanel_name +'] .social-channel-setting').toggle();
		});


		/* Media Upload */
		$('.social-custom-icon-upload-button').live( 'click', function(e) {
			e.preventDefault();
			var social_channel = $(this).data('slug');
			var image = wp.media({
					title: 'Upload Image',
						// mutiple: true if you want to upload multiple files at once
						multiple: false
					}).open()
				.on('select', function(e){
					// This will return the selected image from the Media Uploader, the result is an object
					var uploaded_image = image.state().get('selection').first();
					// We convert uploaded_image to a JSON object to make accessing it easier
					// Output to the console uploaded_image
					var image_url = uploaded_image.toJSON().url;
					$('#social-channel-' + social_channel + '-custom-icon').val(image_url);
					$('#social-channel-' + social_channel + '-icon').show();
					$('#social-channel-' + social_channel + '-custom-icon-img').attr( 'src', image_url);
					var $social_icon_text = $('#social-' + social_channel + '-icon_text').val();
					var $social_icon_text_size = $('#social-' + social_channel + '-icon_text_size').val();
					if( $social_icon_text != '' ) {
						var $social_icon_text_size_style = 'display: block;font-size: '+ $social_icon_text_size + 'px;';
					} else {
						var $social_icon_text_size_style = 'display: none;font-size: '+ $social_icon_text_size + 'px;';
					}
					$('ul.myStickyelements-preview-ul li span.social-' + social_channel).html();
					$('ul.myStickyelements-preview-ul li span.social-' + social_channel).html( '<img src="' + image_url + '" width="40" height="40"/><span class="mystickyelements-icon-below-text" style="'+ $social_icon_text_size_style +'">'+ $social_icon_text +'</span>' );

					$('.social-channels-item .social-channel-input-box .social-' + social_channel ).html('<img src="' + image_url + '" width="25" height="25"/>');
			});
		});
		$('.social-channel-icon-close').live( 'click', function(){
			var chanel_name = $(this).data('slug');
			$('#social-channel-' + chanel_name + '-custom-icon').val('');
			$('#social-channel-' + chanel_name + '-icon').hide();
			$('#social-channel-' + chanel_name + '-custom-icon-img').attr( 'src', '');
		});

		$('.social-channel-icon').each( function(){
			if ( $(this).children('img').attr('src') !='' ){
				$(this).show();
			}
		});

		/*  Delete Contact Lead*/
		jQuery(".mystickyelement-delete-entry").on( 'click', function(){
			var deleterowid = $( this ).attr( "data-delete" );
			var confirm_delete = window.confirm("Are you sure you want to delete Record with ID# "+deleterowid);
			if (confirm_delete == true) {
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "mystickyelement_delete_db_record","ID": deleterowid, delete_nonce: jQuery("#delete_nonce").val()},
					success: function(data){
						location.href = window.location.href;
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("Status: " + textStatus); alert("Error: " + errorThrown);
					}
				});
			}
			return false;
		});

		jQuery("#mystickyelement_delete_all_leads").on( 'click', function(){
			var confirm_delete = window.confirm("Are you sure you want to delete all Record from the database?");
			if (confirm_delete == true) {
				jQuery.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {"action": "mystickyelement_delete_db_record", 'all_leads': 1 , delete_nonce: jQuery("#delete_nonce").val()},
					success: function(data){
						location.href = window.location.href;
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) {
						alert("Status: " + textStatus); alert("Error: " + errorThrown);
					}
				});
			}
			// Prevents default submission of the form after clicking on the submit button.
			return false;
		});

		/* Desktop Position */
		jQuery("input[name='general-settings[position]'").on( 'click', function(){
			if ( $(this).val() === 'left'){
				$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-left');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-bottom');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-right');

				$('span.mystickyelements-minimize').removeClass('minimize-position-right');
				$('span.mystickyelements-minimize').removeClass('minimize-position-bottom');
				$('span.mystickyelements-minimize').addClass('minimize-position-left');
				$( '.mystickyelements-minimize.minimize-position-left' ).html('&larr;');				

				$( '.myStickyelements-position-on-screen-wrap' ).hide();
			}
			if ( $(this).val() === 'right'){
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-left');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-bottom');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-right');

				$('span.mystickyelements-minimize').removeClass('minimize-position-left');
				$('span.mystickyelements-minimize').removeClass('minimize-position-bottom');
				$('span.mystickyelements-minimize').addClass('minimize-position-right');
				$( '.mystickyelements-minimize.minimize-position-right' ).html('&rarr;')				

				$( '.myStickyelements-position-on-screen-wrap' ).hide();
			}
			if ( $(this).val() === 'bottom'){
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-left');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-right');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-top');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-bottom');

				$('span.mystickyelements-minimize').removeClass('minimize-position-left');
				$('span.mystickyelements-minimize').removeClass('minimize-position-right');
				$('span.mystickyelements-minimize').removeClass('minimize-position-top');
				$('span.mystickyelements-minimize').addClass('minimize-position-bottom');
				$( '.mystickyelements-minimize.minimize-position-bottom' ).html('&darr;');				

				$( '.myStickyelements-position-on-screen-wrap' ).show();
			}
			mystickyelements_border_radius();
		});
		/* NEW CUSTOM FIELD POPUP */
		
		$( '.mystickyelements-new-custom-btn' ).on( 'click', function () {
			$( '.custom-field-popup-open' ).show();
			$( 'body' ).addClass( 'contact-form-popup-open' );
		});
		$( '.contact-form-dropdfown-close' ).on( 'click', function () {
			$( '.custom-field-popup-open' ).hide();
			$( 'body' ).removeClass( 'contact-form-popup-open' );
		});
		$( document ).click( function( event ) {
		  if ( !$( event.target ).closest( ".custom-field-popup-open,.mystickyelements-add-custom-fields a" ).length ) {
			$( '.custom-field-popup-open' ).hide();
			$( 'body' ).removeClass( 'contact-form-popup-open' );
		  }
		});
		/* Mobile Position */
		jQuery("input[name='general-settings[position_mobile]'").on( 'click', function(){
			if( $( '.myStickyelements-preview-screen' ).hasClass( 'myStickyelements-preview-mobile-screen' ) == true ) {
				if ( $(this).val() === 'left'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-bottom');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-top');

					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-right');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-bottom');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-top');
					$('span.mystickyelements-minimize').addClass('minimize-position-mobile-left');

					jQuery( '#myStickyelements_mobile_templete_desc' ).hide();
				}
				if ( $(this).val() === 'right'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-bottom');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-top');

					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-left');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-bottom');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-top');
					$('span.mystickyelements-minimize').addClass('minimize-position-mobile-right');

					jQuery( '#myStickyelements_mobile_templete_desc' ).hide();
				}
				if ( $(this).val() === 'bottom'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-top');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-bottom');

					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-left');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-right');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-top');
					$('span.mystickyelements-minimize').addClass('minimize-position-mobile-bottom');

					if (jQuery('#myStickyelements-inputs-templete option:selected').val() != 'default') {
						jQuery( '#myStickyelements_mobile_templete_desc' ).show();
						$('#myStickyelements_mobile_templete_desc').fadeOut(500);
						$('#myStickyelements_mobile_templete_desc').fadeIn(500);
					} else {
						jQuery( '#myStickyelements_mobile_templete_desc' ).hide();
					}
				}
				if ( $(this).val() === 'top'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-bottom');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-top');

					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-left');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-right');
					$('span.mystickyelements-minimize').removeClass('minimize-position-mobile-bottom');
					$('span.mystickyelements-minimize').addClass('minimize-position-mobile-top');

					if (jQuery('#myStickyelements-inputs-templete option:selected').val() != 'default') {
						jQuery( '#myStickyelements_mobile_templete_desc' ).show();
						$('#myStickyelements_mobile_templete_desc').fadeOut(500);
						$('#myStickyelements_mobile_templete_desc').fadeIn(500);
					} else {
						jQuery( '#myStickyelements_mobile_templete_desc' ).hide();
					}
				}
			}
			mystickyelements_border_radius();
		});
		/*Icon text live preivew*/
		$( '.myStickyelements-icon-text-input' ).live( 'keyup', function(){
			var myStickyelements_icon_text = $( this ).val();
			var myStickyelements_icon_social = $( this ).data( 'icontext' );
			if( jQuery("#myStickyelements-inputs-templete").val() == 'default' ) {
				$( '.social-' + myStickyelements_icon_social + ' .mystickyelements-icon-below-text' ).show();
			}
			$( '.social-' + myStickyelements_icon_social + ' .mystickyelements-icon-below-text' ).text( myStickyelements_icon_text );
			if( myStickyelements_icon_text == '' ) {
				$( '.social-' + myStickyelements_icon_social + ' .mystickyelements-icon-below-text' ).hide();
			}
		} );
		/*Icon text size live preivew*/
		$( '.myStickyelements-icon-text-size' ).live( 'keyup', function(){
			var myStickyelements_icon_text_size = $( this ).val();
			var myStickyelements_icon_social = $( this ).data( 'icontextsize' );
			$( '.social-' + myStickyelements_icon_social + ' .mystickyelements-icon-below-text' ).css( 'font-size', myStickyelements_icon_text_size + 'px' );
			if( myStickyelements_icon_text_size == 0 ) {
				$( '.social-' + myStickyelements_icon_social + ' .mystickyelements-icon-below-text' ).css( 'font-size', '' );
			}
		} );
		/*Contact text live preivew*/
		$( '[name="contact-form[text_in_tab]"]' ).live( 'keyup', function(){
			var myStickyelements_text_in_tab = $( this ).val();
			$( '.mystickyelements-contact-form .mystickyelements-social-icon' ).html( '<i class="far fa-envelope"></i> ' + myStickyelements_text_in_tab );			
		} );

		jQuery(".myStickyelements-preview-window ul li").on( 'click', function(){
			$('.myStickyelements-preview-window ul li').removeClass('preview-active');
			if ( $(this).hasClass('preview-desktop') === true ) {
				$(this).addClass('preview-active');
				$('.myStickyelements-preview-screen').removeClass('myStickyelements-preview-mobile-screen');

				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-left');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-right');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-bottom');
				$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-top');

				jQuery( '#myStickyelements_mobile_templete_desc' ).hide();

				if ( jQuery( 'input[name="contact-form[desktop]"]' ).prop( 'checked' ) == true ) {
					jQuery( '#myStickyelements-preview-contact' ).addClass( 'element-desktop-on' );
				} else {
					jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-desktop-on' );
				}
			}

			if ( $(this).hasClass('preview-mobile') === true ) {
				$(this).addClass('preview-active');
				$('.myStickyelements-preview-screen').addClass('myStickyelements-preview-mobile-screen');
				$("input[name='general-settings[position_mobile]']:checked").val()
				if ( $("input[name='general-settings[position_mobile]']:checked").val() === 'left'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-bottom');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-top');
				}
				if ( $("input[name='general-settings[position_mobile]']:checked").val() === 'right'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-bottom');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-top');
				}
				if ( $("input[name='general-settings[position_mobile]']:checked").val() === 'bottom'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-top');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-bottom');
				}
				if ( $("input[name='general-settings[position_mobile]']:checked").val() === 'top'){
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-left');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-right');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').removeClass('mystickyelements-position-mobile-bottom');
					$('.myStickyelements-preview-screen .mystickyelements-fixed').addClass('mystickyelements-position-mobile-top');
				}

				if ( jQuery( "#myStickyelements-inputs-templete option:selected" ).val() != 'default' && ( jQuery('input[name="general-settings[position_mobile]"]:checked').val() == 'bottom' || jQuery('input[name="general-settings[position_mobile]"]:checked').val() == 'top' ) ) {
					jQuery( '#myStickyelements_mobile_templete_desc' ).show();
					$('#myStickyelements_mobile_templete_desc').fadeOut(500);
					$('#myStickyelements_mobile_templete_desc').fadeIn(500);
				} else {
					jQuery( '#myStickyelements_mobile_templete_desc' ).hide();
				}

				if ( jQuery( 'input[name="contact-form[mobile]"]' ).prop( 'checked' ) == true && $( '#myStickyelements-contact-form-enabled' ).prop("checked") == true ) {
					jQuery( '#myStickyelements-preview-contact' ).addClass( 'element-mobile-on' );
				} else {
					jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-desktop-on' );
					jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-mobile-on' );
				}
			}
			mystickyelements_border_radius();
		});

		function mysticky_social_channel_order(){
			var social_count = 1;
			$('.social-channels-item').each( function(){
				/* remove from preview */
				$('ul.myStickyelements-preview-ul li.mystickyelements-social-' + $(this).data('slug')).remove();
			});

			$('.social-channels-item').each( function(){
				var social_channel = $(this).data('slug');
				social_count = ("0" + social_count).slice(-2);
				$('#social-' + social_channel  + '-number').html(social_count);
				social_count++;

				var $social_icon = $('.social-channel-input-box .social-'+social_channel).html();
				var $social_custom_icon = $('.social-channel-setting #social-channel-'+ social_channel + '-icon img').attr( 'src');

				var $social_custom_fontawe_icon = $('#mystickyelements-'+ social_channel + '-custom-icon').val();
				if ( typeof $social_custom_icon !== 'undefined' && $social_custom_fontawe_icon !== '') {
					$social_icon = '<i class="' + $social_custom_fontawe_icon + '"></i>';
				}else if ( typeof $social_custom_icon !== 'undefined' && $social_custom_icon !== '' ) {
					$social_icon = '<img src="' + $social_custom_icon + '"/>';
				}

				var $social_bg_color = $('#social-' + social_channel + '-bg_color').val();
				var $social_icon_color = $('#social-' + social_channel + '-icon_color').val();
				var $social_icon_text = $('#social-' + social_channel + '-icon_text').val();
				var $social_icon_text_size = $('#social-' + social_channel + '-icon_text_size').val();
				if( $social_icon_text != '' ) {
					var $social_icon_text_size_style = 'display: block;font-size: '+ $social_icon_text_size + 'px;';
				} else {
					var $social_icon_text_size_style = 'display: none;font-size: '+ $social_icon_text_size + 'px;';
				}

				if( $('#social_channel_' + social_channel + '_desktop').prop("checked") == true ){
					var social_channel_desktop_visible = ' element-desktop-on';
				}
				else {
					var social_channel_desktop_visible = '';
				}

				if( $('#social_channel_' + social_channel + '_mobile').prop("checked") == true ){
					var social_channel_mobile_visible = ' element-mobile-on';
				}
				else {
					var social_channel_mobile_visible = '';
				}

				var social_channel_data = '<li id="mystickyelements-social-' + social_channel + '" class="mystickyelements-social-' + social_channel + '' +  social_channel_desktop_visible + '' + social_channel_mobile_visible + ' mystickyelements-social-preview "><span class="mystickyelements-social-icon social-' + social_channel + '" style="background: ' +$social_bg_color + '; color: '+ $social_icon_color + '">' + $social_icon + '<span class="mystickyelements-icon-below-text" style="'+ $social_icon_text_size_style +'">'+ $social_icon_text +'</span></span>';

				if ( social_channel == 'line') {
					social_channel_data += '<style>.mystickyelements-social-icon.social-'+ social_channel +' svg .fil1{fill: '+ $social_icon_color+'}</style>';
				}
				if ( social_channel == 'qzone') {
					social_channel_data += '<style>.mystickyelements-social-icon.social-'+ social_channel +' svg .fil2{fill: '+ $social_icon_color+'}</style>';
				}
				social_channel_data +='</li>';

				$('ul.myStickyelements-preview-ul').append(social_channel_data);
			});

			setTimeout(function(){
				myStickyelements_mobile_count();
			}, 500);
		}
		myStickyelements_mobile_count();
		function myStickyelements_mobile_count () {
			if( $( 'input[name="contact-form[desktop]"]' ).prop("checked") == true && $( '#myStickyelements-contact-form-enabled' ).prop("checked") == true ){
				jQuery( '#myStickyelements-preview-contact' ).addClass( 'element-desktop-on' );
			} else {
				jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-desktop-on' );
				jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-mobile-on' );
			}
			if( $( 'input[name="contact-form[mobile]"]' ).prop("checked") == true && $( '#myStickyelements-contact-form-enabled' ).prop("checked") == true ){
				jQuery( '#myStickyelements-preview-contact' ).addClass( 'element-mobile-on' );
			} else {
				jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-mobile-on' );
			}

			var mobile_bottom = 0;
			$('.mystickyelements-fixed ul li').each( function () {
				if ( $(this).hasClass('element-mobile-on') ){
					mobile_bottom++;
				}
			});

			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-bottom-social-channel-\S+/g) || []).join(' ');
			});
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-top-social-channel-\S+/g) || []).join(' ');
			});
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-bottom-social-channel-' + mobile_bottom );
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-top-social-channel-' + mobile_bottom );
		}

		/* Sortable Contact Form Fields */
		jQuery( '#mystickyelements-contact-form-fields' ).sortable({
			items:'tr',
			cursor:'move',
			scrollSensitivity:40,
			placeholder: "mystickyelements-state-highlight",
			helper:function(e,ui){
				ui.children().each(function(){
					jQuery(this).width(jQuery(this).width());
				});
				ui.css('left', '0');
				return ui;
			},
			start:function(event,ui){
				ui.item.css('background-color','#f9fcfc');
			},
			stop:function(event,ui){
				ui.item.removeAttr('style');
			}
		});

		$( "#mystickyelements-contact-form-fields" ).disableSelection();

		/* Open Contact form Dropdown Option popup */
		$( '.contact-form-dropdown-popup' ).on( 'click', function () {
			$( '.contact-form-dropdown-open' ).show();
			$( 'body' ).addClass( 'contact-form-popup-open' );
		});
		$( '.contact-form-dropdfown-close' ).on( 'click', function () {
			$( '.contact-form-dropdown-open' ).hide();
			$( 'body' ).removeClass( 'contact-form-popup-open' );
		});
		/* Add Dropdown Option */
		$( '.add-dropdown-option' ).on( 'click', function () {
			$( '.contact-form-dropdown-option' ).append( '<div class="option-value-field ui-sortable-handle"><div class="move-icon"></div><input type="text" name="contact-form[dropdown-option][]" value=""/><span class="delete-dropdown-option"><i class="fas fa-times"></i></span></div>' );
		});
		/* Delete Dropdown Option */
		$( '.delete-dropdown-option' ).live( 'click', function () {
			$(this).closest('div').remove();
		});

		/*  Sortable Dropdown Option Value field*/
		jQuery( '.contact-form-dropdown-option' ).sortable({
			items:'.option-value-field',
			placeholder: "mystickyelements-state-highlight option-value-field",
			cursor:'move',
			scrollSensitivity:40,
			helper:function(e,ui){
				ui.children().each(function(){
					jQuery(this).width(jQuery(this).width());
				});
				ui.css('left', '0');
				return ui;
			},
			start:function(event,ui){
				ui.item.css('background-color','#EFF6F6');
			},
			stop:function(event,ui){
				ui.item.removeAttr('style');
			}
		});

		if ( $( '#myStickyelements-minimize-tab' ).prop("checked") != true ) {
			$( '.myStickyelements-minimize-tab .wp-picker-container' ).hide();
			$( '.myStickyelements-minimized' ).hide();
		}

		if ( $( '#myStickyelements-contact-form-enabled' ).prop("checked") != true ) {
			$('.myStickyelements-contact-form-field-hide').hide();
			myStickyelements_mobile_count();
		}

		if ( $( '#myStickyelements-social-channels-enabled' ).prop("checked") != true ) {
			$('.mystickyelements-social-preview').hide();
			$('#myStickyelements-preview-contact').addClass('mystickyelements-contact-last');
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-bottom-social-channel-\S+/g) || []).join(' ');
			});
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-top-social-channel-\S+/g) || []).join(' ');
			});
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-bottom-social-channel-1' );
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-top-social-channel-1' );
		}

		$( 'input[name="contact-form[desktop]"]' ).on( 'click', function(){
			if( $(this).prop("checked") == true ){
				jQuery( '#myStickyelements-preview-contact' ).addClass( 'element-desktop-on' );
			} else {
				jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-desktop-on' );
			}
		});
		$( 'input[name="contact-form[mobile]"]' ).on( 'click', function(){
			if( $(this).prop("checked") == true ){
				jQuery( '#myStickyelements-preview-contact' ).addClass( 'element-mobile-on' );
				myStickyelements_mobile_count();
			} else {
				jQuery( '#myStickyelements-preview-contact' ).removeClass( 'element-mobile-on' );
				myStickyelements_mobile_count();
			}
		});

		$( '#myStickyelements-minimize-tab' ).on( 'click', function () {
			if( $(this).prop("checked") == true ){
				$( '.myStickyelements-minimize-tab .wp-picker-container' ).show();
				$( '.myStickyelements-minimized' ).show();
				var position = $( 'input[name="general-settings[position]"]:checked' ).val();
				var position_arrow = '';
				if (position == 'left'){
					position_arrow = '&larr;';
				} else {
					position_arrow = '&rarr;';
				}
				var backgroud_color = $( '#minimize_tab_background_color' ).val();

				var minimize_content = "<li class='mystickyelements-minimize'><span class='mystickyelements-minimize minimize-position-"+ position +"' style='background: "+ backgroud_color +"'>"+position_arrow+"</span></li>";
				$( '.myStickyelements-preview-tab ul.myStickyelements-preview-ul li.mystickyelements-minimize' ).remove();
				$( ".myStickyelements-preview-tab ul.myStickyelements-preview-ul" ).prepend( minimize_content );
				$( '.myStickyelements-preview-tab ul.myStickyelements-preview-ul' ).removeClass( 'remove-minimize' );
			} else {
				$( '.myStickyelements-minimize-tab .wp-picker-container' ).hide();
				$( '.myStickyelements-minimized' ).hide();
				$( '.myStickyelements-preview-tab ul.myStickyelements-preview-ul li.mystickyelements-minimize' ).remove();
				$( '.myStickyelements-preview-tab ul.myStickyelements-preview-ul' ).addClass( 'remove-minimize' );
			}
			mystickyelements_border_radius();
		});

		$( '#myStickyelements-contact-form-enabled' ).on( 'click', function () {
			$('.myStickyelements-contact-form-field-hide').toggle();
		});

		$( '#myStickyelements-social-channels-enabled' ).on( 'click', function () {
				$('.mystickyelements-social-preview').toggle();
				$('#myStickyelements-preview-contact').toggleClass('mystickyelements-contact-last');
				if ($(this).prop("checked") != true) {
					$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
						return (className.match (/(^|\s)mystickyelements-bottom-social-channel-\S+/g) || []).join(' ');
					});
					$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
						return (className.match (/(^|\s)mystickyelements-top-social-channel-\S+/g) || []).join(' ');
					});
					$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-bottom-social-channel-1' );
					$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-top-social-channel-1' );
				} else {
					myStickyelements_mobile_count();
				}
		});

		var total_page_option = 0;
		var page_option_content = "";
		total_page_option   = $( '.myStickyelements-page-options' ).length;
	    page_option_content = $( '.myStickyelements-page-options-html' ).html();
	    $( '.myStickyelements-page-options-html' ).remove();

	    $( '#create-rule' ).on( 'click', function(){
	        var append_html = page_option_content.replace(/__count__/g, total_page_option, page_option_content);
	        total_page_option++;
	        $( '.myStickyelements-page-options' ).append( append_html );
	        $( '.myStickyelements-page-options .myStickyelements-page-option' ).removeClass( 'last' );
	        $( '.myStickyelements-page-options .myStickyelements-page-option:last' ).addClass( 'last' );

			if( $( '.myStickyelements-page-option .upgrade-myStickyelements' ).length > 0 ) {
				$( this ).css( 'pointer-events', 'none' );
			}
			
			var topPos = jQuery(".show-on-apper").offset().top - jQuery(window).scrollTop() - 640;
			topPos = Math.abs(topPos);
			var finalpos = $( '.mystickyelements-wrap' ).position().top + topPos;
			jQuery(".myStickyelements-preview-tab").css("margin-top", ((-1)*finalpos)+"px");
	    });
	    $( document ).on( 'click', '.myStickyelements-remove-rule', function() {
	       $( this ).closest( '.myStickyelements-page-option' ).remove();
	        $( '.myStickyelements-page-options .myStickyelements-page-option' ).removeClass( 'last' );
	        $( '.myStickyelements-page-options .myStickyelements-page-option:last' ).addClass( 'last' );
	    });
		check_for_preview_pos();
		jQuery(window).scroll(function(){
			check_for_preview_pos();
		});

		$( document ).on( 'change', '.myStickyelements-url-options', function() {
			var current_val = jQuery( this ).val();
			var myStickyelements_siteURL = jQuery( '#myStickyelements_site_url' ).val();
			var myStickyelements_newURL  = myStickyelements_siteURL;
			if( current_val == 'page_has_url' ) {
				myStickyelements_newURL = myStickyelements_siteURL;
			} else if( current_val == 'page_contains' ) {
				myStickyelements_newURL = myStickyelements_siteURL + '%s%';
			} else if( current_val == 'page_start_with' ) {
				myStickyelements_newURL = myStickyelements_siteURL + 's%';
			} else if( current_val == 'page_end_with' ) {
				myStickyelements_newURL = myStickyelements_siteURL + '%s';
			}
			$( this ).closest( '.url-content' ).find( '.myStickyelements-url' ).text( myStickyelements_newURL );
		});

		set_rule_position();
		$( window ).on( 'scroll', function(){
			set_rule_position();
		});

		$("#myStickyelements-inputs-position-on-screen").on( 'change', function() {
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-position-screen-\S+/g) || []).join(' ');
			});
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-position-screen-' + $(this).val() );
		});
		$("#myStickyelements-widget-size").on( 'change', function() {
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-size-\S+/g) || []).join(' ');
			});
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-size-' + $(this).val() );
		});
		$("#myStickyelements-widget-mobile-size").on( 'change', function() {
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-mobile-size-\S+/g) || []).join(' ');
			});
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-mobile-size-' + $(this).val() );
		});

		$("#myStickyelements-entry-effect").on( 'change', function() {
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass('entry-effect');
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-entry-effect-\S+/g) || []).join(' ');
			});
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-entry-effect-' + $(this).val() );
			setTimeout( function(){
				$(".myStickyelements-preview-tab .mystickyelements-fixed").addClass('entry-effect');
			}, 1000 );

		});

		$("#myStickyelements-inputs-templete").on( 'change', function() {
			$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
				return (className.match (/(^|\s)mystickyelements-templates-\S+/g) || []).join(' ');
			});
			$( '.myStickyelements-preview-tab .mystickyelements-fixed' ).addClass( 'mystickyelements-templates-' + $(this).val() );
			social_icon_live_preview_color_css();

			if ( jQuery(this).val() != 'default' && jQuery( '.preview-mobile' ).hasClass('preview-active') == true ) {
				jQuery( '#myStickyelements_mobile_templete_desc' ).show();
				$('#myStickyelements_mobile_templete_desc').fadeOut(500);
				$('#myStickyelements_mobile_templete_desc').fadeIn(500);
			} else {
				jQuery( '#myStickyelements_mobile_templete_desc' ).hide();
			}
			if( jQuery( this ).val() != 'default' ) {
				$( '.mystickyelements-icon-below-text' ).hide();
			} else {
				$( '.mystickyelements-icon-below-text' ).show();
				$( '.myStickyelements-preview-ul li' ).each( function(){
					if ( $( this ).find( '.mystickyelements-icon-below-text' ).is(':empty') ){
					  $( this ).find( '.mystickyelements-icon-below-text' ).hide();
					}
				} );
			}
		});

		$( '.mystickyelements-fixed' ).addClass( 'entry-effect' );

		/* FontAwesome icon formate in select2 */
		function stickyelement_iconformat(icon) {
			var originalOption = icon.element;
			return $('<span><i class="' + icon.text + '"></i> ' + icon.text + '</span>');
		}

		$('.social-channel-fontawesome-icon').select2({
													allowClear: true,
													templateSelection: stickyelement_iconformat,
													templateResult: stickyelement_iconformat,
													allowHtml: true
												});
		$('.social-channel-fontawesome-icon').live( 'change', function() {
			var social_channel = $(this).data('slug');
			$('ul.myStickyelements-preview-ul li span.social-' + social_channel).html('<i class="' + $(this).val() + '"></i>');
			$('.social-channels-item .social-channel-input-box .social-' + social_channel ).html('<i class="' + $(this).val() + '"></i>');
			mystickyelements_border_radius();
		});
		mystickyelements_border_radius();
		$( '.myStickyelements-preview-tab').css( 'top' , $( '.myStickyelements-contact-form-tab' ).offset().top );

		/*Confirm dialog*/
		$( '.mystickyelements-wrap p.submit input#submit' ).on( 'click', function(e){
			var icon_below_text_apper = 0;
			var mystickyelement_save_confirm_status = $( 'input#mystickyelement_save_confirm_status' ).val();
			$( '.mystickyelements-fixed ul li' ).each( function(){
				var icon_below_text_val = $( this ).find( '.mystickyelements-icon-below-text' ).text();
				if ( icon_below_text_val != '' ){
				  icon_below_text_apper = 1;
				  return false;
				}
			} );
			if ( jQuery("#myStickyelements-inputs-templete").val() != 'default' && icon_below_text_apper && mystickyelement_save_confirm_status == '' ) {
				e.preventDefault();
				$( "#mystickyelement-save-confirm" ).dialog({
					resizable: false,
					modal: true,
					draggable: false,
					height: 'auto',
					width: 600,
					buttons: {
						"Publish": {
							click:function () {
								$( 'input#mystickyelement_save_confirm_status' ).val('1');
								$( '.mystickyelement-wrap p.submit input#submit' ).trigger('click');
								$( this ).dialog('close');
							},
							text: 'Publish',
	                    	class: 'purple-btn'
						},
						"Keep Editing": {
							 click:function () {
								$( this ).dialog( 'close' );
							},
							text: 'Keep Editing',
	                    	class: 'gray-btn'
						}
					}
				});
			}
			//return false;
		} );
		
		/*social search*/
		$( ".myStickyelements-social-search-wrap input" ).on( "keyup", function() {
			var search_value = $( this ).val().toLowerCase();
			$( ".myStickyelements-social-channels-lists li" ).filter(function() {
			  $( this ).toggle( $( this ).data( "search" ).toLowerCase().indexOf( search_value ) > -1 );
			});
		});
	});
	$( window ).load( function(){

	    $( '.myStickyelements-url-options' ).each( function(){
	        $( this ).trigger( 'change' );
	    })
	});
	function check_for_preview_pos() {
		if(jQuery(".show-on-apper").length && jQuery(".myStickyelements-preview-tab").length) {

			var topPos = jQuery(".show-on-apper").offset().top - jQuery(window).scrollTop() - 640;
			var tabtopPos = jQuery(".tab-css-apper").offset().top - jQuery(window).scrollTop() - 580;
			if ( (topPos < 0 && $( ".myStickyelements-page-option" ).length) ) {
				topPos = Math.abs(topPos);
				var finalpos = $( '.mystickyelements-wrap' ).position().top + topPos;
				jQuery(".myStickyelements-preview-tab").css("margin-top", ((-1)*finalpos)+"px");
			} else if (jQuery(window).scrollTop() > 0 ) {
				jQuery(".myStickyelements-preview-tab").css("margin-top", "-" + $( '.mystickyelements-wrap' ).position().top + "px");
			} else {
				jQuery(".myStickyelements-preview-tab").css("margin-top", "0");
			}
			if ( tabtopPos < 0 ) {
				tabtopPos = Math.abs(tabtopPos);
				var finalpos = $( '.mystickyelements-wrap' ).position().top + tabtopPos;
				jQuery(".myStickyelements-preview-tab").css("margin-top", ((-1)*finalpos)+"px");
				if( $( ".myStickyelements-page-option" ).length ) {
					topPos = Math.abs(topPos);
					var finalpos = $( '.mystickyelements-wrap' ).position().top + topPos;
					jQuery(".myStickyelements-preview-tab").css("margin-top", ((-1)*finalpos)+"px");
				}
 			}
		}
	}

	function set_rule_position() {
		var dir = $("html").attr("dir") ;
		if (dir === 'rtl') {
			var rt = ($( '.myStickyelements-content-section tr:first-child .myStickyelements-inputs' ).position().left - $( '.myStickyelements-minimized .myStickyelements-inputs' ).outerWidth()) + 12;
			$( '#create-rule' ).css( 'margin-right', rt + 'px' );
		} else {
			var right_element_pos = $( '.myStickyelements-content-section tr:first-child .myStickyelements-inputs' ).position().left;
			var create_rule_pos = $( '#create-rule' ).position().left;
			var remain_rule_pos = right_element_pos - create_rule_pos;
			$( '#create-rule' ).css( 'margin-left', remain_rule_pos + 'px' );
		}
	}

	function social_icon_live_preview_color_css() {
		$('.myStickyelements-preview-ul li.mystickyelements-social-preview').each( function () {
			var current_icon_color = $(this).find('span.mystickyelements-social-icon').get(0).style.backgroundColor;
			var all_icon_class = this.className;
			var current_icon_class = all_icon_class.split(' ');

			var preview_css = '.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{background: '+ current_icon_color +'}';
			var preview_css = preview_css + '.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{background: '+ current_icon_color +'}';
			var preview_css = preview_css + '.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-bottom.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon{background: '+ current_icon_color +' !important}';
			var preview_css = preview_css + '.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-top.mystickyelements-templates-diamond li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon{background: '+ current_icon_color +' !important}';

			var preview_css = preview_css + '.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{background: '+ current_icon_color +'}';
			var preview_css = preview_css + '.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{background: '+ current_icon_color +'}';
			var preview_css = preview_css + '.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-bottom.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon{background: '+ current_icon_color +' !important}';
			var preview_css = preview_css + '.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-top.mystickyelements-templates-triangle li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon{background: '+ current_icon_color +' !important}';

			var preview_css = preview_css + '.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{border-left-color: '+ current_icon_color +'}';
			var preview_css = preview_css + '.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{border-right-color: '+ current_icon_color +'}';
			var preview_css = preview_css + '.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-position-bottom.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{border-bottom-color: '+ current_icon_color +'}';

			var preview_css = preview_css + '.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-left.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{border-left-color: '+ current_icon_color +'}';
			var preview_css = preview_css + '.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-position-mobile-right.mystickyelements-templates-arrow li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{border-right-color: '+ current_icon_color +'}';

			if ( current_icon_class[0] == 'insagram' ) {
				var preview_css = preview_css + '.myStickyelements-preview-screen:not(.myStickyelements-preview-mobile-screen) .mystickyelements-templates-arrow li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{background: '+ current_icon_color +'}';
				var preview_css = preview_css + '.myStickyelements-preview-screen.myStickyelements-preview-mobile-screen .mystickyelements-templates-arrow li:not(.mystickyelements-contact-form).'+ current_icon_class[0] +' span.mystickyelements-social-icon::before{background: '+ current_icon_color +'}';
			}
			$('head').append('<style type="text/css"> '+ preview_css +' </style>');
		});
	}

	/*font family Privew*/
	$( '.form-fonts' ).on( 'change', function(){
		var font_val = $(this).val();
		$( '.sfba-google-font' ).remove();
		$( 'head' ).append( '<link href="https://fonts.googleapis.com/css?family='+ font_val +':400,600,700" rel="stylesheet" type="text/css" class="sfba-google-font">' );
		$( '.myStickyelements-preview-ul .mystickyelements-social-icon' ).css( 'font-family',font_val );
	} );

	function mystickyelements_border_radius(){
		var position_device = '';
		var social_id = '';
		var $i = 0;
		var second_social_id = '';
		var $flg = false;

		if( $(".myStickyelements-preview-screen" ).hasClass( "myStickyelements-preview-mobile-screen" ) ){
			position_device = 'mobile-';
		}
		var $mobile_bottom = 0;
		$('.mystickyelements-fixed ul li').each( function () {
			$('.mystickyelements-position-' + position_device + 'left #' + $(this).attr('id') + ' .mystickyelements-social-icon').css('border-radius','');
			$('.mystickyelements-position-' + position_device + 'right #' + $(this).attr('id') + ' .mystickyelements-social-icon').css('border-radius','');
			$('.mystickyelements-position-' + position_device + 'bottom #' + $(this).attr('id') + ' .mystickyelements-social-icon').css('border-radius','');

			/* Check First LI */
			if ( $i == 1 ){
				if ( !$(".myStickyelements-preview-screen" ).hasClass( "myStickyelements-preview-mobile-screen" ) &&  !$(this).hasClass('element-desktop-on')){
					$flg = true;
				}
				if ( $(".myStickyelements-preview-screen" ).hasClass( "myStickyelements-preview-mobile-screen" ) &&  !$(this).hasClass('element-mobile-on')){
					$flg = true;
				}
			}
			if ( $i == 2 && $flg === true) {
				if ( !$(".myStickyelements-preview-screen" ).hasClass( "myStickyelements-preview-mobile-screen" ) ){
					second_social_id = $(this).attr('id');
				}
				if ( $(".myStickyelements-preview-screen" ).hasClass( "myStickyelements-preview-mobile-screen" ) ){
					second_social_id = $(this).attr('id');
				}
			}
			if ( !$(".myStickyelements-preview-screen" ).hasClass( "myStickyelements-preview-mobile-screen" ) &&  $(this).hasClass('element-desktop-on')){
				social_id = $(this).attr('id');
			}
			if ( $(".myStickyelements-preview-screen" ).hasClass( "myStickyelements-preview-mobile-screen" ) &&  $(this).hasClass('element-mobile-on')){
				social_id = $(this).attr('id');
				$mobile_bottom++;
			}
			$i++;
		});
		$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
			return (className.match (/(^|\s)mystickyelements-bottom-social-channel-\S+/g) || []).join(' ');
		});
		$(".myStickyelements-preview-tab .mystickyelements-fixed").removeClass (function (index, className) {
			return (className.match (/(^|\s)mystickyelements-top-social-channel-\S+/g) || []).join(' ');
		});
		$( '.mystickyelements-fixed.mystickyelements-position-mobile-bottom').addClass( 'mystickyelements-bottom-social-channel-' + $mobile_bottom );
		$( '.mystickyelements-fixed.mystickyelements-position-mobile-top').addClass( 'mystickyelements-top-social-channel-' + $mobile_bottom );
		if ( social_id != ''  ) {
			$('.mystickyelements-fixed').show();
			if ( social_id === 'myStickyelements-preview-contact' ){
				$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css('border-bottom-left-radius', '10px' );
				$('.mystickyelements-position-' + position_device + 'right #' + social_id + ' .mystickyelements-social-icon').css('border-top-left-radius', '10px' );
				$('.mystickyelements-position-' + position_device + 'bottom #' + social_id + ' .mystickyelements-social-icon').css('border-top-right-radius', '10px' );

				if( $( 'li.mystickyelements-minimize' ).length !== 1 ){
					$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css('border-bottom-right-radius', '10px' );
					$('.mystickyelements-position-' + position_device + 'right #' + social_id + ' .mystickyelements-social-icon').css('border-top-right-radius', '10px' );
				}
			} else if ( social_id !== 'myStickyelements-preview-contact') {
				if ( $i=== 1 ) {
					$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css('border-radius', '0 10px 10px 0' );
					$('.mystickyelements-position' + position_device + '-right #' + social_id + ' .mystickyelements-social-icon').css('border-radius', '10px 0 0 10px' );
				} else {
					$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css( 'border-bottom-right-radius', '10px' );
					$('.mystickyelements-position-' + position_device + 'right #' + social_id + ' .mystickyelements-social-icon').css( 'border-bottom-left-radius', '10px' );
					$('.mystickyelements-position-' + position_device + 'bottom #' + social_id + ' .mystickyelements-social-icon').css( 'border-top-right-radius', '10px' );
				}
			}
		} else {
			$('.mystickyelement-credit').hide();
			$('.mystickyelements-fixed').hide();
		}
		if ( second_social_id != '' && second_social_id !== 'myStickyelements-preview-contact' && $( 'li.mystickyelements-minimize' ).length !== 1  ) {
			$('.mystickyelements-position-' + position_device + 'left #' + second_social_id + ' .mystickyelements-social-icon').css('border-top-right-radius', '10px' );
			$('.mystickyelements-position-' + position_device + 'right #' + second_social_id + ' .mystickyelements-social-icon').css('border-top-left-radius', '10px' );
			$('.mystickyelements-position-' + position_device + 'bottom #' + second_social_id + ' .mystickyelements-social-icon').css('border-top-left-radius', '10px' );
		}
	}
})( jQuery );