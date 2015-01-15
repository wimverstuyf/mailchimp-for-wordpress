<?php 
if( ! defined("MC4WP_LITE_VERSION") ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}
?>
<div id="mc4wp-fw" class="mc4wp-well">

	<h4 class="mc4wp-title"><?php _e( 'Add a new field', 'mailchimp-for-wp' ); ?></h4>

	<p><?php _e( 'Use the tool below to generate the HTML for your form fields.', 'mailchimp-for-wp' ); ?></p>

	<div id="mc4wp-field-selector"></div>
	<div id="mc4wp-field-helper"></div>

	<noscript><?php _e( 'Please enable JavaScript if you want to use the field helper.', 'mailchimp-for-wp' ); ?></noscript>


</div>