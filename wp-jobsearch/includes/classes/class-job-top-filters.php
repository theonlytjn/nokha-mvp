<?php
/*
  Class : JobFilterHTML
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class Jobsearch_JobsTopFilterHTML
{

    // hook things up
    public function __construct()
    {
        add_filter('jobsearch_job_top_filter_date_posted_box_html', array($this, 'jobsearch_job_filter_date_posted_box_html_callback'), 1, 3);
        add_filter('jobsearch_job_top_filter_jobtype_box_html', array($this, 'jobsearch_job_filter_jobtype_box_html_callback'), 1, 3);
        add_filter('jobsearch_job_top_filter_sector_box_html', array($this, 'jobsearch_job_filter_sector_box_html_callback'), 1, 3);
    }

    static function jobsearch_job_filter_date_posted_box_html_callback($html, $global_rand_id, $sh_atts)
    {
        $posted = isset($_REQUEST['posted']) ? $_REQUEST['posted'] : '';
        $posted = jobsearch_esc_html($posted);
        $rand = rand(234, 34234);
        $default_date_time_formate = 'd-m-Y H:i:s';
        $current_timestamp = current_time('timestamp');

        $posted_date_filter = isset($sh_atts['job_filters_date']) ? $sh_atts['job_filters_date'] : '';

        $date_filter_collapse = isset($sh_atts['job_filters_date_collapse']) ? $sh_atts['job_filters_date_collapse'] : '';
        ob_start();
        ?>
        <li>
            <div class="jobsearch-select-style">
                <select name="posted" class="selectize-select"<?php echo apply_filters('jobsearch_joblistin_top_filters_datefield_exatts', '', $global_rand_id, $sh_atts) ?> placeholder="<?php esc_html_e('Date Posted', 'wp-jobsearch'); ?>">
                    <option value=""><?php esc_html_e('Date Posted', 'wp-jobsearch'); ?></option>
                    <option value="lasthour" <?php echo($posted == 'lasthour' ? 'selected="selected"' : '') ?>><?php esc_html_e('Last Hour', 'wp-jobsearch') ?></option>
                    <option value="last24" <?php echo($posted == 'last24' ? 'selected="selected"' : '') ?>><?php esc_html_e('Last 24 hours', 'wp-jobsearch') ?></option>
                    <option value="7days" <?php echo($posted == '7days' ? 'selected="selected"' : '') ?>><?php esc_html_e('Last 7 days', 'wp-jobsearch') ?></option>
                    <option value="14days" <?php echo($posted == '14days' ? 'selected="selected"' : '') ?>><?php esc_html_e('Last 14 days', 'wp-jobsearch') ?></option>
                    <option value="30days" <?php echo($posted == '30days' ? 'selected="selected"' : '') ?>><?php esc_html_e('Last 30 days', 'wp-jobsearch') ?></option>
                    <option value="all" <?php echo($posted == 'all' ? 'selected="selected"' : '') ?>><?php esc_html_e('All', 'wp-jobsearch') ?></option>
                </select>
            </div>
        </li>
        <?php
        $html .= ob_get_clean();
        if ($posted_date_filter == 'no') {
            $html = '';
        }
        return $html;
    }

    static function jobsearch_job_filter_jobtype_box_html_callback($html, $global_rand_id, $sh_atts)
    {
        global $jobsearch_form_fields;
        $job_type_name = 'job_type';

        $job_type = isset($_REQUEST[$job_type_name]) ? $_REQUEST[$job_type_name] : '';
        $job_type = jobsearch_esc_html($job_type);

        $job_type_filter = isset($sh_atts['job_filters_type']) ? $sh_atts['job_filters_type'] : '';

        $type_filter_collapse = isset($sh_atts['job_filters_type_collapse']) ? $sh_atts['job_filters_type_collapse'] : '';
        ob_start();

        $typs_args = array(
            'taxonomy' => 'jobtype',
            'hide_empty' => false,
        );
        $typs_args = apply_filters('jobsearch_listing_jobtypes_filters_args', $typs_args);
        $all_job_type = get_terms($typs_args);
        if (!empty($all_job_type)) {
            ?>
            <li>
                <div class="<?php echo apply_filters('jobsearch_modify_top_srch_jobtype_parntclass', 'jobsearch-select-style') ?>">
                    <select name="<?php echo($job_type_name) ?>"<?php echo apply_filters('jobsearch_joblistin_top_filters_jtypefield_exatts', '', $global_rand_id, $sh_atts) ?> class="selectize-select"
                            placeholder="<?php esc_html_e('Job Type', 'wp-jobsearch'); ?>">
                        <option value=""><?php esc_html_e('Job Type', 'wp-jobsearch'); ?></option>
                        <?php
                        foreach ($all_job_type as $job_typeitem) {
                            ?>
                            <option value="<?php echo($job_typeitem->slug); ?>" <?php echo($job_type == $job_typeitem->slug ? 'selected="selected"' : '') ?>><?php echo($job_typeitem->name); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
            </li>
            <?php
        }
        $html .= ob_get_clean();
        if ($job_type_filter == 'no') {
            $html = '';
        }
        return $html;
    }

    static function jobsearch_job_filter_sector_box_html_callback($html, $global_rand_id, $sh_atts) {
        global $jobsearch_form_fields;
        $sector_name = 'sector_cat';
        $sector = isset($_REQUEST['sector_cat']) ? $_REQUEST['sector_cat'] : '';
        $sector = jobsearch_esc_html($sector);
        $job_sector_filter = isset($sh_atts['job_filters_sector']) ? $sh_atts['job_filters_sector'] : '';
        $sec_filter_collapse = isset($sh_atts['job_filters_sector_collapse']) ? $sh_atts['job_filters_sector_collapse'] : '';

        ob_start();
        $sector_parent_id = 0;
        $sector_show_count = 0;
        $selected_spec = '';
        if ($sector != '') {
            $selected_spec = get_term_by('slug', $sector, 'sector');
            if (isset($selected_spec->term_id)) {
                $sector_parent_id = $selected_spec->term_id;
            }
        }
        $sector_args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'number' => $sector_show_count,
            'fields' => 'all',
            'slug' => '',
            'hide_empty' => false,
            //'parent' => $sector_parent_id,
        );
        $all_sector = get_terms('sector', $sector_args);
        if (count($all_sector) <= 0) {
            $sector_args = array(
                'orderby' => 'name',
                'order' => 'ASC',
                'number' => $sector_show_count,
                'fields' => 'all',
                'hide_empty' => false,
                'slug' => '',
                //'parent' => isset($selected_spec->parent) ? $selected_spec->parent : 0,
            );
            $all_sector = get_terms('sector', $sector_args);
        }
        $all_sector = get_terms('sector', $sector_args);
        if (!empty($all_sector)) { ?>
            <li>
                <div class="<?php echo apply_filters('jobsearch_modify_top_srch_sector_parntclass', 'jobsearch-select-style') ?>">
                    <select name="<?php echo($sector_name) ?>"<?php echo apply_filters('jobsearch_joblistin_top_filters_secfield_exatts', '', $global_rand_id, $sh_atts) ?> class="selectize-select"
                            placeholder="<?php esc_html_e('Sector', 'wp-jobsearch'); ?>">
                        <option value=""><?php esc_html_e('Sector', 'wp-jobsearch'); ?></option>
                        <option value="all" <?php echo ($sector == 'all' ? 'selected="selected"' : '') ?>><?php esc_html_e('All', 'wp-jobsearch') ?></option>
                        <?php
                        foreach ($all_sector as $job_secitem) { ?>
                            <option value="<?php echo($job_secitem->slug); ?>" <?php echo($sector == $job_secitem->slug ? 'selected="selected"' : '') ?>><?php echo($job_secitem->name); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </li>
            <?php
        }
        $html .= ob_get_clean();
        if ($job_sector_filter == 'no') {
            $html = '';
        }
        return $html;
    }
}

// class $Jobsearch_JobsTopFilterHTML 
$Jobsearch_JobsTopFilterHTML = new Jobsearch_JobsTopFilterHTML();
global $Jobsearch_JobsTopFilterHTML;

