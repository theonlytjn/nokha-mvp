<?php

if (!defined('ABSPATH')) {
    die;
}

class WP_Jobsearch_Package_Expire_Alerts {

    public function __construct() {

        add_action('jobsearch_expire_pkgs_alert_cron', array($this, 'expire_pkgs_alert_cron'));
    }

    public function expire_pkgs_alert_cron() {
        if(function_exists('wc_get_orders')){
            $ordrs_posts = wc_get_orders(array( 
                'status' => array('wc-completed'),
                //'return' => 'ids',
                'jobsearch_order_attach_with' => 'package',
                'jobsearch_ordrexpiry_mail_sent' => 'sent',
            ));
        }
        
        if (!empty($ordrs_posts)) { 
            foreach ($ordrs_posts as $order) {
                $order_id = $order->get_id();

                //'customer_id' => $user_id,
                $jobsearch_order_attach_with    = $order->get_meta('jobsearch_order_attach_with');
                $jobsearch_ordrexpiry_mail_sent    = $order->get_meta('jobsearch_ordrexpiry_mail_sent');

                if (
                    $jobsearch_order_attach_with == 'package' && 
                    (empty($jobsearch_ordrexpiry_mail_sent) || $jobsearch_ordrexpiry_mail_sent !== 'sent')
                ) {
                    $package_type = get_post_meta($order_id, 'package_type', true);
                    
                    $order_user_id = get_post_meta($order_id, 'jobsearch_order_user', true);
                    
                    $order_user_obj = get_user_by('ID', $order_user_id);
                    
                    if ($package_type == 'job' && jobsearch_pckg_order_is_expired($order_id)) {
                        do_action('jobsearch_jobs_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                    if ($package_type == 'featured_jobs' && jobsearch_fjobs_pckg_order_is_expired($order_id)) {
                        do_action('jobsearch_fjobs_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                    if ($package_type == 'cv' && jobsearch_cv_pckg_order_is_expired($order_id)) {
                        do_action('jobsearch_cvs_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                    if ($package_type == 'candidate' && jobsearch_app_pckg_order_is_expired($order_id)) {
                        do_action('jobsearch_candidates_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                    if ($package_type == 'promote_profile' && jobsearch_promote_profile_pkg_is_expired($order_id)) {
                        do_action('jobsearch_promprofile_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                    if ($package_type == 'urgent_pkg' && jobsearch_member_urgent_pkg_is_expired($order_id)) {
                        do_action('jobsearch_urgent_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                    if ($package_type == 'candidate_profile' && jobsearch_cand_profile_pkg_is_expired($order_id)) {
                        do_action('jobsearch_candprofile_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                    if ($package_type == 'employer_profile' && jobsearch_emp_profile_pkg_is_expired($order_id)) {
                        do_action('jobsearch_empprofile_package_expire_email', $order_user_obj, $order_id);
                        update_post_meta($order_id, 'jobsearch_ordrexpiry_mail_sent', 'sent');
                    }
                }
            }
        }
    }

}

new WP_Jobsearch_Package_Expire_Alerts();
