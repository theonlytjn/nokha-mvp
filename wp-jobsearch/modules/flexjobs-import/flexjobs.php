<?php

/*
 * Class : Flexjobs Jobs Import
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class JobSearch_Flexjobs_Jobs {

    // hook things up
    public function __construct() {
        $this->load_files();
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));

    }

    public function admin_enqueue_scripts() {

        $flexjobs_jobs_switch = get_option('jobsearch_integration_flexjobs_jobs');

        if ($flexjobs_jobs_switch == 'on') {
            wp_enqueue_style('jobsearch-flexjobs', jobsearch_plugin_get_url('modules/flexjobs-import/css/flexjobs.css'));
        }

        //
        if ($flexjobs_jobs_switch == 'on') {
            wp_enqueue_script('jobsearch-flexjobs-scripts', jobsearch_plugin_get_url('modules/flexjobs-import/js/flexjobs.js'), array(), '', true);
            $jobsearch_plugin_arr = array(
                'plugin_url' => jobsearch_plugin_get_url(),
                'ajax_url' => admin_url('admin-ajax.php'),
                'error_msg' => esc_html__('There is some problem.', 'wp-jobsearch'),
                'submit_txt' => esc_html__('Submit', 'wp-jobsearch'),
            );

            wp_localize_script('jobsearch-flexjobs-scripts', 'jobsearch_flexjobs_vars', $jobsearch_plugin_arr);
        }
    }

    public function load_files() {
        include plugin_dir_path(dirname(__FILE__)) . 'flexjobs-import/include/simple-html-dom.php';
        include plugin_dir_path(dirname(__FILE__)) . 'flexjobs-import/include/flexjobs-scraping.php';
        include plugin_dir_path(dirname(__FILE__)) . 'flexjobs-import/include/import-schedule.php';
    }

}

// class JobSearch_Flexjobs_Jobs
$JobSearch_Flexjobs_Jobs_obj = new JobSearch_Flexjobs_Jobs();
