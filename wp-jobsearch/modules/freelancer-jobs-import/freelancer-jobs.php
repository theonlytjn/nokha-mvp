<?php

/*
  Class : Freelancer Jobs Import
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class JobSearch_Freelancer_Jobs {

    // hook things up
    public function __construct() {
        $this->load_files();
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

    }

    public function admin_enqueue_scripts() {

        $freelancer_jobs_switch = get_option('jobsearch_integration_freelancer_jobs');

        if ($freelancer_jobs_switch == 'on') {
            wp_enqueue_style('jobsearch-freelancer-jobs', jobsearch_plugin_get_url('modules/freelancer-jobs-import/css/freelancer-jobs.css'));
        }

        //
        if ($freelancer_jobs_switch == 'on') {
            wp_enqueue_script('jobsearch-freelancer-jobs-scripts', jobsearch_plugin_get_url('modules/freelancer-jobs-import/js/freelancer-jobs.js'), array(), '', true);
            $jobsearch_plugin_arr = array(
                'plugin_url' => jobsearch_plugin_get_url(),
                'ajax_url' => admin_url('admin-ajax.php'),
                'error_msg' => esc_html__('There is some problem.', 'wp-jobsearch'),
                'submit_txt' => esc_html__('Submit', 'wp-jobsearch'),
            );

            wp_localize_script('jobsearch-freelancer-jobs-scripts', 'jobsearch_freelancerjobs_vars', $jobsearch_plugin_arr);
        }
    }

    public function load_files() {
        include plugin_dir_path(dirname(__FILE__)) . 'freelancer-jobs-import/include/simple-html-dom.php';
        include plugin_dir_path(dirname(__FILE__)) . 'freelancer-jobs-import/include/freelancer-jobs-scraping.php';
        include plugin_dir_path(dirname(__FILE__)) . 'freelancer-jobs-import/include/import-schedule.php';
    }

}

// class JobSearch_Freelancer_Jobs
$JobSearch_Freelancer_Jobs_obj = new JobSearch_Freelancer_Jobs();
