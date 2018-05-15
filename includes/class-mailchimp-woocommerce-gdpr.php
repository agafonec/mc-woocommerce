<?php

class MailChimp_WooCommerce_GDPR
{
    /**
     * Privacy policy
     */
    public function privacy_policy()
    {
        if (function_exists( 'wp_add_privacy_policy_content')) {
            $content = sprintf(
                __( 'When shopping, we keep a temporary record of your cart contents, and your email address. 
This record is kept to repopulate the contents of your cart if you switch devices or needed to come back another day. Read our privacy policy <a href="%s">here</a>.',
                    'mailchimp-woocommerce' ),
                'https://mailchimp.com/legal/privacy/'
            );

            wp_add_privacy_policy_content('MailChimp for WooCommerce', wp_kses_post(wpautop($content, false)));
        }
    }

    /**
     * @param array $exporters
     * @return mixed
     */
    public function register_exporter($exporters)
    {
        $exporters['mailchimp-woocommerce'] = array(
            'exporter_friendly_name' => __('MailChimp for WooCommerce'),
            'callback'               => array($this, 'export'),
        );
        return $exporters;
    }

    /**
     * @param array $erasers
     * @return mixed
     */
    public function register_eraser($erasers)
    {
        $erasers['mailchimp-woocommerce'] = array(
            'exporter_friendly_name' => __('MailChimp for WooCommerce'),
            'callback'               => array($this, 'erase'),
        );
        return $erasers;
    }

    /**
     * @param $email_address
     * @param int $page
     * @return array
     */
    public function export($email_address, $page = 1)
    {
        global $wpdb;

        $uid = md5(trim(strtolower($email_address)));

        $data = array();

        if (get_site_option('mailchimp_woocommerce_db_mailchimp_carts', false)) {
            $table = "{$wpdb->prefix}mailchimp_carts";
            $statement = "SELECT * FROM $table WHERE id = %s";
            $sql = $wpdb->prepare($statement, $uid);

            if (($saved_cart = $wpdb->get_row($sql)) && !empty($saved_cart)) {
                $data = array('name' => __('Email Address'), 'value' => $email_address);
            }
        }

        return array(
            'data' => array(
                'group_id'    => 'mailchimp_cart',
                'group_label' => __('Shopping Cart Data'),
                'item_id'     => $uid,
                'data'        => $data,
            ),
            'done' => true,
        );
    }

    public function erase($email_address, $page = 1)
    {
        global $wpdb;

        $uid = md5(trim(strtolower($email_address)));
        $count = 0;

        if (get_site_option('mailchimp_woocommerce_db_mailchimp_carts', false)) {
            $table = "{$wpdb->prefix}mailchimp_carts";
            $sql = $wpdb->prepare("DELETE FROM $table WHERE id = %s", $uid);
            $count = $wpdb->query($sql);
        }

        return array(
            'items_removed' => (int) $count,
            'items_retained' => false, // always false in this example
            'messages' => array(), // no messages in this example
            'done' => true,
        );
    }
}
