<?php
/*
  Class : Indeed jobs Front view
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class JobSearch_Indeed_Jobs_Front {

    // hook things up
    public function __construct() {

        //jobsearch_job_detail_content_info
        //add_filter('jobsearch_job_detail_content_info', array($this, 'job_detail_content_info'), 10, 2);

        //jobsearch_job_detail_content_fields
        //add_filter('jobsearch_job_detail_content_fields', array($this, 'job_detail_content_none'), 10, 2);

        //jobsearch_job_detail_content_detail
        //add_filter('jobsearch_job_detail_content_detail', array($this, 'job_detail_content_detail'), 10, 2);

        //jobsearch_job_detail_content_skills
        //add_filter('jobsearch_job_detail_content_skills', array($this, 'job_detail_content_none'), 10, 2);

        //jobsearch_job_detail_content_related
        //jobsearch_job_detail_sidebar_apply_btns
        //add_filter('jobsearch_job_detail_sidebar_apply_btns', array($this, 'job_detail_content_none'), 10, 2);
        
        //add_filter('jobsearch_job_defdet_applybtn_boxhtml', array($this, 'job_detail_apply_btn'), 10, 2);
        
        //add_filter('jobsearch_job_defdetail_after_detcont_html', array($this, 'job_detail_remove_html'), 100, 2);
        //add_filter('jobsearch_job_det_apply_mobile_btn_html', array($this, 'job_detail_remove_html'), 100, 2);

        //jobsearch_job_detail_sidebar_related_jobs
    }
    
    public function job_detail_remove_html($html, $job_id) {
        $job_referral = get_post_meta($job_id, 'jobsearch_job_referral', true);
        if ($job_referral == 'indeed' || $job_referral == 'ziprecruiter' || $job_referral == 'careerbuilder' || $job_referral == 'careerjet') {
            $html = '';
        }
        
        return $html;
    }
    
    public function job_detail_apply_btn($apply_bbox, $job_id) {
        global $jobsearch_plugin_options;
        $job_referral = get_post_meta($job_id, 'jobsearch_job_referral', true);
        if ($job_referral == 'indeed' || $job_referral == 'ziprecruiter' || $job_referral == 'careerbuilder' || $job_referral == 'careerjet') {
            
            $without_login_signin_restriction = isset($jobsearch_plugin_options['without-login-apply-restriction']) ? $jobsearch_plugin_options['without-login-apply-restriction'] : '';
            
            $free_job_apply = isset($jobsearch_plugin_options['free-job-apply-allow']) ? $jobsearch_plugin_options['free-job-apply-allow'] : '';
            
            $apply_without_login = isset($jobsearch_plugin_options['job-apply-without-login']) ? $jobsearch_plugin_options['job-apply-without-login'] : '';

            $external_signin_switch = false;
            if (isset($without_login_signin_restriction) && is_array($without_login_signin_restriction) && sizeof($without_login_signin_restriction) > 0) {
                foreach ($without_login_signin_restriction as $restrict_signin_switch) {
                    if ($restrict_signin_switch == 'external') {
                        $external_signin_switch = true;
                    }
                }
            }

            $url_target = '_blank';
            $login_class = '';
            $job_detail_url = get_post_meta($job_id, 'jobsearch_field_job_detail_url', true);
            if ($apply_without_login == 'off' && $external_signin_switch && !is_user_logged_in()) {
                $job_detail_url = 'javascript:void(0);';
                $url_target = '_self';
                $login_class = ' jobsearch-open-signin-tab';
            }
            
            if ($free_job_apply != 'on') {
                $user_app_pkg = jobsearch_candidate_first_subscribed_app_pkg();
                if (!$user_app_pkg) {
                    $user_app_pkg = jobsearch_candprof_first_pkg_subscribed();
                }
                if (!$user_app_pkg) {
                    $job_detail_url = 'javascript:void(0);';
                    $url_target = '_self';
                    $login_class = ' jobsearch-buy-apply-pkgalert';
                    
                    add_action('wp_footer', function() {
                        global $jobsearch_plugin_options;
                        $rand_id = rand(1000000, 99999999);
                        $candidate_pkgs_page = isset($jobsearch_plugin_options['candidate_package_page']) ? $jobsearch_plugin_options['candidate_package_page'] : '';
                        $candidate_pkgs_page_url = '';
                        if ($candidate_pkgs_page != '') {
                            $candidate_pkgs_page_obj = get_page_by_path($candidate_pkgs_page);
                            if (is_object($candidate_pkgs_page_obj) && isset($candidate_pkgs_page_obj->ID)) {
                                $candidate_pkgs_page_url = get_permalink($candidate_pkgs_page_obj->ID);
                            }
                        }
                        if ($candidate_pkgs_page_url != '') {
                            $response_msg = wp_kses(sprintf(__('You have no package. <a href="%s">Click here</a> to subscribe a package.', 'wp-jobsearch'), $candidate_pkgs_page_url), array('a' => array('href' => array())));
                        } else {
                            $response_msg = esc_html__('You have no package. Please subscribe to a package first.', 'wp-jobsearch');
                        }
                        ?>
                        <div class="jobsearch-modal jobsearch-typo-wrap fade" id="JobSearchUserApplyJobPkgAlertPopup<?php echo ($rand_id) ?>">
                            <div class="modal-inner-area">&nbsp;</div>
                            <div class="modal-content-area">
                                <div class="modal-box-area">
                                    <div class="jobsearch-modal-title-box">
                                        <span class="modal-close"><i class="fa fa-times"></i></span>
                                    </div>
                                    <p><?php echo ($response_msg) ?></p>
                                </div>
                            </div>
                            <script>
                                jQuery(document).on('click', '.jobsearch-buy-apply-pkgalert', function () {
                                    jobsearch_modal_popup_open('JobSearchUserApplyJobPkgAlertPopup<?php echo($rand_id) ?>');
                                });
                            </script>
                        </div>
                        <?php
                    });
                }
            }
            
            ob_start();
            ?>
            <a href="<?php echo ($job_detail_url) ?>" target="<?php echo ($url_target) ?>" class="jobsearch-applyjob-btn<?php echo ($login_class) ?>"><?php esc_html_e('Apply for the job', 'wp-jobsearch') ?></a>
            <?php
            $apply_bbox = ob_get_clean();
        }
        
        return $apply_bbox;
    }

    public function job_detail_content_info($content, $post_id) {

        $job_referral = get_post_meta($post_id, 'jobsearch_job_referral', true);
        if ($job_referral == 'indeed') {

            ob_start();

            $jobsearch_job_posted = get_post_meta($post_id, 'jobsearch_field_job_publish_date', true);
            $jobsearch_job_posted_ago = jobsearch_time_elapsed_string($jobsearch_job_posted);
            $jobsearch_job_posted_formated = date_i18n(get_option('date_format'), ($jobsearch_job_posted));


            $job_views_count = get_post_meta($post_id, 'jobsearch_job_views_count', true);

            $job_type_str = jobsearch_job_get_all_jobtypes($post_id, 'jobsearch-jobdetail-type', '', '', '<small>', '</small>');
            $job_company_name = get_post_meta($post_id, 'jobsearch_field_company_name', true);
            
            $get_job_location = get_post_meta($post_id, 'jobsearch_field_location_address', true);
            ?>
            <span>
                <?php
                if ($job_type_str != '') {
                    echo force_balance_tags($job_type_str);
                }
                if ($job_company_name != '') {
                    echo '<a>' . ($job_company_name) . '</a>';
                }
                ?>
                <small class="jobsearch-jobdetail-postinfo"><?php echo esc_html($jobsearch_job_posted_ago); ?></small>
            </span>
            <ul class="jobsearch-jobdetail-options">
                <?php
                if (!empty($get_job_location)) {
                    $google_mapurl = 'https://www.google.com/maps/search/' . $get_job_location;
                    ?>
                    <li><i class="jobsearch-icon jobsearch-maps-and-flags"></i> <?php echo esc_html($get_job_location); ?> <a href="<?php echo esc_url($google_mapurl); ?>" target="_blank" class="jobsearch-jobdetail-view"><?php echo esc_html__('View on Map', 'wp-jobsearch') ?></a></li>
                    <?php
                }
                ?> 
                <li><i class="jobsearch-icon jobsearch-calendar"></i> <?php echo esc_html__('Post Date', 'wp-jobsearch') ?>: <?php echo esc_html($jobsearch_job_posted_formated); ?></li>
                <li><a><i class="jobsearch-icon jobsearch-view"></i> <?php echo esc_html__('View(s)', 'wp-jobsearch') ?> <?php echo absint($job_views_count); ?></a></li>
            </ul>
            <?php
            // wrap in this due to enquire arrange button style.
            $before_label = esc_html__('Shortlist', 'wp-jobsearch');
            $after_label = esc_html__('Shortlisted', 'wp-jobsearch');
            $figcaption_div = true;
            $book_mark_args = array(
                'before_label' => $before_label,
                'after_label' => $after_label,
                'before_icon' => '<i class="fa fa-heart-o"></i>',
                'after_icon' => '<i class="fa fa-heart"></i>',
            );
            do_action('jobsearch_shortlist_frontend_button', $post_id, $book_mark_args, $figcaption_div);

            //
            $popup_args = array(
                'job_id' => $post_id,
            );
            do_action('jobsearch_job_send_to_email_filter', $popup_args);

            do_action('jobsearch_job_detail_socilinks_html', $post_id);

            $content = ob_get_clean();
        }
        return $content;
    }

    public function job_detail_content_detail($content, $post_id) {
        
        global $jobsearch_plugin_options;

        $job_referral = get_post_meta($post_id, 'jobsearch_job_referral', true);
        if ($job_referral == 'indeed') {

            $without_login_signin_restriction = isset($jobsearch_plugin_options['without-login-apply-restriction']) ? $jobsearch_plugin_options['without-login-apply-restriction'] : '';
            
            $free_job_apply = isset($jobsearch_plugin_options['free-job-apply-allow']) ? $jobsearch_plugin_options['free-job-apply-allow'] : '';

            $apply_without_login = isset($jobsearch_plugin_options['job-apply-without-login']) ? $jobsearch_plugin_options['job-apply-without-login'] : '';

            $external_signin_switch = false;
            if (isset($without_login_signin_restriction) && is_array($without_login_signin_restriction) && sizeof($without_login_signin_restriction) > 0) {
                foreach ($without_login_signin_restriction as $restrict_signin_switch) {
                    if ($restrict_signin_switch == 'external') {
                        $external_signin_switch = true;
                    }
                }
            }

            $login_class = '';
            $job_detail_url = get_post_meta($post_id, 'jobsearch_field_job_detail_url', true);
            if ($apply_without_login == 'off' && $external_signin_switch && !is_user_logged_in()) {
                $job_detail_url = 'javascript:void(0);';
                $login_class = ' jobsearch-open-signin-tab';
            }
            if ($free_job_apply != 'on') {
                $user_app_pkg = jobsearch_candidate_first_subscribed_app_pkg();
                if (!$user_app_pkg) {
                    $user_app_pkg = jobsearch_candprof_first_pkg_subscribed();
                }
                if (!$user_app_pkg) {
                    $job_detail_url = 'javascript:void(0);';
                    $url_target = '_self';
                    $login_class = ' jobsearch-buy-apply-pkgalert';
                    
                    add_action('wp_footer', function() {
                        global $jobsearch_plugin_options;
                        $rand_id = rand(1000000, 99999999);
                        $candidate_pkgs_page = isset($jobsearch_plugin_options['candidate_package_page']) ? $jobsearch_plugin_options['candidate_package_page'] : '';
                        $candidate_pkgs_page_url = '';
                        if ($candidate_pkgs_page != '') {
                            $candidate_pkgs_page_obj = get_page_by_path($candidate_pkgs_page);
                            if (is_object($candidate_pkgs_page_obj) && isset($candidate_pkgs_page_obj->ID)) {
                                $candidate_pkgs_page_url = get_permalink($candidate_pkgs_page_obj->ID);
                            }
                        }
                        if ($candidate_pkgs_page_url != '') {
                            $response_msg = wp_kses(sprintf(__('You have no package. <a href="%s">Click here</a> to subscribe a package.', 'wp-jobsearch'), $candidate_pkgs_page_url), array('a' => array('href' => array())));
                        } else {
                            $response_msg = esc_html__('You have no package. Please subscribe to a package first.', 'wp-jobsearch');
                        }
                        ?>
                        <div class="jobsearch-modal jobsearch-typo-wrap fade" id="JobSearchUserApplyJobPkgAlertPopup<?php echo ($rand_id) ?>">
                            <div class="modal-inner-area">&nbsp;</div>
                            <div class="modal-content-area">
                                <div class="modal-box-area">
                                    <div class="jobsearch-modal-title-box">
                                        <span class="modal-close"><i class="fa fa-times"></i></span>
                                    </div>
                                    <p><?php echo ($response_msg) ?></p>
                                </div>
                            </div>
                            <script>
                                jQuery(document).on('click', '.jobsearch-buy-apply-pkgalert', function () {
                                    jobsearch_modal_popup_open('JobSearchUserApplyJobPkgAlertPopup<?php echo($rand_id) ?>');
                                });
                            </script>
                        </div>
                        <?php
                    });
                }
            }
        
            $content .= '<div class="view-more-link"><a href="' . $job_detail_url . '" class="view-more-btn' . $login_class . '">' . esc_html__('view more', 'wp-jobsearch') . '</a></div>';
        }
        return $content;
    }

    public function job_detail_content_none($content, $post_id) {

        $job_referral = get_post_meta($post_id, 'jobsearch_job_referral', true);
        if ($job_referral == 'indeed') {
            $content = '';
        }
        return $content;
    }

}

// Class JobSearch_Indeed_Jobs_Front
$JobSearch_Indeed_Jobs_Front_obj = new JobSearch_Indeed_Jobs_Front();
global $JobSearch_Indeed_Jobs_Front_obj;
