<?php

/*
 * Class : Kropjobs Jobs Import
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class JobSearch_Kropjobs_Jobs {

    // hook things up
    public function __construct() {
        $this->load_files();
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

    }

    public function admin_enqueue_scripts() {

        $kropjobs_jobs_switch = get_option('jobsearch_integration_kropjobs_jobs');

        if ($kropjobs_jobs_switch == 'on') {
            wp_enqueue_style('jobsearch-kropjobs', jobsearch_plugin_get_url('modules/kropjobs-import/css/kropjobs.css'));
        }

        //
        if ($kropjobs_jobs_switch == 'on') {
            wp_enqueue_script('jobsearch-kropjobs-scripts', jobsearch_plugin_get_url('modules/kropjobs-import/js/kropjobs.js'), array(), '', true);
            $jobsearch_plugin_arr = array(
                'plugin_url' => jobsearch_plugin_get_url(),
                'ajax_url' => admin_url('admin-ajax.php'),
                'error_msg' => esc_html__('There is some problem.', 'wp-jobsearch'),
                'submit_txt' => esc_html__('Submit', 'wp-jobsearch'),
            );

            wp_localize_script('jobsearch-kropjobs-scripts', 'jobsearch_kropjobs_vars', $jobsearch_plugin_arr);
        }
    }

    public function load_files() {
        include plugin_dir_path(dirname(__FILE__)) . 'kropjobs-import/include/simple-html-dom.php';
        include plugin_dir_path(dirname(__FILE__)) . 'kropjobs-import/include/kropjobs-scraping.php';
        include plugin_dir_path(dirname(__FILE__)) . 'kropjobs-import/include/import-schedule.php';
    }

}

// class JobSearch_Kropjobs_Jobs
$JobSearch_Kropjobs_Jobs_obj = new JobSearch_Kropjobs_Jobs();
