( function( $ ) {
	"use strict";
	var social_id = '';
	var second_social_id = '';
	var $i = 0;
	var $flg = false;
	$(document).ready(function(){

		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			$(".mystickyelements-fixed").addClass("mystickyelements-on-click").removeClass("mystickyelements-on-hover");
		}

		$('#stickyelements-form').on( 'submit', function(event){
			event.preventDefault();

			$('#stickyelements-form .mse-input-error').removeClass("mse-input-error");
			$('#stickyelements-form .mse-input-message').remove();

			var totalErrors = 0;
			if($("#stickyelements-form .required").length) {
				$("#stickyelements-form .required").each(function(){
					if($.trim($(this).val()) == "") {
						$(this).addClass("mse-input-error");
						$(this).after("<span class='mse-input-message'>This field is required</span>");
						totalErrors++;
					}
				});
			}
			if($("#stickyelements-form .email.required:not(.mse-input-error)").length) {
				$("#stickyelements-form .email.required:not(.mse-input-error)").each(function(){
					var thisVal = $.trim($(this).val());
					var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
					if (!regex.test(thisVal)) {
						$(this).addClass("mse-input-error");
						$(this).after("<span class='mse-input-message'>Email address is not valid</span>");
						totalErrors++;
					}
				});
			}

			if(totalErrors == 0) {
				jQuery.ajax({
					url: mystickyelements.ajaxurl,
					type: 'post',
					data: 'action=mystickyelements_contact_form&' + jQuery("form#stickyelements-form").serialize() + '&security=' + mystickyelements.ajax_nonce,
					beforeSend: function() {
						$( '#stickyelements-submit-form' ).prop('disabled', true);
					},
					success: function (data) {
						$( '#stickyelements-submit-form' ).prop('disabled', false);
						$('#stickyelements-form .mse-input-error').removeClass("mse-input-error");
						$('#stickyelements-form .mse-input-message').remove();

						data = $.parseJSON(data);
						if(data.error == '1') {
							for(var i=0; i<data.errors.length; i++) {
								if(data.errors[i].key != "mse-form-error") {
									$('#stickyelements-form #' + data.errors[i].key).addClass("mse-input-error");
									$('#stickyelements-form #' + data.errors[i].key).after("<span class='mse-input-message'>" + data.errors[i].message + "</span>");
								} else {
									$("#mse-form-error").removeClass("mse-form-success-message").addClass("mse-form-error-message").show();
									$("#mse-form-error").html(data.errors[i].message);
								}
							}
						} else if(data.status == '0') {
							$("#mse-form-error").removeClass("mse-form-success-message").addClass("mse-form-error-message").show();
							$("#mse-form-error").html(data.message);
						} else {
							$("#mse-form-error").removeClass("mse-form-error-message").addClass("mse-form-success-message").show();
							$("#mse-form-error").html(data.message);

							$('#stickyelements-form input[type="text"], #stickyelements-form input[type="tel"], #stickyelements-form input[type="email"]').val("");
							$('#stickyelements-form textarea').val("");
						}
						setTimeout(function () {
							$('.contact-form-message').slideUp("slow");
						}, 5000);
						
						/* redirct Page After Submission */
						if ( data.status == 1 && data.redirect_link != '' ) {
							window.location = data.redirect_link;
						}
						return false;
					}
				});
			}
			return false;
		});

		/* Open tab on Click Event */
		$('.mystickyelements-on-click .mystickyelements-social-icon').on( 'click touch', function(event){
			if(!$(this).parent('li').hasClass("elements-active")) {
				$('.mystickyelements-on-click .elements-active').removeClass("elements-active");
				$(this).parent('li').addClass('elements-active');
			} else {
				$(this).parent('li').removeClass('elements-active');				
				event.preventDefault();
			}
		});
		
		/*close contact form on click close icon*/
		$( '.mystickyelements-on-hover ul li.mystickyelements-contact-form' ).hover( function(){
			$( this ).addClass( 'element-contact-active' );
		} );
		$( '.element-contact-close' ).on( 'click touch', function(event){
			$( '.mystickyelements-contact-form' ).removeClass('elements-active');				
			$( '.mystickyelements-contact-form' ).removeClass('element-contact-active');				
		});

		$('#stickyelements-form input, #stickyelements-form textarea ').on( 'keyup', function(event){
			if ($(this).val()){
				$(this).css('background-color', '#EFF5F8');
				$(this).css('border-color', '#7761DF');
			}

		});
		mystickyelements_border_radius();
		/* Minimize Sticky Elements  */
		//$('.mystickyelements-fixed').css( 'height', $('.mystickyelements-fixed').height() + 'px');
		$('li.mystickyelements-minimize').on('click',function(event){			
			var element_minimize, minimize_device, position_device,element_on_device;
			
			$( this ).toggleClass( 'element-minimize' );
			if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
				minimize_device = 'mobile';
				position_device = 'mobile-';
				element_on_device = 'element-mobile-on';
			} else {
				minimize_device = 'desktop';
				position_device = '';
				element_on_device = 'element-desktop-on';
			}
			if ( $( this ).hasClass( 'element-minimize' ) === true ) {			
				$.cookie("minimize_" + minimize_device, "minimize", { path: '/' });
				element_minimize = true;
			} else {				
				$.cookie("minimize_" + minimize_device, 'minimize_not', { path: '/' });
				element_minimize = false;
			}
			
			/* Left Position */
			$(".mystickyelements-position-" + position_device + "left ul li").each( function() {
				if ( $(this).hasClass( element_on_device ) == true ) {
					var mystickyelements_size = $( '.mystickyelements-fixed' ).hasClass( 'mystickyelements-size-large' );
					if ( mystickyelements_size == true ) {
						$(this).animate({
							width: 'toggle',
							left: ( element_minimize === true ) ? '-=80' : ''
						});
					} else {
						$(this).animate({
							width: 'toggle',
							left: ( element_minimize === true ) ? '-=50' : ''
						});
					}
				}
			});
			
			/* Right Position */
			$(".mystickyelements-position-" + position_device + "right ul li").each( function() {
				if ( $(this).hasClass( element_on_device ) == true ) {
					var mystickyelements_size = $( '.mystickyelements-fixed' ).hasClass( 'mystickyelements-size-large' );
					if ( mystickyelements_size == true ) {
						$(this).animate({
							width: 'toggle',
							left: ( element_minimize === true ) ? '+=80' : ''
						}, 300 , function() {
						});
					} else {
						$(this).animate({
							width: 'toggle',
							left: ( element_minimize === true ) ? '+=50' : ''
						}, 300 , function() {
						});
					}
				}
			});
						
			/* Bottom Position */
			$(".mystickyelements-position-" + position_device + "bottom ul li").each( function() {		
				if ( $(this).hasClass( element_on_device ) == true ) {
					$(this).css( 'position', 'relative' );
					var mystickyelements_size = $( '.mystickyelements-fixed' ).hasClass( 'mystickyelements-size-large' );
					if ( mystickyelements_size == true ) {
						$(this).animate({
							height: 'toggle',
							bottom: ( element_minimize === true ) ? '-=80' : '',
						}, 300 , function() {
							$(this).css( 'position', ( element_minimize === true ) ? 'relative' : 'static' );
						});
					} else {
						$(this).animate({
							height: 'toggle',
							bottom: ( element_minimize === true ) ? '-=60' : '',
						}, 300 , function() {
							$(this).css( 'position', ( element_minimize === true ) ? 'relative' : 'static' );
						});
					}			
				}
			});
			/* Top Position */
			$(".mystickyelements-position-" + position_device + "top ul li").each( function() {		
				if ( $(this).hasClass( element_on_device ) == true ) {
					$(this).css( 'position', 'relative' );
					var mystickyelements_size = $( '.mystickyelements-fixed' ).hasClass( 'mystickyelements-size-large' );
					if ( mystickyelements_size == true ) {
						$(this).animate({
							height: 'toggle',
							top: ( element_minimize === true ) ? '-=80' : '',
						}, 300 , function() {
							$(this).css( 'position', ( element_minimize === true ) ? 'relative' : 'static' );
						});
					} else {
						$(this).animate({
							height: 'toggle',
							top: ( element_minimize === true ) ? '-=60' : '',
						}, 300 , function() {
							$(this).css( 'position', ( element_minimize === true ) ? 'relative' : 'static' );
						});
					}			
				}
			});
			
			/*$(".mystickyelements-position-" + position_device + "left ul li").not('.mystickyelements-minimize').animate({
				width: 'toggle',
				left: ( element_minimize === true ) ? '-=50' : ''
			});

			$(".mystickyelements-position-" + position_device + "right ul li").not('.mystickyelements-minimize').animate({
				width: 'toggle',
				left: ( element_minimize === true ) ? '+=50' : ''
			}, 300 , function() {
			});*/

			/* Move arrow base on minimize */
			if ( $( 'span.mystickyelements-minimize' ).hasClass( 'minimize-position-' + position_device + 'left' ) === true ) {

				if ( $( 'li.mystickyelements-minimize' ).hasClass( 'element-minimize' ) === true) {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'left' ).html('&rarr;')
				} else {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'left' ).html('&larr;')
				}
			} else if ( $( 'span.mystickyelements-minimize' ).hasClass( 'minimize-position-' + position_device + 'bottom' ) === true ) {
				
				if ( $( 'li.mystickyelements-minimize' ).hasClass( 'element-minimize' ) === true) {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'bottom' ).html('&uarr;')
				} else {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'bottom' ).html('&darr;')
				}
			} else if ( $( 'span.mystickyelements-minimize' ).hasClass( 'minimize-position-' + position_device + 'top' ) === true ) {
				
				if ( $( 'li.mystickyelements-minimize' ).hasClass( 'element-minimize' ) === true) {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'top' ).html('&darr;')
				} else {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'top' ).html('&uarr;')
				}
			} else {				
				if ( $( 'li.mystickyelements-minimize' ).hasClass( 'element-minimize' ) === true) {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'right' ).html('&larr;')
				} else {
					$( '.mystickyelements-minimize.minimize-position-' + position_device + 'right' ).html('&rarr;')
				}
			}
		});
		/*iframe set*/
		$( '.mystickyelements-fixed ul li' ).each( function(){
			var custom_html_class = $( this ).hasClass( 'mystickyelements-custom-html-main' );
			if( custom_html_class ) {
				var custom_html_child_class = $( this ).hasClass( 'mystickyelements-custom-html-iframe' );
				if( custom_html_child_class ) {
					//var custom_html_iframe = $( this ).find( 'iframe' ).height();
					var custom_html_iframe = $( this ).find( '.mystickyelements-custom-html' ).height();
					var main_ul_height = $( '.mystickyelements-fixed ul' ).height();
					if( main_ul_height > custom_html_iframe ) {
						//$( this ).addClass( 'mystickyelements-custom-iframe-bottom' );
					}
				}
			}
		});

		setTimeout( function(){
			$( '.mystickyelements-entry-effect-fade.entry-effect,.mystickyelements-entry-effect-slide-in.entry-effect' ).css( 'transition', 'all 0s ease 0s' );
		}, 1000 );

		$( '.mystickyelements-fixed ul li' ).on( 'click', function(){
			if ( $( this ).hasClass( 'mystickyelements-custom-html-iframe' ) ) {
				$( '.mystickyelements-fixed' ).toggleClass( 'mystickyelements-custom-html-iframe-open' );
			} else {
				$( '.mystickyelements-fixed' ).removeClass( 'mystickyelements-custom-html-iframe-open' );
			}
		} );
		
		$( '.mystickyelements-fixed' ).addClass( 'entry-effect' );
		if ( $( window ).width() > 1024  ) {
			var mystickyelements_bottom_width = $( '.mystickyelements-position-bottom .mystickyelements-lists' ).width();
			if ( mystickyelements_bottom_width < 300 ) {
				$( '.mystickyelements-position-bottom .mystickyelements-contact-form .element-contact-form' ).width( '300' );
			}
		}
		mystickyelements_mobile_top_pos();
	});

	$( window ).resize(function() {
		mystickyelements_border_radius();
		mystickyelements_mobile_top_pos();
	});

	function mystickyelements_mobile_top_pos() {
		if ( $( window ).width() <= 1024  ) {
			if ( $( '.mystickyelements-fixed' ).hasClass( 'mystickyelements-position-mobile-top' ) ) {
				var mystickyelements_height = $( '.mystickyelements-fixed' ).height();
				$( 'html' ).attr( 'style', 'margin-top: ' + mystickyelements_height + 'px !important' );
			}
		} else {
			$( 'html' ).css( 'margin-top', '' );
		}
	}


	function mystickyelements_border_radius(){
		var position_device = '';
		if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			position_device = 'mobile-';
		} 
		var $mobile_bottom = 0;
		$('.mystickyelements-fixed ul li').each( function () {
			/* Check google analytics enable then add 'update-analytics' class */
			if ( mystickyelements.google_analytics === '1'  && $(this).hasClass('mystickyelements-minimize') !== true && $(this).attr('id') !== 'mystickyelements-contact-form' ) {
				if ( $(this).find( "a" ).length !== 0 ) {
					var elementname = $(this).attr("id").split('mystickyelements-social-');
					$(this).find( "a" ).addClass('update-analytics');
					$(this).find( "a" ).attr( 'data-social-slug', elementname[1]);
				} else {
					$(this).addClass('analytics-update');
				}
			}

			$('.mystickyelements-position-' + position_device + 'left #' + $(this).attr('id') + ' .mystickyelements-social-icon').css('border-radius','');
			$('.mystickyelements-position-' + position_device + 'right #' + $(this).attr('id') + ' .mystickyelements-social-icon').css('border-radius','');
			
			/* Check First LI */
			if ( $i == 0 ){
				if ( $( window ).width() > 1024 &&  !$(this).hasClass('element-desktop-on')){
					$flg = true;
				}
				if ( $( window ).width() < 1025 &&  !$(this).hasClass('element-mobile-on')){
					$flg = true;
				}
			}

			if ( $i == 1 && $flg === true) {
				if ( $( window ).width() > 1024){
					second_social_id = $(this).attr('id');
				}
				if ( $( window ).width() < 1025){
					second_social_id = $(this).attr('id');
				}
			}

			if ( $( window ).width() > 1024 &&  $(this).hasClass('element-desktop-on')){
				social_id = $(this).attr('id');
			}
			if ( $( window ).width() < 1025 &&  $(this).hasClass('element-mobile-on')){
				social_id = $(this).attr('id');
				$mobile_bottom++;
			}

			$i++;
		});
		
		$( '.mystickyelements-fixed.mystickyelements-position-mobile-bottom').addClass( 'mystickyelements-bottom-social-channel-' + $mobile_bottom );
		$( '.mystickyelements-fixed.mystickyelements-position-mobile-top').addClass( 'mystickyelements-top-social-channel-' + $mobile_bottom );
		
		if ( social_id != '' ) {
			if ( social_id === 'mystickyelements-contact-form' ){
				$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css('border-bottom-left-radius', '10px' );
				$('.mystickyelements-position-' + position_device + 'right #' + social_id + ' .mystickyelements-social-icon').css('border-top-left-radius', '10px' );
				$('.mystickyelements-position-' + position_device + 'bottom #' + social_id + ' .mystickyelements-social-icon').css('border-top-right-radius', '10px' );
				
				if( $( 'li.mystickyelements-minimize' ).length !== 1 ){
					$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css('border-bottom-right-radius', '10px' );
					$('.mystickyelements-position-' + position_device + 'right #' + social_id + ' .mystickyelements-social-icon').css('border-top-right-radius', '10px' );
				}
			} else if ( social_id !== 'mystickyelements-contact-form') {
				if ( $i=== 1 ) {
					$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css('border-radius', '0px 10px 10px 0' );
					$('.mystickyelements-position' + position_device + '-right #' + social_id + ' .mystickyelements-social-icon').css('border-radius', '10px 0 0 10px' );
				} else {
					$('.mystickyelements-position-' + position_device + 'left #' + social_id + ' .mystickyelements-social-icon').css('border-bottom-right-radius', '10px' );
					$('.mystickyelements-position-' + position_device + 'right #' + social_id + ' .mystickyelements-social-icon').css('border-bottom-left-radius', '10px' );
					$('.mystickyelements-position-' + position_device + 'bottom #' + social_id + ' .mystickyelements-social-icon').css('border-top-right-radius', '10px' );
				}
			}
		} else {
			$('.mystickyelement-credit').hide();
			$('.mystickyelements-fixed').hide();
		}
		if ( second_social_id != '' && second_social_id !== 'mystickyelements-contact-form' && $( 'li.mystickyelements-minimize' ).length !== 1  ) {
			$('.mystickyelements-position-' + position_device + 'left #' + second_social_id + ' .mystickyelements-social-icon').css('border-top-right-radius', '10px' );
			$('.mystickyelements-position-' + position_device + 'right #' + second_social_id + ' .mystickyelements-social-icon').css('border-top-left-radius', '10px' );
			$('.mystickyelements-position-' + position_device + 'bottom #' + second_social_id + ' .mystickyelements-social-icon').css('border-top-left-radius', '10px' );
		}
	}
})( jQuery );