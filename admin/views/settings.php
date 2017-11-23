<?php

use CrankWheel\API;

if ( ! defined( 'WPINC' ) ) {
	die;
}

$cw = new API\CW_API(); ?>

<div class="wrap">
	<div class="cw-section">
		<h3><?php _e('CrankWheel Instant Demos for WordPress', 'crankwheel'); ?></h3>
		<p><?php _e('Demo to your prospects instantly from your website using screen sharing without the regular setup friction. <a href="http://crankwheel.com/instant-demos/#let-me-show-you" target="_blank">See how it works</a>', 'crankwheel'); ?></p>
	</div>


	<div class="cw-section">
		<h3><?php _e('Setup', 'crankwheel'); ?></h3>

		<?php if ( ! $cw->is_connected() ) : ?>

			<p><?php _e('To get started with using Instant Demos, please connect to your CrankWheel account.', 'crankwheel'); ?></p>
			<p class="cw-connect-wrap">
				<button class="button-primary" data-crankwheel="connect"><?php _e('Connect to Existing Account', 'crankwheel'); ?></button>
				<small><?php _e('Don\'t have an account? <a href="http://crankwheel.com/signup/trial" target="_blank">Click here to Sign Up</a>', 'crankwheel'); ?></small>
			</p>

		<?php else: ?>

			<p class="status"><?php _e('You are <span>connected</span>.', 'crankwheel'); ?></p>
			<p><?php _e('Add this link to any button element on your webpage to enable users to request instant access', 'crankwheel'); ?></p>

			<div class="cw-link-wrap">
				<div class="span-input">
					<input type="url" readonly="true" name="cw_link" class="cw-link-ipunt" value="<?php echo $cw->get_link(); ?>">
				</div>

				<div class="span-button">
					<button class="button-primary cw-link-copy"><?php _e('Copy', 'crankwheel'); ?></button>
				</div>
			</div>
			<p><button class="button" data-crankwheel="disconnect"><?php _e('Disconnect this account', 'crankwheel'); ?></button></p>

		<?php endif; ?>

        <?php
            /*
            This actually attempts to store the settings received via JavaScript, then reload.
            */
            if ( ! $cw->is_connected() ) :
                parse_str($_SERVER['QUERY_STRING'], $query_params);
                if ( array_key_exists('cwresponse', $query_params) ) :
                    $cw->authenticate($query_params['cwresponse']);
                    if ( $cw->is_connected() ) :
                        ?> <script type="text/javascript">location.reload();</script> <?php
                    endif;
                endif;
            endif;
        ?>
	</div>
	<div class="cw-async-status"></div>
</div>
