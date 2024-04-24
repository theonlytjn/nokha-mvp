<?php

/*
 * Class : Aquentjobs Jobs Import
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class JobSearch_Aquentjobs_Jobs {

    // hook things up
    public function __construct() {
        $this->load_files();
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

    }

    public function admin_enqueue_scripts() {

        $aquentjobs_jobs_switch = get_option('jobsearch_integration_aquentjobs_jobs');

        if ($aquentjobs_jobs_switch == 'on') {
            wp_enqueue_style('jobsearch-aquentjobs', jobsearch_plugin_get_url('modules/aquentjobs-import/css/aquentjobs.css'));
        }

        //
        if ($aquentjobs_jobs_switch == 'on') {
            wp_enqueue_script('jobsearch-aquentjobs-scripts', jobsearch_plugin_get_url('modules/aquentjobs-import/js/aquentjobs.js'), array(), '', true);
            $jobsearch_plugin_arr = array(
                'plugin_url' => jobsearch_plugin_get_url(),
                'ajax_url' => admin_url('admin-ajax.php'),
                'error_msg' => esc_html__('There is some problem.', 'wp-jobsearch'),
                'submit_txt' => esc_html__('Submit', 'wp-jobsearch'),
            );

            wp_localize_script('jobsearch-aquentjobs-scripts', 'jobsearch_aquentjobs_vars', $jobsearch_plugin_arr);
        }
    }

    public function load_files() {
        include plugin_dir_path(dirname(__FILE__)) . 'aquentjobs-import/include/simple-html-dom.php';
        include plugin_dir_path(dirname(__FILE__)) . 'aquentjobs-import/include/aquentjobs-scraping.php';
        include plugin_dir_path(dirname(__FILE__)) . 'aquentjobs-import/include/import-schedule.php';
    }

}

// class JobSearch_Aquentjobs_Jobs
$JobSearch_Aquentjobs_Jobs_obj = new JobSearch_Aquentjobs_Jobs();
