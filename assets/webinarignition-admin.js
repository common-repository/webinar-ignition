(function($) {

	document.addEventListener('DOMContentLoaded', function() {
		var dismissLink = document.getElementById('dismiss_old_webinars_notification');
		if (dismissLink) {
			dismissLink.addEventListener('click', function(event) {
				event.preventDefault();
				var notification = document.getElementById('old_webinars_notification');
				if (notification) {
					notification.style.display = 'none';
					// Send AJAX request to mark notification as dismissed
					var xhr = new XMLHttpRequest();
					xhr.open('GET', WEBINARIGNITION.ajax_url + '?action=dismiss_old_webinars_notification');
					xhr.send();
				}
			});
		}
	});

	
	jQuery(document).ready(function($){
		$('.update-message').find('p').each( function(){
			if( $(this).text().length < 1 ){
				$(this).remove();
			}
		});
	});


	$(document).on('ready', function() {
		var license = window.WEBINARIGNITION.license,
			ajax_url = window.WEBINARIGNITION.ajax_url,
			nonce = window.WEBINARIGNITION.nonce;


		if ( license.is_trial === null ) {
			/**
			 * Dashboard license status animation.
			 */
			var unlock_form_container = $('#unlockFormsContainer');

			if ( unlock_form_container.length ) {
				unlock_form_container.on('shown.bs.collapse', function() {
					$("#unlockFormsContainer #unlockFormsContainer").remove(); //Remove duplicate element

					$('html, body').animate({
						scrollTop: $(this).offset().top
					}, 2000);
				});
			}
		}

		jQuery(document).on('click', '#webinarignition-smtp-failed-notice .notice-dismiss', function() {
			jQuery.ajax({
				url: ajax_url,
				data: {
					action: 'webinarignition_delete_smtp_failed_notice',
					security: nonce
				}
			});
		});

		jQuery(document).on( 'click', '#webinarignition-smtp-notice .notice-dismiss', function() {
			jQuery.ajax({
				url: ajax_url,
				data: {
					action: 'webinarignition_delete_smtp_updated_status',
					security: nonce
				}
			});
		});

		var dismissLink = document.getElementById('dismiss_old_webinars_notification');
		if (dismissLink) {
			dismissLink.addEventListener('click', function(event) {
				event.preventDefault();
				var notification = document.getElementById('old_webinars_notification');
				if (notification) {
					notification.style.display = 'none';
					// Send AJAX request to mark notification as dismissed
					// ! TODO: convert this to a wp ajax req.
					var xhr = new XMLHttpRequest();
					xhr.open('GET', ajaxurl + '?action=dismiss_old_webinars_notification');
					xhr.send();
				}
			});
		}

		$('.update-message').find('p').each( function(){
			if( $(this).text().length < 1 ){
				$(this).remove();
			}
		});

		var progress = parseInt( $('#webinarignition-reg-progress-counter').data('progress') );
		
		$(".meter > span").each(function () {
			$(this).animate({
				width: progress + '%'
			}, 4000 );
		});

		// edit.php
		$('#editApp').on('change', function(event){
			if (document.readyState === 'complete'){
				$(this).addClass("dirty")
			}else{
			}
		});


		window.onbeforeunload = function() {
			if (tinyMCE.activeEditor.isDirty()) {
				return 'There is unsaved data.';
			}
			return undefined;
		}

		if ( 'free' === license.switch ) {
			var flag = true;
			$('#webinarignition-save-webhook').on('click', function(e) {
				if ( flag ) {
					e.preventDefault();
				}

				$.ajax({
					url: ajax_url,
					type: 'POST',
					data: {
						action: 'webinarignition/display_popup_for_free_plan',
						nonce: nonce,
					},
					success: function({ data }) {
						$('#wpbody').append(data.html);
						flag = false;
					},
				}).done(function() {
					$('#go-ahead-with-free-plan').on('click', function() {
						$('#webinarignition-free-plan-popup-on-saving').fadeOut();
						$('#webinarignition-save-webhook').click();
					})
				})
			})
		}

	});
})(jQuery)