<?php

if (!defined('ABSPATH')) {
    die;
}

// main plugin class
class JobSearch_Freelancer_Jobs_Scraping_Scheduler {

    // hook things up
    public function __construct() {
        add_action('jobsearch_schedule_jobs_form_type_opts_after', array($this, 'schedule_jobs_form_opt'));
        add_action('jobsearch_schedule_jobs_form_type_field_after', array($this, 'schedule_jobs_form_fields'), 10, 2);

        //
        add_action('jobsearch_job_import_schedule_cronruner', array($this, 'job_import_schedule_cron'), 10, 2);
    }

    public function schedule_jobs_form_opt() {
        $freelancer_import_jobs = get_option('jobsearch_integration_freelancer_jobs');
        if ($freelancer_import_jobs == 'on') {
            ?>
            <option value="freelancer"><?php esc_html_e('Freelancer', 'wp-jobsearch') ?></option>
            <?php
        }
    }

    public function schedule_jobs_form_fields($import_from = '', $schedule_itm = '') {
        $freelancer_import_jobs = get_option('jobsearch_integration_freelancer_jobs');
        if ($freelancer_import_jobs == 'on' && $import_from == 'freelancer') {
            $import_page_url = isset($schedule_itm['schedule_page_url']) ? $schedule_itm['schedule_page_url'] : '';
            ?>
            <div class="jobsearch-element-field adin-schedinteg-keywrdcon">
                <div class="elem-label">
                    <label><?php esc_html_e('URL', 'wp-jobsearch') ?></label>
                </div>
                <div class="elem-field">
                    <input type="text" name="schedule_page_url" value="<?php echo ($import_page_url) ?>">
                </div>
            </div>
            <?php
        }
    }

    public function job_import_schedule_cron($import_from, $schedule_itm) {
        if ($import_from == 'freelancer') {
            $base_url = isset($schedule_itm['schedule_page_url']) ? $schedule_itm['schedule_page_url'] : '';
            $location = isset($schedule_itm['schedule_import_location']) ? $schedule_itm['schedule_import_location'] : '';
            $keyword = isset($schedule_itm['schedule_import_keyword']) ? $schedule_itm['schedule_import_keyword'] : '';
            $expire_days = isset($schedule_itm['schedule_import_expire_on']) ? $schedule_itm['schedule_import_expire_on'] : '';
            $job_username = isset($schedule_itm['job_username']) ? $schedule_itm['job_username'] : '';

            $num_jobs = 25;
            $platform = 'freelancer';

            $det_base_url = 'https://www.freelancer.com';

            $job_username = sanitize_text_field($_POST['job_username']);

            $job_actcount = isset($_POST['job_actcount']) && $_POST['job_actcount'] > 0 ? $_POST['job_actcount'] : 0;

            $query_arr = array();
            if ($keyword != '') {
                $query_arr[] = 'keyword=' . urlencode($keyword);
            }

            if ($location != '') {
                $query_arr[] = 'l=' . urlencode($location);
            }

            if (!empty($query_arr)) {
                $base_url = $base_url . '?' . implode('&', $query_arr);
            }
            $html = file_get_contents($base_url);
            //var_dump($base_url);
            //var_dump($html);
            $dom = new DOMDocument();

            @$dom->loadHTML($html);

            $xpath = new DOMXPath($dom);

            $jobs_elements = $xpath->query("//a[contains(@class, 'JobSearchCard-primary-heading-link')]");

            $jobs_elements_length = isset($jobs_elements->length) ? $jobs_elements->length : 0;

            if ($jobs_elements_length > 0) {
                
                $found_jobs = $jobs_elements_length;
                $js_html = '';
                foreach ($jobs_elements as $job_element) {
                
                    $u = explode('?', $job_element->getAttribute('href'));

                    $jk = $u[0];

                    $job_url = $det_base_url . $jk;

                    $existing_id = jobsearch_get_postmeta_id_byval('jobsearch_field_job_detail_url', $job_url);
                    $skiping_job = false;
                    if ($existing_id > 0) {
                        //
                        $skiping_job = true;
                    } else {
                        $job_detail = @file_get_html($job_url);

                        $job_title = '';
                        if ($job_detail) {
                            foreach ($job_detail->find('h1.PageProjectViewLogout-header-title') as $job_title_html) {
                                $job_title = wp_kses($job_title_html, array());
                            }
                        }
                        
                        if ($job_title != '') {
                            $company_image = '';
                            $job_company = '';
                            $job_salary = '';
                            $job_desc = '';
                            // foreach ($job_detail->find('div.jobsearch-CompanyInfoWithoutHeaderImage') as $company_image_html) {
                            //     $company_image = $company_image_html;
                            // }
                            // foreach ($job_detail->find('div.jobsearch-InlineCompanyRating>div.icl-u-xs-mr--xs') as $job_company_html) {
                            //     $job_company = wp_kses($job_company_html, array());
                            // }
                            // foreach ($job_detail->find('div.jobsearch-JobMetadataHeader-item') as $job_salary_html) {
                            //     $job_salary = wp_kses($job_salary_html, array());
                            // }
                            foreach ($job_detail->find('div.PageProjectViewLogout-detail') as $job_desc_html) {
                                $job_desc = esc_html($job_desc_html);
                            }

                            $job_desc = str_replace(array('&lt;', '&gt;'), array('<', '>'), $job_desc);
                            //var_dump($job_desc);
                            
                            // $freelancer_job_type = $job_detail->find('span.jobsearch-JobMetadataHeader-item');
                            // $freelancer_job_type = isset($freelancer_job_type[0]) ? $freelancer_job_type[0] : '';

                            // if ($freelancer_job_type != '') {
                            //     $freelancer_job_type = wp_kses($freelancer_job_type, array());
                            //     $freelancer_job_type = str_replace(array('<', '>', '-', '!'), array('', '', '', ''), $freelancer_job_type);
                            // }

                            $job_location = '';

                            $job_salary_min = '';
                            $job_salary_max = '';
                            
                            if ($job_salary != '') {
                                $job_salary = str_replace(array(','), array(''), $job_salary);
                                $job_salary_parts = explode('-', $job_salary);
                                if (isset($job_salary_parts[0]) && isset($job_salary_parts[1])) {
                                    preg_match('!\d+!', $job_salary_parts[0], $job_salary_min);
                                    if (isset($job_salary_min[0])) {
                                        $job_salary_min = $job_salary_min[0];
                                    } else {
                                        $job_salary_min = '';
                                    }
                                    preg_match('!\d+!', $job_salary_parts[1], $job_salary_max);
                                    if (isset($job_salary_max[0])) {
                                        $job_salary_max = $job_salary_max[0];
                                    } else {
                                        $job_salary_max = '';
                                    }
                                } else {
                                    preg_match('!\d+!', $job_salary, $job_salary_min);
                                    if (isset($job_salary_min[0])) {
                                        $job_salary_min = $job_salary_min[0];
                                    } else {
                                        $job_salary_min = '';
                                    }
                                }
                            }
                            $post_data = array(
                                'post_type' => 'job',
                                'post_title' => $job_title,
                                'post_content' => $job_desc,
                                'post_status' => 'publish',
                            );
                            // Insert the job into the database
                            $post_id = wp_insert_post($post_data);

                            //
                            update_post_meta($post_id, 'jobsearch_job_employer_status', 'approved');
                            update_post_meta($post_id, 'jobsearch_field_job_featured', '');

                            // Insert job username meta key
                            if ($job_username > 0) {
                                update_post_meta($post_id, 'jobsearch_field_job_posted_by', $job_username, true);
                            } else {
                                if ($job_company != '') {
                                    jobsearch_fake_generate_employer_byname($job_company, $post_id);
                                }
                            }

                            // Insert job posted on meta key
                            update_post_meta($post_id, 'jobsearch_field_job_publish_date', current_time('timestamp'), true);

                            // Insert job expired on meta key
                            $expired_date = date('d-m-Y H:i:s', strtotime("$expire_days days", current_time('timestamp')));
                            update_post_meta($post_id, 'jobsearch_field_job_expiry_date', strtotime($expired_date), true);

                            // Insert job status meta key
                            update_post_meta($post_id, 'jobsearch_field_job_status', 'approved', true);

                            // Insert job address meta key
                            if ($job_location != '') {
                                update_post_meta($post_id, 'jobsearch_field_location_address', $job_location, true);
                            }

                            update_post_meta($post_id, 'jobsearch_field_job_salary', $job_salary_min, true);
                            update_post_meta($post_id, 'jobsearch_field_job_max_salary', $job_salary_max, true);

                            // Insert job referral meta key
                            update_post_meta($post_id, 'jobsearch_job_referral', 'freelancer', true);

                            // Insert job detail url meta key
                            update_post_meta($post_id, 'jobsearch_field_job_detail_url', ($job_url), true);
                            update_post_meta($post_id, 'jobsearch_field_job_jk', ($jk), true);

                            update_post_meta($post_id, 'jobsearch_field_job_apply_type', 'external', true);
                            update_post_meta($post_id, 'jobsearch_field_job_apply_url', $job_url, true);
                            
                            // if ($freelancer_job_type != '') {
                            //     if (strpos($freelancer_job_type, ',')) {
                            //         $freelancer_job_types = explode(',', $freelancer_job_type);
                                    
                            //         $type_term_ids = array();
                            //         foreach ($freelancer_job_types as $the_job_type) {
                            //             $type_term = get_term_by('name', $the_job_type, 'jobtype');
                            //             if (empty($type_term)) {
                            //                 wp_insert_term($the_job_type, 'jobtype');
                            //                 $type_term = get_term_by('name', $the_job_type, 'jobtype');
                            //             }
                            //             $type_term_ids[] = $type_term->term_id;
                            //         }
                            //         wp_set_post_terms($post_id, $type_term_ids, 'jobtype');
                            //     } else {
                            //         $type_term = get_term_by('name', $freelancer_job_type, 'jobtype');
                            //         if (empty($type_term)) {
                            //             wp_insert_term($freelancer_job_type, 'jobtype');
                            //             $type_term = get_term_by('name', $freelancer_job_type, 'jobtype');
                            //         }
                            //         wp_set_post_terms($post_id, $type_term->term_id, 'jobtype');
                            //     }
                            // }

                            $job_actcount++;
                        }
                    }

                    if ($job_actcount >= $num_jobs) {
                        break;
                    }
                    //
                }
            }
        }
    }

}

return new JobSearch_Freelancer_Jobs_Scraping_Scheduler();
