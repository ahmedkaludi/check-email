<div id="checkemail" class="wrap">
    <?php if ( isset( $_POST["checkemail_to"]) && !empty($_POST["checkemail_to"]) ): ?>
            <div class="updated">
                <?php if (!empty($headers)): ?>		
                    <p><?php _e( 'The test email has been sent by WordPress. Please note this does NOT mean it has been delivered. See <a href="http://codex.wordpress.org/Function_Reference/wp_mail">wp_mail in the Codex</a> for more information. The headers sent were:', "check-email" )?></p>
                    <pre><?php echo str_replace( chr( 10 ), '\n' . "\n", str_replace( chr( 13 ), '\r', $headers ) ); ?></pre>
                <?php else: ?>
                    <p><?php _e( 'Security check failed', 'check-email' ) ?></p>
                <?php endif; ?> 
            </div>
    <?php endif; ?>
    <h2><?php _e( 'Check & Log Email', 'check-email' ) ?></h2><hr />
    <h3><?php _e( 'Current mail settings', 'check-email' ) ?></h3>
    <ul>
        <?php if (isset($phpmailer->Mailer) && !empty($phpmailer->Mailer)): ?>
        <li><?php _e( 'Mailer:', 'check-email' ) ?> <strong><?php echo $phpmailer->Mailer ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->Sendmail) && !empty($phpmailer->Sendmail)): ?>
        <li><?php _e( 'SendMail path:', 'check-email' ) ?> <strong><?php echo $phpmailer->Sendmail ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->Host) && !empty($phpmailer->Host)): ?>
        <li><?php _e( 'Host:', 'check-email' ) ?> <strong><?php echo $phpmailer->Host ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->Port) && !empty($phpmailer->Port)): ?>
        <li><?php _e( 'Port:', 'check-email' ) ?> <strong><?php echo $phpmailer->Port ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->CharSet) && !empty($phpmailer->CharSet)): ?>
        <li><?php _e( 'CharSet:', 'check-email' ) ?> <strong><?php echo $phpmailer->CharSet ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->ContentType) && !empty($phpmailer->ContentType)): ?>
        <li><?php _e( 'Content-Type:', 'check-email' ) ?> <strong><?php echo $phpmailer->ContentType ?></strong></li>
        <?php endif; ?>
    </ul>
    
    <h3><?php _e( 'Send a test email', 'check-email' ) ?></h3><hr />
    <form action="<?php echo get_admin_url() . 'admin.php?page=check-email-status' ?>" method="post">
        <p>
            <label for="checkemail_to"><?php _e( 'Send test email to', 'check-email' ) ?></label>
            <input type="text" name="checkemail_to" id="checkemail_to" class="text" value="<?php echo (isset($_POST["checkemail_to"]) ? esc_attr( $_POST["checkemail_to"] ) : '' ) ?>"/>
        </p>
        <p>
            <label for="checkemail_autoheaders"><?php _e( 'Use standard headers', 'check-email' ) ?></label>
            <input type="radio" id="checkemail_autoheaders" name="checkemail_headers" value="auto" <?php echo (!isset($_POST["checkemail_headers"]) || $_POST["checkemail_headers"] == "auto" ? 'checked="checked"' : '' ) ?>/>
        </p>
        <div id="autoheaders" class="<?php echo ( isset($_POST['checkemail_headers']) && $_POST['checkemail_headers'] == 'custom' ? 'checkemail-hide' : '' ) ?>">
            <?php printf(__( 'MIME-Version: %s', 'check-email'), '1.0' ) ?><br />
            <?php printf(__( 'From: %s', 'check-email'), $from_email ) ?><br />
            <?php printf(__( 'Content-Type: %s', 'check-email'), 'text/plain; charset="' . get_option( 'blog_charset' ) . '"' ) ?>
        </div>
        <p>
            <label for='checkemail_customheaders'><?php _e( 'Use custom headers', 'check-email' ) ?></label>
            <input type="radio" id="checkemail_customheaders" name="checkemail_headers" value="custom" <?php echo (isset($_POST['checkemail_headers']) && $_POST['checkemail_headers'] == 'custom' ? 'checked="checked"' : '') ?>/>
        </p>
        <div id="customheaders" class="<?php echo ( !isset($_POST['checkemail_headers']) || $_POST['checkemail_headers'] == 'auto' ? 'checkemail-hide' : '' ) ?>"><br />
            <h3><?php _e( 'Set your custom headers below', 'check-email' ) ?></h3><hr />
            <p>
                <label for="checkemail_mime"><?php _e( 'MIME Version', 'check-email' ) ?></label>
                <input type="text" name="checkemail_mime" id="checkemail_mime" value="<?php echo (isset( $_POST['checkemail_mime'] ) ? esc_attr($_POST['checkemail_mime']) : '1.0') ?>"/>
            </p>
            <p>
                <label for="checkemail_type"><?php _e( 'Content type', 'check-email' ) ?></label>
                <input type="text" name="checkemail_type" id="checkemail_type" value="<?php echo ( isset( $_POST['checkemail_type'] ) ? esc_attr( $_POST['checkemail_type'] ) : 'text/html; charset=iso-8859-1' ) ?>"/>
            </p>
            <p>
                <label for="checkemail_from"><?php _e( 'From', 'check-email' ) ?></label>
                <input type="text" name="checkemail_from" id="checkemail_from" value="<?php echo ( isset( $_POST['checkemail_from'] ) ? esc_attr( $_POST['checkemail_from'] ) : $from_email) ?>" class="text" />
            </p>
            <p>
                <label for="checkemail_cc"><?php _e( 'CC', 'check-email' ) ?></label>
                <textarea name="checkemail_cc" id="checkemail_cc" cols="30" rows="4" class="text">
                    <?php if ( isset( $_POST['checkemail_cc'] ) ): ?>
                            <?php echo esc_textarea( $_POST['checkemail_cc'] ); ?>
                    <?php endif ?>
                </textarea>
            </p>
            <p>
                <label for="checkemail_break_n"><?php _e( 'Header line break type', 'check-email' ) ?></label>
                <input type="radio" name="checkemail_break" id="checkemail_break_n" value="\n" <?php echo ( !isset( $_POST['checkemail_break'] ) || $_POST['checkemail_break'] == '\n' ? 'checkeed="checked"' : '') ?>/><?php _e('\n', 'check-email') ?>
                <input type="radio" name="checkemail_break" id="checkemail_break_rn" value="\r\n" <?php echo ( isset( $_POST['checkemail_break'] ) && $_POST['checkemail_break'] == '\r\n' ?  'checkeed="checked"' : '') ?>/><?php _e('\r\n', 'check-email') ?>
            </p>
        </div>
        <p>
            <label for="checkemail_go" class="checkemail-hide"><?php _e( 'Send', 'check-email' ) ?></label>
            <input type="submit" name="checkemail_go" id="checkemail_go" class="button-primary" value="<?php _e( 'Send test email', 'check-email' ) ?>" />
        </p>
        <?php echo wp_nonce_field( 'checkemail' ); ?>
    </form>
</div>
