<?php
/*
  Class : CandidateFilterHTML
 */


// this is an include only WP file
if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class Jobsearch_CandidateFilterHTML {

    // hook things up
    public function __construct() {
        add_filter('jobsearch_candidate_filter_date_posted_box_html', array($this, 'jobsearch_candidate_filter_date_posted_box_html_callback'), 1, 5);
        add_filter('jobsearch_candidate_filter_sector_box_html', array($this, 'jobsearch_candidate_filter_sector_box_html_callback'), 1, 5);
        add_filter('jobsearch_candidate_filter_acf_fields_box_html', array($this, 'jobsearch_candidate_filter_acf_fields_box_html_callback'), 1, 5);
        
        add_filter('jobsearch_candidate_filter_location_box_html', array($this, 'cand_filter_joblocation_box_html_callback'), 1, 5);
        
        add_filter('wp_ajax_jobsearch_load_more_filter_clocs_to_list', array($this, 'load_more_locations'));
        add_filter('wp_ajax_nopriv_jobsearch_load_more_filter_clocs_to_list', array($this, 'load_more_locations'));
    }

    static function jobsearch_candidate_filter_date_posted_box_html_callback($html, $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts) {
        $posted = isset($_REQUEST['posted']) ? $_REQUEST['posted'] : '';
        $posted = jobsearch_esc_html($posted);
        $rand = rand(234, 34234);
        $default_date_time_formate = 'd-m-Y H:i:s';
        $current_timestamp = current_time('timestamp');

        $candidate_date_filter = isset($sh_atts['candidate_filters_date']) ? $sh_atts['candidate_filters_date'] : '';

        $date_filter_collapse = isset($sh_atts['candidate_filters_date_collapse']) ? $sh_atts['candidate_filters_date_collapse'] : '';

        $filter_sort_by = isset($sh_atts['candidate_datefilters_sortby']) ? $sh_atts['candidate_datefilters_sortby'] : '';

        $filter_collapse_cval = 'open';
        if ($date_filter_collapse == 'yes') {
            $filter_collapse_cval = 'close';
        }

        $filter_collapse_cname = 'candidate_date_filter_collapse';
        if (isset($_COOKIE[$filter_collapse_cname]) && $_COOKIE[$filter_collapse_cname] != '') {
            $filter_collapse_cval = $_COOKIE[$filter_collapse_cname];
            if ($_COOKIE[$filter_collapse_cname] == 'open') {
                $date_filter_collapse = 'no';
            } else {
                $date_filter_collapse = 'yes';
            }
        }
        ob_start();
        ?>
        <div class="jobsearch-filter-responsive-wrap">
            <div class="jobsearch-search-filter-wrap jobsearch-search-filter-toggle <?php echo($date_filter_collapse == 'yes' ? 'jobsearch-remove-padding' : '') ?>">
                <div class="jobsearch-fltbox-title"><a href="javascript:void(0);"
                                                       data-cname="<?php echo($filter_collapse_cname) ?>"
                                                       data-cval="<?php echo($filter_collapse_cval) ?>"
                                                       class="jobsearch-click-btn"><?php echo esc_html__('Date Posted', 'wp-jobsearch'); ?></a>
                </div>
                <div class="jobsearch-checkbox-toggle"
                     style="display: <?php echo($date_filter_collapse == 'yes' ? 'none' : 'block') ?>;">
                    <ul class="jobsearch-checkbox">
                        <?php
                        ob_start();
                        ?>
                        <li<?php echo($left_filter_count_switch != 'yes' ? ' class="no-filter-counts"' : '') ?>>
                            <?php
                            // main query array $args_count 
                            $lastdate = date($default_date_time_formate, strtotime('-1 hours', $current_timestamp));
                            $last_hour_count_arr = array(
                                array(
                                    'key' => 'post_date',
                                    'value' => strtotime($lastdate),
                                    'compare' => '>=',
                                    'type' => 'numeric',
                                )
                            );
                            ?>
                            <input id="lasthour<?php echo absint($rand); ?>" type="radio"
                                   name="posted" <?php if ($posted == 'lasthour') echo 'checked="checked"'; ?>
                                   onchange="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>);"
                                   value="lasthour"/>
                            <label for="lasthour<?php echo absint($rand); ?>"><span></span><?php esc_html_e('Last Hour', 'wp-jobsearch') ?>
                            </label>
                            <?php
                            $last_hour_totnum = jobsearch_get_candidate_item_count($left_filter_count_switch, $args_count, $last_hour_count_arr, $global_rand_id, 'posted');
                            if ($left_filter_count_switch == 'yes') {
                                ?>
                                <span class="filter-post-count"><?php echo absint($last_hour_totnum); ?></span>
                            <?php } ?>
                        </li>
                        <?php
                        $last_hour_html = ob_get_clean();
                        ob_start();
                        ?>
                        <li<?php echo($left_filter_count_switch != 'yes' ? ' class="no-filter-counts"' : '') ?>>
                            <?php
                            // main query array $args_count 
                            $lastdate = date($default_date_time_formate, strtotime('-24 hours', $current_timestamp));
                            $last24_count_arr = array(
                                array(
                                    'key' => 'post_date',
                                    'value' => strtotime($lastdate),
                                    'compare' => '>=',
                                    'type' => 'numeric',
                                )
                            );
                            ?>
                            <input id="last24<?php echo absint($rand); ?>" type="radio"
                                   name="posted" <?php if ($posted == 'last24') echo 'checked="checked"'; ?>
                                   onchange="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>);"
                                   value="last24"/>
                            <label for="last24<?php echo absint($rand); ?>"><span></span><?php esc_html_e('Last 24 hours', 'wp-jobsearch') ?>
                            </label>
                            <?php
                            $last24_totnum = jobsearch_get_candidate_item_count($left_filter_count_switch, $args_count, $last24_count_arr, $global_rand_id, 'posted');
                            if ($left_filter_count_switch == 'yes') {

                                ?>
                                <span class="filter-post-count"><?php echo absint($last24_totnum); ?></span>
                            <?php } ?>
                        </li>
                        <?php
                        $last_24_html = ob_get_clean();
                        ob_start();
                        ?>
                        <li<?php echo($left_filter_count_switch != 'yes' ? ' class="no-filter-counts"' : '') ?>>
                            <?php
                            // main query array $args_count 
                            $lastdate = date($default_date_time_formate, strtotime('-7 days', $current_timestamp));
                            $days7_count_arr = array(
                                array(
                                    'key' => 'post_date',
                                    'value' => strtotime($lastdate),
                                    'compare' => '>=',
                                    'type' => 'numeric',
                                )
                            );
                            ?>
                            <input id="7days<?php echo absint($rand); ?>" type="radio"
                                   name="posted" <?php if ($posted == '7days') echo 'checked="checked"'; ?>
                                   onchange="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>);"
                                   value="7days"/>
                            <label for="7days<?php echo absint($rand); ?>"><span></span><?php esc_html_e('Last week', 'wp-jobsearch') ?>
                            </label>
                            <?php
                            $days7_totnum = jobsearch_get_candidate_item_count($left_filter_count_switch, $args_count, $days7_count_arr, $global_rand_id, 'posted');
                            if ($left_filter_count_switch == 'yes') {
                                ?>
                                <span class="filter-post-count"><?php echo absint($days7_totnum); ?></span>
                            <?php } ?>
                        </li>
                        <?php
                        $last_7days_html = ob_get_clean();
                        ob_start();
                        ?>
                        <li<?php echo($left_filter_count_switch != 'yes' ? ' class="no-filter-counts"' : '') ?>>
                            <?php
                            // main query array $args_count 
                            $lastdate = date($default_date_time_formate, strtotime('-14 days', $current_timestamp));
                            $days14_count_arr = array(
                                array(
                                    'key' => 'post_date',
                                    'value' => strtotime($lastdate),
                                    'compare' => '>=',
                                    'type' => 'numeric',
                                )
                            );
                            ?>
                            <input id="14days<?php echo absint($rand); ?>" type="radio"
                                   name="posted" <?php if ($posted == '14days') echo 'checked="checked"'; ?>
                                   onchange="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>);"
                                   value="14days"/>
                            <label for="14days<?php echo absint($rand); ?>"><span></span><?php esc_html_e('Last 2 weeks', 'wp-jobsearch') ?>
                            </label>
                            <?php
                            $days14_totnum = jobsearch_get_candidate_item_count($left_filter_count_switch, $args_count, $days14_count_arr, $global_rand_id, 'posted');
                            if ($left_filter_count_switch == 'yes') {
                                ?>
                                <span class="filter-post-count"><?php echo absint($days14_totnum); ?></span>
                            <?php } ?>
                        </li>
                        <?php
                        $last_14days_html = ob_get_clean();
                        ob_start();
                        ?>
                        <li<?php echo($left_filter_count_switch != 'yes' ? ' class="no-filter-counts"' : '') ?>>
                            <?php
                            // main query array $args_count 
                            $lastdate = date($default_date_time_formate, strtotime('-30 days', $current_timestamp));
                            $days30_count_arr = array(
                                array(
                                    'key' => 'post_date',
                                    'value' => strtotime($lastdate),
                                    'compare' => '>=',
                                    'type' => 'numeric',
                                )
                            );
                            ?>
                            <input id="30days<?php echo absint($rand); ?>" type="radio"
                                   name="posted" <?php if ($posted == '30days') echo 'checked="checked"'; ?>
                                   onchange="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>);"
                                   value="30days"/>
                            <label for="30days<?php echo absint($rand); ?>"><span></span><?php esc_html_e('Last month', 'wp-jobsearch') ?>
                            </label>
                            <?php
                            $days30_totnum = jobsearch_get_candidate_item_count($left_filter_count_switch, $args_count, $days30_count_arr, $global_rand_id, 'posted');
                            if ($left_filter_count_switch == 'yes') {
                                ?>
                                <span class="filter-post-count"><?php echo absint($days30_totnum); ?></span>
                            <?php } ?>
                        </li>
                        <?php
                        $last_month_html = ob_get_clean();
                        ob_start();
                        ?>
                        <li<?php echo($left_filter_count_switch != 'yes' ? ' class="no-filter-counts"' : '') ?>>
                            <?php
                            // main query array $args_count 
                            $all_days_count_arr = array();
                            ?>
                            <input id="all<?php echo absint($rand); ?>" type="radio"
                                   name="posted" <?php if ($posted == 'all' || $posted == '') echo 'checked="checked"'; ?>
                                   onchange="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>);"
                                   value="all"/>
                            <label for="all<?php echo absint($rand); ?>"><span></span><?php esc_html_e('All', 'wp-jobsearch') ?>
                            </label>
                            <?php
                            $all_days_totnum = jobsearch_get_candidate_item_count($left_filter_count_switch, $args_count, $all_days_count_arr, $global_rand_id, 'posted');
                            if ($left_filter_count_switch == 'yes') {
                                ?>
                                <span class="filter-post-count"><?php echo absint($all_days_totnum); ?></span>
                            <?php } ?>
                        </li>
                        <?php
                        $from_all_html = ob_get_clean();

                        $filter_html_arr = array(
                            array(
                                'count' => $last_hour_totnum,
                                'html' => $last_hour_html
                            ),
                            array(
                                'count' => $last24_totnum,
                                'html' => $last_24_html
                            ),
                            array(
                                'count' => $days7_totnum,
                                'html' => $last_7days_html
                            ),
                            array(
                                'count' => $days14_totnum,
                                'html' => $last_14days_html
                            ),
                            array(
                                'count' => $days30_totnum,
                                'html' => $last_month_html
                            ),
                            array(
                                'count' => $all_days_totnum,
                                'html' => $from_all_html
                            ),
                        );
                        if ($filter_sort_by == 'desc') {
                            krsort($filter_html_arr);
                        } else if ($filter_sort_by == 'count') {
                            usort($filter_html_arr, function ($a, $b) {
                                if ($a['count'] == $b['count']) {
                                    $ret_val = 0;
                                }
                                $ret_val = ($b['count'] < $a['count']) ? -1 : 1;
                                return $ret_val;
                            });
                        }
                        foreach ($filter_html_arr as $filtr_item_html) {
                            echo ($filtr_item_html['html']);
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
        $html .= ob_get_clean();
        if ($candidate_date_filter == 'no') {
            $html = '';
        }
        return $html;
    }

    static function jobsearch_candidate_filter_acf_fields_box_html_callback($html, $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts) {
        global $jobsearch_form_fields;
        $sector_name = 'sector_cat';
        $sector = isset($_REQUEST[$sector_name]) ? $_REQUEST[$sector_name] : '';

        $sector = jobsearch_esc_html($sector);

        $candidate_sector_filter = isset($sh_atts['candidate_filters_sector']) ? $sh_atts['candidate_filters_sector'] : '';
        $sec_filter_collapse = isset($sh_atts['candidate_filters_sector_collapse']) ? $sh_atts['candidate_filters_sector_collapse'] : '';

        $filter_sort_by = isset($sh_atts['candidate_sectorfilters_sortby']) ? $sh_atts['candidate_sectorfilters_sortby'] : '';
        $field_key = isset($sh_atts['field_key']) ? $sh_atts['field_key'] : '';

        $filter_collapse_cval = 'open';
        if ($sec_filter_collapse == 'yes') {
            $filter_collapse_cval = 'close';
        }

        $filter_collapse_cname = 'candidate_sec_filter_collapse';
        if (isset($_COOKIE[$filter_collapse_cname]) && $_COOKIE[$filter_collapse_cname] != '') {
            $filter_collapse_cval = $_COOKIE[$filter_collapse_cname];
            if ($_COOKIE[$filter_collapse_cname] == 'open') {
                $sec_filter_collapse = 'no';
            } else {
                $sec_filter_collapse = 'yes';
            }
        }
        ob_start();
        if(!empty($field_key)){
            $field = get_field_object($field_key);
            $field_label = !empty($field['label']) ? $field['label'] : '';
            $field_name = !empty($field['name']) ? $field['name'] : '';
            $field_type = !empty($field['type']) ? $field['type'] : '';
            $choices = !empty($field['choices']) ? $field['choices'] : array();

            $job_type_selected  = '';
            $field_value	= !empty($_REQUEST[$field_name]) ? $_REQUEST[$field_name] : '';
            ?>
            <div class="jobsearch-filter-responsive-wrap">
                <div class="jobsearch-search-filter-wrap jobsearch-search-filter-toggle <?php echo($sec_filter_collapse == 'yes' ? 'jobsearch-remove-padding' : '') ?>">
                    <div class="jobsearch-fltbox-title"><a href="javascript:void(0);"
                                                        data-cname="<?php echo($filter_collapse_cname) ?>"
                                                        data-cval="<?php echo($filter_collapse_cval) ?>"
                                                        class="jobsearch-click-btn"><?php echo esc_html($field_label) ?></a>
                    </div>
                    <div class="jobsearch-checkbox-toggle" style="display: <?php echo ($sec_filter_collapse == 'yes' ? 'none' : 'block') ?>;">
                        <ul class="jobsearch-checkbox">
                            
                                <?php
                                 
                                switch ($field_type) {                              
                                    case "radio":

                                       
                                        foreach($choices as $key=>$choice){
                                            $days7_count_arr = array(
                                                array(
                                                    'key' => $field_name,
                                                    'value' => $key,
                                                    'compare' => 'LIKE',
                                                )
                                            );
                                            $days7_totnum = jobsearch_get_item_count($left_filter_count_switch, $args_count, $days7_count_arr, $global_rand_id, $field_name);
                                            ?>
                                            <li class="no-filter-counts checkbox">
                                                <?php
                                                $job_type_selected = '';
                                                if (!empty($field_value) && (!is_array($field_value) && ($field_value == $key) || (is_array($field_value) && in_array($key, $field_value)))) {
                                                    $job_type_selected = ' checked="checked"';
                                                }
                                                $jobsearch_form_fields->radio_field(
                                                    array(
                                                        'simple' => true,
                                                        'id' => $field_name.'_'.$key,
                                                        'std' => $key,
                                                        'cus_name' => $field_name,
                                                        'ext_attr' => 'onchange="jobsearch_candidate_content_load(\'' . absint($global_rand_id) . ' \')"' . $job_type_selected,
                                                    )
                                                );
                                                ?>
                                                <label for="<?php echo esc_attr($field_name.'_'.$key) ?>">
                                                    <span></span>
                                                    <?php echo force_balance_tags($choice); ?>
                                                </label>
                                                <?php if ($left_filter_count_switch == 'yes') { ?>
                                                    <span class="filter-post-count"><?php echo absint($days7_totnum); ?></span>
                                                <?php } ?>
                                            </li>
                                            <?php                                       
                                        }                                    
                                        break;
                                    case "select":
                                        foreach($choices as $key=>$choice){
                                            $days7_count_arr = array(
                                                array(
                                                    'key' => $field_name,
                                                    'value' => $key,
                                                    'compare' => 'LIKE',
                                                )
                                            );
                                            $days7_totnum = jobsearch_get_item_count($left_filter_count_switch, $args_count, $days7_count_arr, $global_rand_id, $field_name);
                                            ?>
                                            <li class="no-filter-counts checkbox">
                                                <?php
                                                $job_type_selected = '';
                                                if (!empty($field_value) && (!is_array($field_value) && ($field_value == $key) || (is_array($field_value) && in_array($key, $field_value)))) {
                                                    $job_type_selected = ' checked="checked"';
                                                }
                                                $jobsearch_form_fields->radio_field(
                                                    array(
                                                        'simple' => true,
                                                        'id' => $field_name.'_'.$key,
                                                        'std' => $key,
                                                        'cus_name' => $field_name,
                                                        'ext_attr' => 'onchange="jobsearch_candidate_content_load(\'' . absint($global_rand_id) . ' \')"' . $job_type_selected,
                                                    )
                                                );
                                                ?>
                                                <label for="<?php echo esc_attr($field_name.'_'.$key) ?>">
                                                    <span></span>
                                                    <?php echo force_balance_tags($choice); ?>
                                                </label>
                                                <?php if ($left_filter_count_switch == 'yes') { ?>
                                                    <span class="filter-post-count"><?php echo absint($days7_totnum); ?></span>
                                                <?php } ?>
                                            </li>
                                            <?php                                       
                                        }                                    
                                        break;
                                    case "email":
                                        ?>
                                        <li class="no-filter-counts checkbox">
                                            <?php
                                            $jobsearch_form_fields->input_field(
                                                array(
                                                    'simple' => true,
                                                    'id' => $field_name,
                                                    'cus_name' =>$field_name,
                                                    'std' => $field_value,
                                                    'ext_attr' => 'onchange="jobsearch_candidate_content_load(\'' . absint($global_rand_id) . ' \')"' . $job_type_selected,
                                                )
                                            );  
                                            ?>
                                        </li>
                                        <?php                                     
                                        break;
                                    case "number":
                                        ?>
                                        <li class="no-filter-counts checkbox">
                                            <?php
                                            $jobsearch_form_fields->input_field(
                                                array(
                                                    'simple' => true,
                                                    'id' => $field_name,
                                                    'cus_name' =>$field_name,
                                                    'std' => $field_value,
                                                    'ext_attr' => 'onchange="jobsearch_candidate_content_load(\'' . absint($global_rand_id) . ' \')"' . $job_type_selected,
                                                )
                                            );  
                                            ?>
                                        </li>
                                        <?php                                     
                                        break;
                                    case "text":
                                        ?>
                                        <li class="no-filter-counts checkbox">
                                            <?php
                                            $jobsearch_form_fields->input_field(
                                                array(
                                                    'simple' => true,
                                                    'id' => $field_name,
                                                    'cus_name' =>$field_name,
                                                    'std' => $field_value,
                                                    'ext_attr' => 'onchange="jobsearch_candidate_content_load(\'' . absint($global_rand_id) . ' \')"' . $job_type_selected,
                                                )
                                            );  
                                            ?>
                                        </li>
                                        <?php                                     
                                        break;
                                    default:
                                        foreach($choices as $key=>$choice){
                                            $days7_count_arr = array(
                                                array(
                                                    'key' => $field_name,
                                                    'value' => $key,
                                                    'compare' => 'LIKE',
                                                )
                                            );
                                            $days7_totnum = jobsearch_get_item_count($left_filter_count_switch, $args_count, $days7_count_arr, $global_rand_id, $field_name);
                                            ?>
                                            <li class="no-filter-counts checkbox">
                                                <?php
                                                $job_type_selected = '';
                                                if (!empty($field_value) && (!is_array($field_value) && ($field_value == $key) || (is_array($field_value) && in_array($key, $field_value)))) {
                                                    $job_type_selected = ' checked="checked"';
                                                }
                                                $jobsearch_form_fields->checkbox_field(
                                                    array(
                                                        'simple' => true,
                                                        'id' => sanitize_title($field_name.'_'.$key),
                                                        'cus_name' => $field_name.'[]',
                                                        'std' => $key,
                                                        'ext_attr' => 'onchange="jobsearch_candidate_content_load(\'' . absint($global_rand_id) . ' \')"' . $job_type_selected,
                                                    )
                                                );
                                                ?>
                                                <label for="<?php echo esc_attr(sanitize_title($field_name.'_'.$key)) ?>">
                                                    <span></span>
                                                    <?php echo force_balance_tags($choice); ?>
                                                </label>
                                                <?php if ($left_filter_count_switch == 'yes') { ?>
                                                    <span class="filter-post-count"><?php echo absint($days7_totnum); ?></span>
                                                <?php } ?>
                                            </li>
                                            <?php                                       
                                        }
                                }
                                ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
        }  
        $html .= ob_get_clean();
        return $html;
    }

    static function jobsearch_candidate_filter_sector_box_html_callback($html, $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts) {
        global $jobsearch_form_fields;
        $sector_name = 'sector_cat';
        $sector = isset($_REQUEST[$sector_name]) ? $_REQUEST[$sector_name] : '';

        $sector = jobsearch_esc_html($sector);

        $candidate_sector_filter = isset($sh_atts['candidate_filters_sector']) ? $sh_atts['candidate_filters_sector'] : '';
        $sec_filter_collapse = isset($sh_atts['candidate_filters_sector_collapse']) ? $sh_atts['candidate_filters_sector_collapse'] : '';

        $filter_sort_by = isset($sh_atts['candidate_sectorfilters_sortby']) ? $sh_atts['candidate_sectorfilters_sortby'] : '';

        $filter_collapse_cval = 'open';
        if ($sec_filter_collapse == 'yes') {
            $filter_collapse_cval = 'close';
        }

        $filter_collapse_cname = 'candidate_sec_filter_collapse';
        if (isset($_COOKIE[$filter_collapse_cname]) && $_COOKIE[$filter_collapse_cname] != '') {
            $filter_collapse_cval = $_COOKIE[$filter_collapse_cname];
            if ($_COOKIE[$filter_collapse_cname] == 'open') {
                $sec_filter_collapse = 'no';
            } else {
                $sec_filter_collapse = 'yes';
            }
        }
        ob_start();
        ?>
        <div class="jobsearch-filter-responsive-wrap">
            <div class="jobsearch-search-filter-wrap jobsearch-search-filter-toggle <?php echo($sec_filter_collapse == 'yes' ? 'jobsearch-remove-padding' : '') ?>">
                <div class="jobsearch-fltbox-title"><a href="javascript:void(0);"
                                                       data-cname="<?php echo($filter_collapse_cname) ?>"
                                                       data-cval="<?php echo($filter_collapse_cval) ?>"
                                                       class="jobsearch-click-btn"><?php echo esc_html__('Sector', 'wp-jobsearch') ?></a>
                </div>
                <div class="jobsearch-checkbox-toggle"
                     style="display: <?php echo($sec_filter_collapse == 'yes' ? 'none' : 'block') ?>;">

                    <?php
                    // get all candidate types

                    $sector_parent_id = 0;
                    $sector_show_count = 0;
                    $input_type_sector = 'radio';   // if first level then select only sigle sector

                    $sector_args = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'number' => $sector_show_count,
                        'fields' => 'all',
                        'slug' => '',
                        'hide_empty' => false,
                            //'parent' => $sector_parent_id,
                    );

                    $sector_all_args = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
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
                        $sector_all_args = array(
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'fields' => 'all',
                            'hide_empty' => false,
                            'slug' => '',
                                //'parent' => isset($selected_spec->parent) ? $selected_spec->parent : 0,
                        );
                        $all_sector = get_terms('sector', $sector_args);

                        if (isset($selected_spec->parent) && $selected_spec->parent != 0) {
                            $input_type_sector = 'checkbox';
                        }
                    } else {
                        if ($sector_parent_id != 0) {    // if parent is not root means not main parent
                            $input_type_sector = 'checkbox';   // if first level then select multiple sector
                        }
                    }

                    if (!empty($all_sector)) {

                        ob_start();
                        $number_option = 1;
                        $show_sector = 'yes';

                        if ($input_type_sector == 'radio' && $sector != '') {

                            if (is_array($sector) && is_array_empty($sector)) {
                                $show_sector = 'yes';
                            } else {
                                $show_sector = 'yes';
                            }
                        } else {
                            $show_sector = 'yes';
                        }

                        if ($show_sector == 'yes') {

                            if ($input_type_sector == 'checkbox') {
                                
                            }
                            $number_option_flag = 1;
                            echo '<ul class="jobsearch-checkbox">';
                            $sector_count_post = jobsearch_get_taxanomy_type_item_count($left_filter_count_switch, 'all', 'sector', $args_count, 'candidate');
                            ?>
                            <li class="jobsearch-<?php echo ($input_type_sector); ?>">
                                <?php
                                $sector_selected = '';
                                if ($sector == 'all') {
                                    $sector_selected = ' checked="checked"';
                                }
                                $jobsearch_form_fields->radio_field(
                                    array(
                                        'simple' => true,
                                        'id' => 'sector_all',
                                        'cus_name' => 'sector_cat',
                                        'std' => 'all',
                                        'classes' => $sector_name,
                                        'ext_attr' => ' onchange="jobsearch_candidate_content_load(' . absint($global_rand_id) . ');"' . $sector_selected
                                    )
                                );
                                ?>
                                <label for="sector_all">
                                    <span></span><?php esc_html_e('All', 'wp-jobsearch') ?>
                                </label>
                                <?php if ($left_filter_count_switch == 'yes') { ?>
                                    <span class="filter-post-count"><?php echo $sector_count_post; ?></span>
                                <?php } ?>
                            </li>
                            <?php
                            $filter_html_arr = array();
                            foreach ($all_sector as $sectoritem) {

                                $candidate_id_para = '';
                                ob_start();
                                if ($input_type_sector == 'checkbox') {
                                    ?>
                                    <li class="jobsearch-<?php echo $input_type_sector; ?><?php echo($number_option_flag > 6 ? ' filter-more-fields' : '') ?><?php echo($left_filter_count_switch != 'yes' ? ' no-filter-counts' : '') ?>">
                                        <?php
                                        $sector_selected = '';
                                        if ($sector == $sectoritem->slug) {
                                            $sector_selected = ' checked="checked"';
                                        }
                                        $jobsearch_form_fields->radio_field(
                                                array(
                                                    'simple' => true,
                                                    'id' => 'sector_' . $number_option,
                                                    'cus_name' => $sector_name,
                                                    'std' => $sectoritem->slug,
                                                    'classes' => $sector_name,
                                                    'ext_attr' => ' onchange="jobsearch_candidate_content_load(' . absint($global_rand_id) . ');"' . $sector_selected
                                                )
                                        );
                                        ?>
                                        <label for="sector_<?php echo $number_option; ?>">
                                            <span></span><?php echo $sectoritem->name; ?>
                                        </label>
                                        <?php
                                        $sector_count_post = jobsearch_get_taxanomy_type_item_count($left_filter_count_switch, $sectoritem->slug, 'sector', $args_count, 'candidate');
                                        if ($left_filter_count_switch == 'yes') {
                                            ?>
                                            <span class="filter-post-count"><?php echo $sector_count_post; ?></span>
                                        <?php } ?>

                                    </li>
                                    <?php
                                } else {
                                    if ($input_type_sector == 'radio') {
                                        $sector_selected = '';
                                        if ($sector == $sectoritem->slug) {
                                            $sector_selected = ' checked="checked"';
                                        }
                                        ?>
                                        <li class="jobsearch-<?php echo $input_type_sector; ?><?php echo($number_option_flag > 6 ? ' filter-more-fields' : '') ?><?php echo($left_filter_count_switch != 'yes' ? ' no-filter-counts' : '') ?>">
                                            <?php
                                            $jobsearch_form_fields->radio_field(
                                                    array(
                                                        'simple' => true,
                                                        'id' => 'sector_' . $number_option,
                                                        'cus_name' => $sector_name,
                                                        'std' => $sectoritem->slug,
                                                        'classes' => $sector_name,
                                                        'ext_attr' => ' onchange="jobsearch_candidate_content_load(' . absint($global_rand_id) . ');"' . $sector_selected
                                                    )
                                            );
                                            ?>
                                            <label for="sector_<?php echo $number_option; ?>">
                                                <span></span><?php echo $sectoritem->name; ?>
                                            </label>
                                            <?php
                                            $sector_count_post = jobsearch_get_taxanomy_type_item_count($left_filter_count_switch, $sectoritem->slug, 'sector', $args_count, 'candidate');
                                            if ($left_filter_count_switch == 'yes') {
                                                ?>
                                                <span class="filter-post-count"><?php echo $sector_count_post; ?></span>
                                            <?php } ?>
                                        </li>
                                        <?php
                                    }
                                }
                                $filter_itm_html = ob_get_clean();
                                $filter_html_arr[] = array(
                                    'title' => $sectoritem->name,
                                    'count' => $sector_count_post,
                                    'html' => $filter_itm_html
                                );
                                $number_option++;
                                $number_option_flag++;
                            }
                            if ($filter_sort_by == 'desc') {
                                krsort($filter_html_arr);
                            } else if ($filter_sort_by == 'alpha') {
                                usort($filter_html_arr, function ($a, $b) {
                                    return strcmp($a["title"], $b["title"]);
                                });
                            } else if ($filter_sort_by == 'count') {
                                usort($filter_html_arr, function ($a, $b) {
                                    if ($a['count'] == $b['count']) {
                                        $ret_val = 0;
                                    }
                                    $ret_val = ($b['count'] < $a['count']) ? -1 : 1;
                                    return $ret_val;
                                });
                            }

                            foreach ($filter_html_arr as $filtr_item_html) {
                                echo ($filtr_item_html['html']);
                            }
                            echo '</ul>';
                            if ($number_option_flag > 6) {
                                echo '<a href="javascript:void(0);" class="show-toggle-filter-list">' . esc_html__('+ see more', 'wp-jobsearch') . '</a>';
                            }
                        }

                        $sector_filter_html = ob_get_clean();
                        echo apply_filters('jobsearch_side_listin_filters_sector_html', $sector_filter_html, 'candidate', $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts);
                    } else {
                        ?>
                        <p><?php esc_html_e('No sector found. Please add from admin > job > sectors.', 'wp-jobsearch') ?></p>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
        $html .= ob_get_clean();
        if ($candidate_sector_filter == 'no') {
            $html = '';
        }
        return $html;
    }

    public function load_more_locations() {

        $page_num = isset($_POST['page_num']) && $_POST['page_num'] > 0 ? $_POST['page_num'] : 1;
        $global_rand_id = isset($_POST['param_rid']) ? $_POST['param_rid'] : 1;
        $q_agrs = isset($_POST['q_agrs']) ? $_POST['q_agrs'] : '';
        $left_filter_count_switch = isset($_POST['param_cousw']) ? $_POST['param_cousw'] : '';
        $order = isset($_POST['order']) ? $_POST['order'] : '';
        $orderby = isset($_POST['orderby']) ? maybe_unserialize(stripslashes($_POST['orderby'])) : '';
        //var_dump($orderby);

        $agrs_count = array();
        if ($q_agrs != '') {
            $q_agrs = stripslashes($q_agrs);
            $q_agrs = str_replace(array('lest_sign', 'gret_sign'), array('<', '>'), $q_agrs);
            $q_agrs = json_decode($q_agrs, true);
            $agrs_count = $q_agrs;
        }

        $loc_args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'fields' => 'all',
            'hide_empty' => false,
        );

        //$all_locs = get_terms('job-location', $loc_args);
        $all_locs = jobsearch_get_terms_woutparnt('job-location', $orderby, $order);

        if (!empty($all_locs)) {
            $h_list = self::get_terms_hierarchical($all_locs, '', 0, 0, $global_rand_id, $agrs_count, $left_filter_count_switch, 'array', false);
            $reults_per_page = 6;
            $start = ($page_num - 1) * ($reults_per_page);
            $offset = $reults_per_page;
            $paged_locs = array_slice($h_list, $start, $offset);
            $h_list_html = '';
            if (!empty($paged_locs)) {
                foreach ($paged_locs as $paged_loc) {
                    $h_list_html .= $paged_loc;
                }
            }
            echo json_encode(array('list' => $h_list_html));
        }
        die;
    }

    public static function get_terms_hierarchical($terms, $output = '', $parent_id = 0, $level = 0, $global_rand_id = '', $args_count = '', $left_filter_count_switch = '', $output_type = 'html', $output_break = true, $html_array = array()) {
        global $jobsearch_form_fields, $job_location_flag, $loc_counter, $sitepress;
        if (get_query_var('location') != '' && !isset($_REQUEST['location'])) {
            $get_queryvar_loc = get_query_var('location');
            $_REQUEST['location'] = $get_queryvar_loc;
        }

        $job_type_name = 'job-location';

        $job_type = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';

        foreach ($terms as $term) {
            if ($parent_id == $term->parent) {

                $rand_flag_id = rand(10000000, 999999999);

                $job_type_count_post = '';

                $location_slug = $term->slug;

                if (function_exists('icl_object_id') && function_exists('wpml_init_language_switcher') && isset($term->term_id)) {
                    $curr_lang_code = $sitepress->get_current_language();
                    $def_lang_code = $sitepress->get_default_language();
                    if ($def_lang_code != $curr_lang_code) {
                        $term_id = $term->term_id;
                        $orig_taxonmy_id = icl_object_id($term_id, 'job-location', false, $def_lang_code);
                        $sitepress->switch_lang($def_lang_code, true);
                        $orig_term = get_term_by('id', $orig_taxonmy_id, 'job-location');
                        if (isset($orig_term->slug)) {
                            $location_slug = $orig_term->slug;
                        }
                        $sitepress->switch_lang($curr_lang_code, true);
                    }
                }

                $location_count_post = jobsearch_get_taxanomy_location_item_count($left_filter_count_switch, $location_slug, 'job-location', $args_count, 'candidate');

                ob_start();
                ?>
                <li class="<?php echo 'location-level-' . $level ?><?php echo($left_filter_count_switch != 'yes' ? ' no-filter-counts' : '') ?>">
                    <?php
                    $job_type_selected = '';
                    if ($job_type == $location_slug) {
                        $job_type_selected = ' checked="checked"';
                    }
                    $jobsearch_form_fields->radio_field(
                            array(
                                'simple' => true,
                                'id' => 'job_location_' . $rand_flag_id,
                                'cus_name' => 'location',
                                'std' => $location_slug,
                                'ext_attr' => 'onchange="jobsearch_candidate_content_load(\'' . absint($global_rand_id) . ' \')"' . $job_type_selected,
                            )
                    );
                    ?>
                    <label for="<?php echo force_balance_tags('job_location_' . $rand_flag_id) ?>"><span></span><?php echo force_balance_tags($term->name); ?>
                    </label>
                    <?php if ($left_filter_count_switch == 'yes') { ?>
                        <span class="filter-post-count"><?php echo absint($location_count_post); ?></span>
                    <?php } ?>
                </li>
                <?php
                $job_location_flag++;
                $loc_counter++;

                if ($output_type == 'array') {
                    $output = ob_get_clean();
                } else {
                    $output .= ob_get_clean();
                }
                $html_array[] = $output;
                if ($output_type == 'array') {
                    $html_array = self::get_terms_hierarchical($terms, $output, $term->term_id, $level + 1, $global_rand_id, $args_count, $left_filter_count_switch, $output_type, $output_break, $html_array);
                } else {
                    $output = self::get_terms_hierarchical($terms, $output, $term->term_id, $level + 1, $global_rand_id, $args_count, $left_filter_count_switch, $output_type, $output_break, $html_array);
                }

                if ($loc_counter > 6 && $output_break === true) {
                    break;
                }
            }
        }
        if ($output_type == 'array') {
            return $html_array;
        }
        return $output;
    }

    static function cand_filter_joblocation_box_html_callback($html, $global_rand_id, $args_count, $left_filter_count_switch, $sh_atts) {
        global $jobsearch_form_fields, $job_location_flag, $loc_counter, $jobsearch_plugin_options, $jobsearch_gdapi_allocation;

        if (get_query_var('location') != '' && !isset($_REQUEST['location'])) {
            $get_queryvar_loc = get_query_var('location');
            $_REQUEST['location'] = $get_queryvar_loc;
        }

        $job_type_name = 'job-location';
        $loc_location1 = isset($_REQUEST['location_location1']) ? $_REQUEST['location_location1'] : '';
        $loc_location2 = isset($_REQUEST['location_location2']) ? $_REQUEST['location_location2'] : '';
        $loc_location3 = isset($_REQUEST['location_location3']) ? $_REQUEST['location_location3'] : '';

        $loc_location1 = jobsearch_esc_html($loc_location1);
        $loc_location2 = jobsearch_esc_html($loc_location2);
        $loc_location3 = jobsearch_esc_html($loc_location3);
        ?>
        <script type="text/javascript">
            var jobsearch_sloc_country = "<?php echo $loc_location1 ?>";
            var jobsearch_sloc_state = "<?php echo $loc_location2 ?>";
            var jobsearch_sloc_city = "<?php echo $loc_location3 ?>";
            var jobsearch_is_admin = "<?php echo is_admin(); ?>";
        </script>
        <?php
        $is_ajax = false;
        if (isset($_POST['action']) && $_POST['action'] == 'jobsearch_candidates_content') {
            $is_ajax = true;
        }
        $all_locations_type = isset($jobsearch_plugin_options['all_locations_type']) ? $jobsearch_plugin_options['all_locations_type'] : '';
        if ($all_locations_type == 'api') {
            $jobsearch_gdapi_allocation->load_locations_js(true, $is_ajax);
        }

        $loc_counter = 1;

        $job_type = isset($_REQUEST['location']) ? $_REQUEST['location'] : '';
        $job_type = jobsearch_esc_html($job_type);

        $job_loc_filter = isset($sh_atts['cand_filters_loc']) ? $sh_atts['cand_filters_loc'] : '';
        $job_loc_filter_view = isset($sh_atts['cand_filters_loc_view']) ? $sh_atts['cand_filters_loc_view'] : '';

        $filter_sort_by = isset($sh_atts['candidate_locfilters_sortby']) ? $sh_atts['candidate_locfilters_sortby'] : '';

        $loc_filter_collapse = isset($sh_atts['cand_filters_loc_collapse']) ? $sh_atts['cand_filters_loc_collapse'] : '';

        $filter_collapse_cval = 'open';
        if ($loc_filter_collapse == 'yes') {
            $filter_collapse_cval = 'close';
        }

        $filter_collapse_cname = 'cand_loc_filter_collapse';
        if (isset($_COOKIE[$filter_collapse_cname]) && $_COOKIE[$filter_collapse_cname] != '') {
            $filter_collapse_cval = $_COOKIE[$filter_collapse_cname];
            if ($_COOKIE[$filter_collapse_cname] == 'open') {
                $loc_filter_collapse = 'no';
            } else {
                $loc_filter_collapse = 'yes';
            }
        }
        ob_start();
        ?>

        <div class="jobsearch-filter-responsive-wrap">
            <div class="jobsearch-search-filter-wrap jobsearch-search-filter-toggle <?php echo($loc_filter_collapse == 'yes' ? 'jobsearch-remove-padding' : '') ?>">
                <div class="jobsearch-fltbox-title"><a href="javascript:void(0);"
                                                       data-cname="<?php echo($filter_collapse_cname) ?>"
                                                       data-cval="<?php echo($filter_collapse_cval) ?>"
                                                       class="jobsearch-click-btn"><?php echo esc_html__('Locations', 'wp-jobsearch'); ?></a>
                </div>
                <?php
                if ($job_loc_filter_view == 'dropdowns' && $all_locations_type != 'api') {
                    jobsearch_listins_locfilter_manula_dropdown($loc_filter_collapse, $global_rand_id, $is_ajax, 'candidate');
                } else if ($job_loc_filter_view == 'input') {
                    ?>
                    <div class="jobsearch-checkbox-toggle"
                         style="display: <?php echo($loc_filter_collapse == 'yes' ? 'none' : 'block') ?>;">
                        <ul class="jobsearch-checkbox">
                            <li>
                                <input type="text" name="location"
                                       placeholder="<?php echo esc_html__('Search by Location', 'wp-jobsearch'); ?>"
                                       value="<?php echo($job_type) ?>"
                                       onchange="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>)">
                            </li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="jobsearch-checkbox-toggle"
                         style="display: <?php echo($loc_filter_collapse == 'yes' ? 'none' : 'block') ?>;">
                             <?php
                             if ($all_locations_type == 'api') {
                                 if ($job_loc_filter != 'no') {
                                     wp_enqueue_script('jobsearch-location');
                                     wp_enqueue_script('jobsearch-gdlocation-api');
                                 }
                                 $jobsearch_locsetin_options = get_option('jobsearch_locsetin_options');

                                 $api_contries_list = $jobsearch_gdapi_allocation::get_countries();

                                 $loc_location1 = isset($_REQUEST['location_location1']) ? $_REQUEST['location_location1'] : '';
                                 $loc_location2 = isset($_REQUEST['location_location2']) ? $_REQUEST['location_location2'] : '';
                                 $loc_location3 = isset($_REQUEST['location_location3']) ? $_REQUEST['location_location3'] : '';
                                 $loc_location1 = jobsearch_esc_html($loc_location1);
                                 $loc_location2 = jobsearch_esc_html($loc_location2);
                                 $loc_location3 = jobsearch_esc_html($loc_location3);
                                 $loc_optionstype = isset($jobsearch_locsetin_options['loc_optionstype']) ? $jobsearch_locsetin_options['loc_optionstype'] : '';
                                 ?>
                            <script type="text/javascript">
                                var jobsearch_sloc_country = "<?php echo $loc_location1 ?>";
                                var jobsearch_sloc_state = "<?php echo $loc_location2 ?>";
                                var jobsearch_sloc_city = "<?php echo $loc_location3 ?>";
                                var jobsearch_is_admin = "<?php echo is_admin(); ?>";
                            </script>
                            <?php
                            $nameof_singl_contry = '';
                            $contry_singl_contry = isset($jobsearch_locsetin_options['contry_singl_contry']) ? $jobsearch_locsetin_options['contry_singl_contry'] : '';
                            if ($contry_singl_contry != '' && ($loc_optionstype == '2' || $loc_optionstype == '3')) {
                                $nameof_singl_contry = isset($api_contries_list[$contry_singl_contry]) ? $api_contries_list[$contry_singl_contry] : '';
                            }

                            $contry_order = isset($jobsearch_locsetin_options['contry_order']) ? $jobsearch_locsetin_options['contry_order'] : '';
                            $contry_order = $contry_order != '' ? $contry_order : 'alpha';
                            $contry_filtring = isset($jobsearch_locsetin_options['contry_filtring']) ? $jobsearch_locsetin_options['contry_filtring'] : '';
                            $contry_filtring = $contry_filtring != '' ? $contry_filtring : 'none';
                            $contry_filtr_limreslts = isset($jobsearch_locsetin_options['contry_filtr_limreslts']) ? $jobsearch_locsetin_options['contry_filtr_limreslts'] : '';
                            $contry_filtr_limreslts = $contry_filtr_limreslts <= 0 ? 1000000 : $contry_filtr_limreslts;
                            $contry_filtrinc_contries = isset($jobsearch_locsetin_options['contry_filtrinc_contries']) ? $jobsearch_locsetin_options['contry_filtrinc_contries'] : '';
                            $contry_filtrexc_contries = isset($jobsearch_locsetin_options['contry_filtrexc_contries']) ? $jobsearch_locsetin_options['contry_filtrexc_contries'] : '';
                            $contry_preselct = isset($jobsearch_locsetin_options['contry_preselct']) ? $jobsearch_locsetin_options['contry_preselct'] : '';
                            $contry_preselct = $contry_preselct != '' ? $contry_preselct : 'none';
                            $contry_presel_contry = isset($jobsearch_locsetin_options['contry_presel_contry']) ? $jobsearch_locsetin_options['contry_presel_contry'] : '';

                            // For saved country
                            if ($loc_location1 != '' && in_array($loc_location1, $api_contries_list)) {
                                $contry_preselct = 'by_contry';
                                $contry_singl_contry = $contry_presel_contry = array_search($loc_location1, $api_contries_list);
                            }
                            //
                            $continent_group = isset($jobsearch_locsetin_options['continent_group']) ? $jobsearch_locsetin_options['continent_group'] : '';
                            $continent_order = isset($jobsearch_locsetin_options['continent_order']) ? $jobsearch_locsetin_options['continent_order'] : '';
                            $continent_order = $continent_order != '' ? $continent_order : 'alpha';
                            $continent_filter = isset($jobsearch_locsetin_options['continent_filter']) ? $jobsearch_locsetin_options['continent_filter'] : '';
                            $continent_filter = $continent_filter != '' ? $continent_filter : 'none';
                            $continents_selected = isset($jobsearch_locsetin_options['continents_selected']) ? $jobsearch_locsetin_options['continents_selected'] : '';
                            //
                            $state_order = isset($jobsearch_locsetin_options['state_order']) ? $jobsearch_locsetin_options['state_order'] : '';
                            $state_order = $state_order != '' ? $state_order : 'alpha';
                            $state_filtring = isset($jobsearch_locsetin_options['state_filtring']) ? $jobsearch_locsetin_options['state_filtring'] : '';
                            $state_filtring = $state_filtring != '' ? $state_filtring : 'none';
                            $state_filtr_limreslts = isset($jobsearch_locsetin_options['state_filtr_limreslts']) ? $jobsearch_locsetin_options['state_filtr_limreslts'] : '';
                            $state_filtr_limreslts = $state_filtr_limreslts <= 0 ? 1000000 : $state_filtr_limreslts;
                            //
                            $city_order = isset($jobsearch_locsetin_options['city_order']) ? $jobsearch_locsetin_options['city_order'] : '';
                            $city_order = $city_order != '' ? $city_order : 'alpha';
                            $city_filtring = isset($jobsearch_locsetin_options['city_filtring']) ? $jobsearch_locsetin_options['city_filtring'] : '';
                            $city_filtring = $city_filtring != '' ? $city_filtring : 'none';
                            $city_filtr_limreslts = isset($jobsearch_locsetin_options['city_filtr_limreslts']) ? $jobsearch_locsetin_options['city_filtr_limreslts'] : '';
                            $city_filtr_limreslts = $city_filtr_limreslts <= 0 ? 1000000 : $city_filtr_limreslts;
                            //

                            $continents_class = '';
                            if ($continent_group == 'on') {
                                $continents_class = ' group-continents';
                                if ($continent_order == 'alpha') {
                                    $continents_class .= ' group-order-alpha';
                                } else if ($continent_order == 'by_population') {
                                    $continents_class .= ' group-order-pop';
                                } else if ($continent_order == 'north_america') {
                                    $continents_class .= ' group-order-na';
                                } else if ($continent_order == 'europe') {
                                    $continents_class .= ' group-order-eu';
                                } else if ($continent_order == 'africa') {
                                    $continents_class .= ' group-order-af';
                                } else if ($continent_order == 'oceania') {
                                    $continents_class .= ' group-order-oc';
                                } else if ($continent_order == 'asia') {
                                    $continents_class .= ' group-order-as';
                                } else if ($continent_order == 'rand') {
                                    $continents_class .= ' group-order-rand';
                                }

                                //
                                if ($continent_filter == 'by_select' && !empty($continents_selected) && is_array($continents_selected)) {
                                    $inc_continents_selected = implode('-', $continents_selected);
                                    $continents_class .= ' continent-include-' . $inc_continents_selected;
                                }
                            }

                            $contries_class = '';
                            if ($contry_order == 'alpha') {
                                $contries_class .= ' order-alpha';
                            } else if ($contry_order == 'by_population') {
                                $contries_class .= ' order-pop';
                            } else if ($contry_order == 'random') {
                                $contries_class .= ' order-rand';
                            }

                            if ($contry_filtring == 'limt_results' && $contry_filtr_limreslts > 0) {
                                $contries_class .= ' limit-pop-' . absint($contry_filtr_limreslts);
                            } else if ($contry_filtring == 'inc_contries' && !empty($contry_filtrinc_contries) && is_array($contry_filtrinc_contries)) {
                                $inc_contries_implist = implode('-', $contry_filtrinc_contries);
                                $contries_class .= ' include-' . $inc_contries_implist;
                            } else if ($contry_filtring == 'exc_contries' && !empty($contry_filtrexc_contries) && is_array($contry_filtrexc_contries)) {
                                $exc_contries_implist = implode('-', $contry_filtrexc_contries);
                                $contries_class .= ' exclude-' . $exc_contries_implist;
                            }

                            if ($contry_preselct == 'by_contry' && $contry_presel_contry != '') {
                                $contries_class .= ' presel-' . $contry_presel_contry;
                            } else if ($contry_preselct == 'by_user_ip') {
                                $contries_class .= ' presel-byip';
                            }

                            $states_class = '';
                            if ($state_order == 'alpha') {
                                $states_class .= ' order-alpha';
                            } else if ($state_order == 'by_population') {
                                $states_class .= ' order-pop';
                            } else if ($state_order == 'random') {
                                $states_class .= ' order-rand';
                            }

                            //
                            $cities_class = '';
                            if ($city_order == 'alpha') {
                                $cities_class .= ' order-alpha';
                            } else if ($city_order == 'by_population') {
                                $cities_class .= ' order-pop';
                            } else if ($city_order == 'random') {
                                $cities_class .= ' order-rand';
                            }
                            ?>
                            <ul class="jobsearch-row jobsearch-employer-profile-form">
                                <?php if ($loc_optionstype == '0' || $loc_optionstype == '1') { ?>
                                    <li class="jobsearch-column-12">
                                        <label><?php esc_html_e('Country', 'wp-jobsearch') ?></label>
                                        <div id="jobsearch-gdapilocs-contrycon" data-val="<?php echo($loc_location1) ?>"
                                             class="jobsearch-profile-select">
                                            <select name="location_location1" <?php echo('class="countries" id="countryId"') ?>>
                                                <option value=""><?php esc_html_e('Select Country', 'wp-jobsearch') ?></option>
                                                <?php
                                                if ($is_ajax) {
                                                    foreach ($api_contries_list as $api_cntry_key => $api_cntry_val) {
                                                        $api_cntry_key_code = !empty($api_cntry_key->code) ? $api_cntry_key->code : '';
                                                        $api_cntry_val_code = !empty($api_cntry_val->code) ? $api_cntry_val->code : '';
                                                        $api_cntry_val_name = !empty($api_cntry_val->name) ? $api_cntry_val->name : '';
                                                        ?>
                                                        <option value="<?php echo($api_cntry_val_code) ?>" <?php echo($api_cntry_val_code == $loc_location1 ? 'selected="selected"' : '') ?>
                                                                countryid="<?php echo($api_cntry_key_code) ?>"><?php echo($api_cntry_val_name) ?></option>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </li>
                                <?php } ?>

                                <?php if ($loc_optionstype != '4') { ?>
                                    <li class="jobsearch-column-12">
                                        <label><?php esc_html_e('State', 'wp-jobsearch') ?></label>
                                        <?php
                                        if ($loc_optionstype == '2' || $loc_optionstype == '3') {
                                            //echo '<input type="hidden" name="location_location1" value="' . $nameof_singl_contry . '"/>';
                                            ?>
                                            <input type="hidden" id="countryId"
                                                   value="<?php echo($contry_singl_contry) ?>"/>
                                               <?php } ?>
                                        <div id="jobsearch-gdapilocs-statecon" data-val="<?php echo($loc_location2) ?>"
                                             class="jobsearch-profile-select">
                                            <select name="location_location2" <?php echo('class="location2-states states" id="stateId"') ?>>
                                                <option value=""><?php esc_html_e('Select State', 'wp-jobsearch') ?></option>
                                                <?php
                                                if ($is_ajax) {
                                                    if ($loc_optionstype == '2' || $loc_optionstype == '3') {
                                                        $states_cntry = $nameof_singl_contry;
                                                    } else {
                                                        $states_cntry = $loc_location1;
                                                    }
                                                    if ($states_cntry != '') {
                                                        $api_states_list = jobsearch_allocation_settings_handle::get_states($states_cntry);
                                                        foreach ($api_states_list as $api_state_key => $api_state_val) {
                                                            ?>
                                                            <option value="<?php echo($api_state_val->state_name) ?>" <?php echo($api_state_val->state_name == $loc_location2 ? 'selected="selected"' : '') ?>><?php echo($api_state_val->state_name) ?></option>
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </li>
                                <?php } ?>
                                <?php if ($loc_optionstype == '1' || $loc_optionstype == '2' || $loc_optionstype == '4') { ?>
                                    <li class="jobsearch-column-12">
                                        <label><?php esc_html_e('City', 'wp-jobsearch') ?></label>
                                        <div id="jobsearch-gdapilocs-citycon" data-val="<?php echo($loc_location3) ?>"
                                             class="jobsearch-profile-select">
                                            <select name="location_location3" <?php echo('class="cities jobsearch-cities" id="cityId"') ?>>
                                                <option value=""><?php esc_html_e('Select City', 'wp-jobsearch') ?></option>
                                                <?php
                                                if ($is_ajax) {
                                                    if (isset($api_states_list) && !empty($api_states_list) && $loc_location2 != '') {
                                                        $api_cities_list = jobsearch_allocation_settings_handle::get_cities('', $loc_location2);
                                                        foreach ($api_cities_list as $api_city_key => $api_city_val) {
                                                            ?>
                                                            <option value="<?php echo($api_city_val->city_name) ?>" <?php echo($api_city_val->city_name == $loc_location3 ? 'selected="selected"' : '') ?>
                                                                    ><?php echo($api_city_val->city_name) ?></option>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                            </select>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                            <div class="onsubmit-apilocs-con">
                                <a href="javascript:void(0);" class="jobsearch-onsubmit-apilocs btn jobsearch-bgcolor"
                                   onclick="jobsearch_candidate_content_load(<?php echo absint($global_rand_id); ?>);"><?php esc_html_e('Submit', 'wp-jobsearch') ?></a>
                            </div>
                            <?php
                        } else {
                            // parse query string and create hidden fileds
                            $job_type_args = array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'fields' => 'all',
                                'hide_empty' => false,
                            );

                            $tax_order_by = 'name';
                            $tax_order = 'ASC';
                            if ($filter_sort_by == 'count') {
                                $tax_order_by = array('meta_value_num', 'active_candidates_loc_count');
                                $tax_order = 'DESC';
                            } else if ($filter_sort_by == 'asc') {
                                $tax_order_by = 'term_id';
                            } else if ($filter_sort_by == 'desc') {
                                $tax_order_by = 'term_id';
                                $tax_order = 'DESC';
                            }

                            //$all_job_type = get_terms('job-location', $job_type_args);
                            $all_job_type = jobsearch_get_terms_woutparnt('job-location', $tax_order_by, $tax_order);

                            $total_pages = 1;
                            $total_records = !empty($all_job_type) ? count($all_job_type) : 0;
                            $reults_per_page = 6;
                            if ($total_records > 0 && $reults_per_page > 0 && $total_records > $reults_per_page) {
                                $total_pages = ceil($total_records / $reults_per_page);
                            }

                            ob_start();
                            if (!empty($all_job_type)) {
                                echo '<ul class="jobsearch-checkbox"> ';
                                $job_location_flag = 1;
                                echo self::get_terms_hierarchical($all_job_type, '', 0, 0, $global_rand_id, $args_count, $left_filter_count_switch);
                                echo '</ul>';
                            } else {
                                ?>
                                <p><?php esc_html_e('No location found. Please add from admin > job > locations.', 'wp-jobsearch') ?></p>
                                <?php
                            }

                            if ($loc_counter > 6) {
                                $agrs_count_json = json_encode($args_count);
                                $agrs_count_json = str_replace(array('<', '>'), array('lest_sign', 'gret_sign'), $agrs_count_json);
                                echo '<a href="javascript:void(0);" class="show-toggle-filter-list jobsearch-loadmore-locations" data-ptype="candidate" data-pnum="2" data-order="' . $tax_order . '" data-orderby=\'' . maybe_serialize($tax_order_by) . '\' data-tpgs="' . $total_pages . '" data-rid="' . $global_rand_id . '" data-cousw="' . $left_filter_count_switch . '">' . esc_html__('+ see more', 'wp-jobsearch') . ' <small class="loc-filter-loder"></small></a>';
                                echo '<input type="hidden" name="loc_count_qargs_' . $global_rand_id . '" value=\'' . ($agrs_count_json) . '\'>';
                            }
                            $loc_filtrs_html = ob_get_clean();
                            echo apply_filters('jobsearch_cand_sidefilters_locations_html', $loc_filtrs_html, $global_rand_id);
                        }
                        ?>

                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
        $html .= ob_get_clean();
        if ($job_loc_filter == 'no') {
            $html = '';
        }
        return $html;
    }

}

global $Jobsearch_CandidateFilterHTML_obj;
// class Jobsearch_CandidateFilterHTML 
$Jobsearch_CandidateFilterHTML_obj = new Jobsearch_CandidateFilterHTML();
