(function($) {

	var $context = $('#mc4wp-admin');

	function proOnlyNotice() {

		// prevent checking of radio buttons
		if( typeof this.checked === 'boolean' ) {
			this.checked = false;
		}

		alert( mc4wp.strings.proOnlyNotice );
		event.stopPropagation();
	}

	$context.find(".pro-feature, .pro-feature label, .pro-feature :radio").click(proOnlyNotice);

	$context.find('input[name$="[show_at_woocommerce_checkout]"]').change(function() {
		$context.find('tr#woocommerce-settings').toggle( $(this).prop( 'checked') );
	});

	// Allow tabs inside the form mark-up
	$(document).delegate('#mc4wpformmarkup', 'keydown', function(e) {
		var keyCode = e.keyCode || e.which;

		if (keyCode == 9) {
			e.preventDefault();
			var start = this.selectionStart;
			var end = this.selectionEnd;

			// set textarea value to: text before caret + tab + text after caret
			$(this).val($(this).val().substring(0, start)
			+ "\t"
			+ $(this).val().substring(end));

			// put caret at right position again
			this.selectionStart =
				this.selectionEnd = start + 1;
		}
	});


	// Add buttons to QTags editor
	(function() {

		if ( typeof(QTags) == 'undefined' ) {
			return;
		}

		QTags.addButton( 'mc4wp_paragraph', '<p>', '<p>', '</p>', 'paragraph', 'Paragraph tag', 1 );
		QTags.addButton( 'mc4wp_label', 'label', '<label>', '</label>', 'label', 'Label tag', 2 );
		QTags.addButton( 'mc4wp_response', 'form response', '{response}', '', 'response', 'Shows the form response' );
		QTags.addButton( 'mc4wp_subscriber_count', '# of subscribers', '{subscriber_count}', '', 'subscribers', 'Shows number of subscribers of selected list(s)' );

		if( window.mc4wp.hasCaptchaPlugin == true ) {
			QTags.addButton( 'mc4wp_captcha', 'CAPTCHA', '{captcha}', '', 'captcha', 'Display a CAPTCHA field' );
		}
	})();

})(jQuery);

