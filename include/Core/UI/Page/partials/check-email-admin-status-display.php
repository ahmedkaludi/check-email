<div id="checkemail" class="wrap">
    <?php if ( isset( $_POST["checkemail_to"]) && !empty($_POST["checkemail_to"]) ): ?>
            <div class="updated">
                <?php if (!empty($headers)): ?>		
                    <p><?php esc_html_e( 'The test email has been sent by WordPress. Please note this does NOT mean it has been delivered. See <a href="http://codex.wordpress.org/Function_Reference/wp_mail">wp_mail in the Codex</a> for more information. The headers sent were:', "check-email" )?></p>
                    <pre><?php echo str_replace( chr( 10 ), '\n' . "\n", str_replace( chr( 13 ), '\r', $headers ) ); ?></pre>
                <?php else: ?>
                    <p><?php esc_html_e( 'Security check failed', 'check-email' ) ?></p>
                <?php endif; ?> 
            </div>
    <?php endif; ?>
    <h2><?php esc_html_e( 'Check & Log Email', 'check-email' ) ?></h2><hr />
    <h3><?php esc_html_e( 'Current mail settings', 'check-email' ) ?></h3>
    <ul>
        <?php if (isset($phpmailer->Mailer) && !empty($phpmailer->Mailer)): ?>
        <li><?php esc_html_e( 'Mailer:', 'check-email' ) ?> <strong><?php echo $phpmailer->Mailer ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->Sendmail) && !empty($phpmailer->Sendmail)): ?>
        <li><?php esc_html_e( 'SendMail path:', 'check-email' ) ?> <strong><?php echo $phpmailer->Sendmail ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->Host) && !empty($phpmailer->Host)): ?>
        <li><?php esc_html_e( 'Host:', 'check-email' ) ?> <strong><?php echo $phpmailer->Host ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->Port) && !empty($phpmailer->Port)): ?>
        <li><?php esc_html_e( 'Port:', 'check-email' ) ?> <strong><?php echo $phpmailer->Port ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->CharSet) && !empty($phpmailer->CharSet)): ?>
        <li><?php esc_html_e( 'CharSet:', 'check-email' ) ?> <strong><?php echo $phpmailer->CharSet ?></strong></li>
        <?php endif; ?>
        <?php if (isset($phpmailer->ContentType) && !empty($phpmailer->ContentType)): ?>
        <li><?php esc_html_e( 'Content-Type:', 'check-email' ) ?> <strong><?php echo $phpmailer->ContentType ?></strong></li>
        <?php endif; ?>
    </ul>
    
    <h3><?php esc_html_e( 'Send a test email', 'check-email' ) ?></h3><hr />
    <form action="<?php echo get_admin_url() . 'admin.php?page=check-email-status' ?>" method="post">
        <p>
            <label for="checkemail_to"><?php esc_html_e( 'Send test email to', 'check-email' ) ?></label>
            <input type="text" name="checkemail_to" id="checkemail_to" class="text" value="<?php echo (isset($_POST["checkemail_to"]) ? esc_attr( $_POST["checkemail_to"] ) : '' ) ?>"/>
        </p>
        <p>
            <label for="checkemail_autoheaders"><?php esc_html_e( 'Use standard headers', 'check-email' ) ?></label>
            <input type="radio" id="checkemail_autoheaders" name="checkemail_headers" value="auto" <?php echo (!isset($_POST["checkemail_headers"]) || $_POST["checkemail_headers"] == "auto" ? 'checked="checked"' : '' ) ?>/>
        </p>
        <div id="autoheaders" class="<?php echo ( isset($_POST['checkemail_headers']) && $_POST['checkemail_headers'] == 'custom' ? 'checkemail-hide' : '' ) ?>">
            <?php printf(esc_html__( 'MIME-Version: %s', 'check-email'), '1.0' ) ?><br />
            <?php printf(esc_html__( 'From: %s', 'check-email'), $from_email ) ?><br />
            <?php printf(esc_html__( 'Content-Type: %s', 'check-email'), 'text/plain; charset="' . get_option( 'blog_charset' ) . '"' ) ?>
        </div>
        <p>
            <label for='checkemail_customheaders'><?php esc_html_e( 'Use custom headers', 'check-email' ) ?></label>
            <input type="radio" id="checkemail_customheaders" name="checkemail_headers" value="custom" <?php echo (isset($_POST['checkemail_headers']) && $_POST['checkemail_headers'] == 'custom' ? 'checked="checked"' : '') ?>/>
        </p>
        <div id="customheaders" class="<?php echo ( !isset($_POST['checkemail_headers']) || $_POST['checkemail_headers'] == 'auto' ? 'checkemail-hide' : '' ) ?>"><br />
            <h3><?php esc_html_e( 'Set your custom headers below', 'check-email' ) ?></h3><hr />
            <p>
                <label for="checkemail_mime"><?php esc_html_e( 'MIME Version', 'check-email' ) ?></label>
                <input type="text" name="checkemail_mime" id="checkemail_mime" value="<?php echo (isset( $_POST['checkemail_mime'] ) ? esc_attr($_POST['checkemail_mime']) : '1.0') ?>"/>
            </p>
            <p>
                <label for="checkemail_type"><?php esc_html_e( 'Content type', 'check-email' ) ?></label>
                <input type="text" name="checkemail_type" id="checkemail_type" value="<?php echo ( isset( $_POST['checkemail_type'] ) ? esc_attr( $_POST['checkemail_type'] ) : 'text/html; charset=iso-8859-1' ) ?>"/>
            </p>
            <p>
                <label for="checkemail_from"><?php esc_html_e( 'From', 'check-email' ) ?></label>
                <input type="text" name="checkemail_from" id="checkemail_from" value="<?php echo ( isset( $_POST['checkemail_from'] ) ? esc_attr( $_POST['checkemail_from'] ) : $from_email) ?>" class="text" />
            </p>
            <p>
                <label for="checkemail_cc"><?php esc_html_e( 'CC', 'check-email' ) ?></label>
                <textarea name="checkemail_cc" id="checkemail_cc" cols="30" rows="4" class="text">
                    <?php if ( isset( $_POST['checkemail_cc'] ) ): ?>
                            <?php echo esc_textarea( $_POST['checkemail_cc'] ); ?>
                    <?php endif ?>
                </textarea>
            </p>
            <p>
                <label for="checkemail_break_n"><?php esc_html_e( 'Header line break type', 'check-email' ) ?></label>
                <input type="radio" name="checkemail_break" id="checkemail_break_n" value="\n" <?php echo ( !isset( $_POST['checkemail_break'] ) || $_POST['checkemail_break'] == '\n' ? 'checkeed="checked"' : '') ?>/><?php esc_html_e('\n', 'check-email') ?>
                <input type="radio" name="checkemail_break" id="checkemail_break_rn" value="\r\n" <?php echo ( isset( $_POST['checkemail_break'] ) && $_POST['checkemail_break'] == '\r\n' ?  'checkeed="checked"' : '') ?>/><?php esc_html_e('\r\n', 'check-email') ?>
            </p>
        </div>
        <p>
            <label for="checkemail_go" class="checkemail-hide"><?php esc_html_e( 'Send', 'check-email' ) ?></label>
            <input type="submit" name="checkemail_go" id="checkemail_go" class="button-primary" value="<?php esc_html_e( 'Send test email', 'check-email' ) ?>" />
        </p>
        <?php echo wp_nonce_field( 'checkemail' ); ?>
    </form>
</div>
