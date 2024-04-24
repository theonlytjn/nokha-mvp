<?php

/**
 * @Manage Columns
 * @return
 *
 */
if (!class_exists('jobsearch_packages_functions')) {

    class jobsearch_packages_functions {

        // The Constructor
        public function __construct() {

            add_action('wp_ajax_jobsearch_cand_profile_pckg_subscribe', array($this, 'cand_profile_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_cand_profile_pckg_subscribe', array($this, 'cand_profile_pckg_subscribe'));

            add_action('wp_ajax_jobsearch_emp_profile_pckg_subscribe', array($this, 'emp_profile_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_emp_profile_pckg_subscribe', array($this, 'emp_profile_pckg_subscribe'));

            add_action('wp_ajax_jobsearch_user_cv_pckg_subscribe', array($this, 'user_cv_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_user_cv_pckg_subscribe', array($this, 'user_cv_pckg_subscribe'));

            add_action('wp_ajax_jobsearch_user_candidate_pckg_subscribe', array($this, 'user_candidate_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_user_candidate_pckg_subscribe', array($this, 'user_candidate_pckg_subscribe'));

            add_action('wp_ajax_jobsearch_user_job_pckg_subscribe', array($this, 'user_job_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_user_job_pckg_subscribe', array($this, 'user_job_pckg_subscribe'));

            add_action('wp_ajax_jobsearch_user_allinone_pckg_subscribe', array($this, 'allin_one_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_user_allinone_pckg_subscribe', array($this, 'allin_one_pckg_subscribe'));
            
            add_action('wp_ajax_jobsearch_user_promote_profile_pckg_sub', array($this, 'user_promote_profile_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_user_promote_profile_pckg_sub', array($this, 'user_promote_profile_pckg_subscribe'));
            
            add_action('wp_ajax_jobsearch_user_urgentsub_pckg_sub', array($this, 'user_urgentpkg_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_user_urgentsub_pckg_sub', array($this, 'user_urgentpkg_pckg_subscribe'));

            add_action('wp_ajax_jobsearch_user_fjobs_pckg_subscribe', array($this, 'user_fjobs_pckg_subscribe'));
            add_action('wp_ajax_nopriv_jobsearch_user_fjobs_pckg_subscribe', array($this, 'user_fjobs_pckg_subscribe'));

            //
            add_action('jobsearch_create_free_packg_order', array($this, 'create_free_packg_order'), 10, 2);

            //
            add_action('jobsearch_add_candidate_resume_id_to_order', array($this, 'add_candidate_resume_id_to_order'), 10, 2);
            //
            add_action('jobsearch_add_candidate_apply_job_id_to_order', array($this, 'add_candidate_apply_job_id_to_order'), 10, 2);
            //
            add_filter('jobsearch_free_package_restrict_multi_memberships', array($this, 'free_package_restrict_multi_memberships'), 10, 4);
            
            add_action('jobsearch_invite_apply_to_cand_add_to_pkg', array($this, 'add_candidate_invites_id_to_order'), 10, 2);
            
            add_filter('woocommerce_order_data_store_cpt_get_orders_query', array($this, 'jobsearch_shop_order_meta_query'), 999, 2);
        }
        
        public function jobsearch_shop_order_meta_query( $query, $query_vars ) {
            error_log('order query: '.print_r($query, true));
            error_log('order query_vars: '.print_r($query_vars, true));
           
            if ( !empty( $query_vars['jobsearch_order_attach_with'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'jobsearch_order_attach_with',
                    'value' => esc_attr( $query_vars['jobsearch_order_attach_with'] ),
                );
            } elseif( !empty( $query_vars['jobsearch_order_transaction_type'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'jobsearch_order_transaction_type',
                    'value' => esc_attr( $query_vars['jobsearch_order_transaction_type'] ),
                    'compare' => '=',
                );
            } elseif( !empty( $query_vars['jobsearch_order_user'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'jobsearch_order_user',
                    'value' => intval( $query_vars['jobsearch_order_user'] ),
                );
            } elseif( !empty( $query_vars['jobsearch_order_package'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'jobsearch_order_package',
                    'value' => intval( $query_vars['jobsearch_order_package'] ),
                );
            } elseif( !empty( $query_vars['contains_subscription'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'contains_subscription',
                    'value' => 'true',
                    'compare' => '=',
                );
            } elseif( !empty( $query_vars['package_type'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'package_type',
                    'value' =>  $query_vars['package_type'],
                    'compare' => 'IN',            
                );
            } elseif( !empty( $query_vars['package_expiry_timestamp'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'package_expiry_timestamp',
                    'value' =>  $query_vars['package_expiry_timestamp'],
                    'compare' => '>',            
                );
            } elseif( !empty( $query_vars['package_expiry_timestamp_greater'] ) ) {
                $query['meta_query'][] = array(
                    'key' => 'package_expiry_timestamp',
                    'value' =>  $query_vars['package_expiry_timestamp_greater'],
                    'compare' => '>',            
                );
            } elseif( !empty( $query_vars['jobsearch_ordrexpiry_mail_sent'] ) ) {
                $query['meta_query'][] = array(
                    'relation' => 'OR',
                    array(
                        'key' => 'jobsearch_ordrexpiry_mail_sent',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key' => 'jobsearch_ordrexpiry_mail_sent',
                        'value' => esc_attr( $query_vars['jobsearch_ordrexpiry_mail_sent'] ),
                        'compare' => '!=',
                    ),
                );
            }
            error_log('order query: '.print_r($query_vars, true));
            return $query;
        }

        public function allin_one_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                $employer_id = jobsearch_get_user_employer_id($user_id);
                
                $is_subscribed = jobsearch_allinpckg_is_subscribed($pkg_id, $user_id, 'jobs');
                if (!$is_subscribed) {
                    $is_subscribed = jobsearch_allinpckg_is_subscribed($pkg_id, $user_id, 'cvs');
                }
                
                if ($is_subscribed) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not an employer.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buyallinpkg_emp_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }
        
        public function cand_profile_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            if ($user_is_candidate) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                $candidate_id = jobsearch_get_user_candidate_id($user_id);
                if (jobsearch_cand_profile_pckg_is_subscribed($pkg_id, $user_id)) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_cand_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not a candidate.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buyprofilepkg_cand_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }

        public function emp_profile_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                $employer_id = jobsearch_get_user_employer_id($user_id);

                $is_subscribed = jobsearch_emprofpckg_is_subscribed($pkg_id, $user_id, 'jobs');
                if (!$is_subscribed) {
                    $is_subscribed = jobsearch_emprofpckg_is_subscribed($pkg_id, $user_id, 'cvs');
                }
                
                if ($is_subscribed) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not an employer.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buyprofilepkg_emp_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }
        
        public function user_cv_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                $employer_id = jobsearch_get_user_employer_id($user_id);
                if (jobsearch_cv_pckg_is_subscribed($pkg_id, $user_id)) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not an employer.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buycvpkg_emp_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }

        public function user_candidate_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            if ($user_is_candidate) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                $candidate_id = jobsearch_get_user_candidate_id($user_id);
                if (jobsearch_app_pckg_is_subscribed($pkg_id, $user_id)) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                echo json_encode(array('msg' => esc_html__('You are not a candidate.', 'wp-jobsearch'), 'error' => '1'));
                die;
            }
        }

        public function user_job_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                if (jobsearch_user_isemp_member($user_id)) {
                    $employer_id = jobsearch_user_isemp_member($user_id);
                } else {
                    $employer_id = jobsearch_get_user_employer_id($user_id);
                }
                if (jobsearch_pckg_is_subscribed($pkg_id, $user_id)) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not an employer.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buyjobpkg_emp_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }

        public function user_promote_profile_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer || $user_is_candidate) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                if (jobsearch_member_promote_profile_pkg_sub($pkg_id, $user_id)) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not allowed.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buypromotpkg_emp_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }

        public function user_urgentpkg_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer || $user_is_candidate) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                if (jobsearch_member_urgent_pkg_sub($pkg_id, $user_id)) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not allowed.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buyurgentpkg_emp_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }

        public function user_fjobs_pckg_subscribe() {
            $user_id = get_current_user_id();
            $user_is_employer = jobsearch_user_is_employer($user_id);
            if ($user_is_employer) {
                
                global $jobsearch_plugin_options;
                $user_dashboard_page = isset($jobsearch_plugin_options['user-dashboard-template-page']) ? $jobsearch_plugin_options['user-dashboard-template-page'] : '';
                $dashboard_page_id = jobsearch__get_post_id($user_dashboard_page, 'page');
                $dashboard_page_url = jobsearch_wpml_lang_page_permalink($dashboard_page_id, 'page');
                
                $pkg_id = isset($_POST['pkg_id']) ? $_POST['pkg_id'] : '';
                $employer_id = jobsearch_get_user_employer_id($user_id);
                if (jobsearch_fjobs_pckg_is_subscribed($pkg_id, $user_id)) {
                    $msgva = esc_html__('You have already subscribed to this package.', 'wp-jobsearch');
                    $msgva = apply_filters('jobsearch_buypkg_emp_alredybuy_msg', $msgva);
                    echo json_encode(array('msg' => $msgva, 'error' => '1'));
                    die;
                }
                if (!class_exists('WooCommerce')) {
                    echo json_encode(array('msg' => esc_html__('WooCommerce Plugin not exist.', 'wp-jobsearch'), 'error' => '1'));
                    die;
                }
                $pkg_charges_type = get_post_meta($pkg_id, 'jobsearch_field_charges_type', true);
                $pkg_attach_product = get_post_meta($pkg_id, 'jobsearch_package_product', true);
                if ($pkg_charges_type == 'paid') {
                    $package_product_obj = $pkg_attach_product != '' ? get_page_by_path($pkg_attach_product, 'OBJECT', 'product') : '';

                    if ($pkg_attach_product != '' && is_object($package_product_obj)) {
                        $product_id = $package_product_obj->ID;
                    } else {
                        echo json_encode(array('msg' => esc_html__('Selected Package Product not found.', 'wp-jobsearch'), 'error' => '1'));
                        die;
                    }

                    // add to cart and checkout
                    ob_start();
                    do_action('jobsearch_woocommerce_payment_checkout', $pkg_id, 'checkout_url');
                    $checkout_url = ob_get_clean();
                    echo json_encode(array('msg' => esc_html__('redirecting...', 'wp-jobsearch'), 'redirect_url' => $checkout_url));
                    die;
                } else {
                    do_action('jobsearch_create_free_packg_order', $pkg_id);
                    echo json_encode(array('msg' => esc_html__('Package Subscribed Successfully.', 'wp-jobsearch'), 'redirect_url' => add_query_arg(array('tab' => 'user-packages'), $dashboard_page_url)));
                    die;
                }
                //
            } else {
                $msgva = esc_html__('You are not an employer.', 'wp-jobsearch');
                $msgva = apply_filters('jobsearch_buyfjobpkg_emp_notalowerr_msg', $msgva);
                echo json_encode(array('msg' => $msgva, 'error' => '1'));
                die;
            }
        }

        public function create_free_packg_order($pckg_id, $member_type = 'employer') {
            global $woocommerce;

            $user_id = get_current_user_id();
            $user_obj = get_user_by('ID', $user_id);
            $user_displayname = $user_obj->display_name;
            $user_displayname = apply_filters('jobsearch_user_display_name', $user_displayname, $user_obj);
            $user_bio = $user_obj->description;
            $user_website = $user_obj->user_url;
            $user_email = $user_obj->user_email;
            $user_fname = $user_obj->first_name;
            $user_lname = $user_obj->last_name;

            $first_name = $user_fname;
            $last_name = $user_lname;
            if ($user_fname == '' && $user_lname == '') {
                $first_name = $user_displayname;
                $last_name = '';
            }

            if ($member_type == 'candidate') {
                $member_id = jobsearch_get_user_candidate_id($user_id);
            } else {
                $member_id = jobsearch_get_user_employer_id($user_id);
            }

            $user_phone = get_post_meta($member_id, 'jobsearch_field_user_phone', true);
            $user_address = get_post_meta($member_id, 'jobsearch_field_location_address', true);
            $user_city = get_post_meta($member_id, 'jobsearch_field_location_location3', true);
            $user_state = get_post_meta($member_id, 'jobsearch_field_location_location2', true);
            $user_country = get_post_meta($member_id, 'jobsearch_field_location_location1', true);

            $product_id = 0;
            $package_product = get_post_meta($pckg_id, 'jobsearch_package_product', true);
            $package_product_obj = $package_product != '' ? get_page_by_path($package_product, 'OBJECT', 'product') : '';
            if ($package_product != '' && is_object($package_product_obj)) {
                $product_id = $package_product_obj->ID;
            }
            
            apply_filters('jobsearch_free_package_restrict_multi_memberships', '', $user_id, $pckg_id);

            if ($product_id > 0 && get_post_type($product_id) == 'product') {

                $address = array(
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'company' => '',
                    'email' => $user_email,
                    'phone' => $user_phone,
                    'address_1' => $user_address,
                    'address_2' => '',
                    'city' => $user_city,
                    'state' => $user_state,
                    'postcode' => '',
                    'country' => $user_country
                );

                // Now we create the order
                $order = wc_create_order();

                $order->add_product(wc_get_product($product_id), 1);
                $order->set_address($address, 'billing');
                //
                $order->calculate_totals();
                $order_id = $order->get_ID();

                $order->update_status('processing');
                //
                update_post_meta($order_id, 'jobsearch_order_attach_with', 'package');
                update_post_meta($order_id, 'jobsearch_order_package', $pckg_id);
                update_post_meta($order_id, 'jobsearch_order_user', $user_id);
                update_post_meta($order_id, '_customer_user', $user_id);

                $order->update_meta_data('jobsearch_order_attach_with', 'package');
                $order->update_meta_data('jobsearch_order_package', $pckg_id);
                $order->update_meta_data('jobsearch_order_user', $user_id);
                $order->update_meta_data('jobsearch_order_transaction_type', 'free');
                $order->update_meta_data('_customer_user', $user_id);
                $order->set_customer_id($user_id);
                //
                // For free package
                update_post_meta($order_id, 'jobsearch_order_transaction_type', 'free');
                //
                $order->update_status('completed');

                $order->save();
            }
        }

        public function free_package_restrict_multi_memberships($html, $user_id, $pckg_id, $return_type = 'ajax') {
            global $jobsearch_plugin_options, $package_form_errs;
            $once_free_pckg = isset($jobsearch_plugin_options['once_free_pckg_switch']) ? $jobsearch_plugin_options['once_free_pckg_switch'] : '';
            
            $packg_type = get_post_meta($pckg_id, 'jobsearch_field_package_type', true);
            $packg_chrges_type = get_post_meta($pckg_id, 'jobsearch_field_charges_type', true);
            
            if ($once_free_pckg == 'on' && $packg_chrges_type == 'free') {

                if(function_exists('wc_get_orders')){
                    $pkgs_query_posts = wc_get_orders(array( 
                        'status' => array('wc-completed'),
                        'order' => 'DESC',
                        'orderby' => 'ID',
                        'customer_id' => $user_id,
                        'jobsearch_order_attach_with' => 'package',
                        'jobsearch_order_transaction_type' => 'free',
                        'jobsearch_order_user' => $user_id,
                        'package_type' => array($packg_type),
                    ));

                }

                if (!empty($pkgs_query_posts)) {

                    $order_exist = false;

                    foreach ($pkgs_query_posts as $order) {                        
                        //'customer_id' => $user_id,
                        $jobsearch_order_user    = $order->get_meta('jobsearch_order_user');
                        $jobsearch_order_attach_with    = $order->get_meta('jobsearch_order_attach_with');
                        $jobsearch_order_transaction_type    = $order->get_meta('jobsearch_order_transaction_type');
                        $jobsearch_order_user    = $order->get_meta('jobsearch_order_user');
                        $package_type    = $order->get_meta('package_type');

                        if (
                            $jobsearch_order_user == $user_id && 
                            $package_type == $packg_type && 
                            $jobsearch_order_attach_with == 'package' && 
                            $jobsearch_order_transaction_type == 'free'
                        ) {
                            $pkg_order_id = $order->get_id();
                            $order_exist = true;
                            break;
                        }
                    }

                    if(!empty($order_exist)){
                        if ($return_type == 'dash_error') {
                            $package_form_errs[] = esc_html__('You have already subscribed 1 free package. More than 1 free packages are not allowed.', 'wp-jobsearch');
                            return $package_form_errs;
                        } else {
                            echo json_encode(array('msg' => esc_html__('You have already subscribed 1 free package. More than 1 free packages are not allowed.', 'wp-jobsearch'), 'error' => '1'));
                            die;
                        }
                    }

                }
            }
        }

        public function add_candidate_resume_id_to_order($candidate_id, $order_id) {
            if ($candidate_id > 0 && $order_id > 0) {
                $order_cvs = get_post_meta($order_id, 'jobsearch_order_cvs_list', true);
                if ($order_cvs != '') {
                    $order_cvs = explode(',', $order_cvs);
                    if (!in_array($candidate_id, $order_cvs)) {
                        $order_cvs[] = $candidate_id;
                    }
                    $order_cvs = implode(',', $order_cvs);
                } else {
                    $order_cvs = $candidate_id;
                }
                update_post_meta($order_id, 'jobsearch_order_cvs_list', $order_cvs);
            }
        }

        public function add_candidate_invites_id_to_order($job_ids, $order_id) {
            if ($job_ids != '' && $order_id > 0) {
                $order_cvs = get_post_meta($order_id, 'jobsearch_order_invites_list', true);
                $order_cvs = $order_cvs != '' ? $order_cvs . ',' . $job_ids : $job_ids;
                update_post_meta($order_id, 'jobsearch_order_invites_list', $order_cvs);
            }
        }

        public function add_candidate_apply_job_id_to_order($candidate_id, $order_id) {
            if ($candidate_id > 0 && $order_id > 0) {
                $order_apps = get_post_meta($order_id, 'jobsearch_order_apps_list', true);
                if ($order_apps != '') {
                    $order_apps = explode(',', $order_apps);
                    $order_apps[] = $candidate_id;
                    $order_apps = implode(',', $order_apps);
                } else {
                    $order_apps = $candidate_id;
                }
                update_post_meta($order_id, 'jobsearch_order_apps_list', $order_apps);
            }
        }

    }

    return new jobsearch_packages_functions();
}
