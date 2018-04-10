<?php
defined( 'ABSPATH' ) or exit;
?>
<h2><?php _e( 'Add dynamic form variable', 'mailchimp-for-wp' ); ?></h2>
<p>
	<?php echo sprintf( __( 'The following list of variables can be used to <a href="%s">add some dynamic content to your form or success and error messages</a>.', 'mailchimp-for-wp' ), 'https://kb.mc4wp.com/using-variables-in-your-form-or-messages/' ) . ' ' . __( 'This allows you to personalise your form or response messages.', 'mailchimp-for-wp' ); ?>
</p>

<table class="widefat striped">
	<tbody>
		<tr>
		<td><code>{data key='UTM_SOURCE'}</code></td>
		<td>Data from the URL or a submitted form.</td>
	</tr>
	<tr>
		<td><code>{cookie name='my_cookie'}</code></td>
		<td>Data from a cookie.</td>
	</tr>
	<tr>
		<td><code>{subscriber_count}</code></td>
		<td>Number of subscribers on the selected list(s)</td>
	</tr>
	<tr>
		<td><code>{email}</code></td>
		<td>Email address of the current visitor (if known).</td>
	</tr>
	<tr>
		<td><code>{current_url}</code></td>
		<td>URL of the current page.</td>
	</tr>
	<tr>
		<td><code>{current_path}</code></td>
		<td>Path of the current page.</td>
	</tr>
	<tr>
		<td><code>{date}</code></td>
		<td>Current date.</td>
	</tr>
	<tr>
		<td><code>{time}</code></td>
		<td>Current time.</td>
	</tr>
	<tr>
		<td><code>{language}</code></td>
		<td>Current site language.</td>
	</tr>
	<tr>
		<td><code>{ip}</code></td>
		<td>Visitor's IP address. Example: 127.0.0.1.</td>
	</tr>
	<tr>
		<td><code>{user property='user_email'}</code></td>
		<td>Property of the currently logged-in user.</td>
	</tr>
	<tr>
		<td><code>{post property='ID'}</code></td>
		<td>Property of the current page or post.</td>
	</tr>
</tbody></table>
