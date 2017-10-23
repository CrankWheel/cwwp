/* global cw */

export default {
	init() {

		/**
		 * Close oAuth Frame on postMessage received from CrankWheel API
		 * Closing is done with delay of 500ms for smoother experience
		 */
		window.addEventListener('message', function(e) {

			if ( e.data === 'closeFrame' ) {

				setTimeout( function() {

					e.source.close();
					location.reload();
				}, 500);
			}
		});


		/**
		 * Function to open oAuth PopUp Window
		 */
		$.oauthPopup = function(options) {

			options.callback = options.callback || function(){ window.location.reload(); };

			var that = this;
			that._oauthWindow = window.open(options.path, 'CrankWheel', 'location=0,status=0,width=600,height=400');
		};
	},
	finalize() {


		$('button[data-crankwheel]').click( function( event ) {
			if(event.preventDefault) { event.preventDefault(); }

			var button = $(this),
				requestURL = cw.cw_api + '?token=' + cw.nonce + '&displayname=' + cw.plugin + '&callbackurl=' + cw.json_api;


			button.prop('disabled', true).text('Please Wait');

			/**
			 * Open oAuth Window
			 */
			if ( button.data('crankwheel') === 'connect' ) {

				$.oauthPopup({
					path: requestURL,
				});


				/**
				 * Save nonce for request validation
				 * @type {[type]}
				 */
				$.ajax({
					url: cw.ajax_url,
					type: 'post',
					dataType: 'json',
					data: {
						nonce: cw.nonce,
						action: 'do_cw_save_nonce',
					},
				});
			}
			/**
			 * Disconnect from CrankWheel API
			 */
			else if ( button.data('crankwheel') === 'disconnect' ) {

				$.ajax({
					url: cw.ajax_url,
					type: 'post',
					dataType: 'json',
					data: {
						nonce: cw.nonce,
						action: 'do_cw_disconnect',
					},
					success: function( data ) {

						if ( data.code === 200 ) {

							location.reload();
						}
						else if ( 'msg' in data ) {

							$('.cw-async-status').html( data.message );
						}
					},
					error: function( MLHttpRequest, textStatus ) {

						$('.cw-async-status').html( textStatus );
					},
				});
			}
		});



		/**
		 * Copy link to clipboard
		 */
		var copyBtn = document.querySelector('.cw-link-copy');

		if ( copyBtn ) {

			copyBtn.addEventListener('click', function() {

				var copyLink = document.querySelector('.cw-link-ipunt');
				copyLink.select();

				try {

					var successful = document.execCommand('copy');
					var msg = successful ? 'Copied' : 'Unable to copy';
					$('<span class="span-copy">'+ msg +'</div>').appendTo( $('.cw-link-wrap') ).fadeOut(2000);

				}
				catch (err) {

					$('<span class="span-copy">Unable to copy</div>').appendTo( $('.cw-link-wrap') ).fadeOut(2000);
				}
			});
		}
	},
};
