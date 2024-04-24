<?php
/*
 * Candidate Default page template
 */

// execute short code
wp_enqueue_style('jobsearch-datetimepicker-style');
wp_enqueue_script('jobsearch-datetimepicker-script');
wp_enqueue_script('jquery-ui');

$output = do_shortcode('[jobsearch_candidate_shortcode
            candidate_cat = ""
            candidate_view = "view-default"
            candidate_excerpt = "20"
            candidate_order = "DESC"
            candidate_orderby = "date"
            candidate_sort_by = "yes"
            candidate_pagination = "yes"
            candidate_per_page = "10"            
            candidate_filters = "yes"
            candidate_type = "" ]');
if (wp_is_mobile()) {
    get_header('mobile');
} else {
    get_header();
}
global $jobsearch_plugin_options;
$plugin_default_view = isset($jobsearch_plugin_options['jobsearch-default-page-view']) ? $jobsearch_plugin_options['jobsearch-default-page-view'] : 'full';
$plugin_default_view_with_str = '';
if ($plugin_default_view == 'boxed') {

    $plugin_default_view_with_str = isset($jobsearch_plugin_options['jobsearch-boxed-view-width']) ? $jobsearch_plugin_options['jobsearch-boxed-view-width'] : '1140px';
    if ($plugin_default_view_with_str != '') {
        $plugin_default_view_with_str = ' style="width:' . $plugin_default_view_with_str . '"';
    }
}
wp_enqueue_script('jobsearch-candidate-functions-script');
?>
<div class="jobsearch-plugin-default-container" <?php echo force_balance_tags($plugin_default_view_with_str); ?>>
    <!--// Main Section \\-->
    <div class="jobsearch-plugin-section"> 
        <?php echo force_balance_tags($output); ?>
    </div>
    <!--// Main Section \\-->
</div>
<?php
get_footer();
