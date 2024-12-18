<style>
    .ck-container {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
    }
    .ck-card {
        flex: 1 1 calc(50% - 20px); /* Adjust card size */
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .ck-card h4 {
        margin: 0 0 10px;
        font-size: 18px;
        color: #333;
    }
    .ck-card .ck-status {
        display: inline-block;
        background-color: #d4edda;
        color: #155724;
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 14px;
        margin-bottom: 10px;
    }
    .ck-card p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    </style>

<div id="checkemail-dns" class="wrap ck_dns_main_div">
    <h2 style="width:450px;"><?php esc_html_e( 'Email Validate', 'check-email' ) ?></h2><hr />
    <input style="width:350px;" type="text" name="ck_email" id="ck_email" class="text" placeholder="<?php esc_html_e( 'Enter email address', 'check-email' ) ?>" value=""/>
    <input type="button" name="ck_submit_dns" id="ck_submit_email_verify" class="button-primary" value="<?php esc_attr_e( 'Email Verify', 'check-email' ) ?>" />
    </p>
    <div class="ck-container" id="ck_email_result">
    </div>
</div>
