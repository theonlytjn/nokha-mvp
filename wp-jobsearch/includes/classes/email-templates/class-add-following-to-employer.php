<?php

if (!class_exists('jobsearch_job_following_to_employer_template')) {

    class jobsearch_job_following_to_employer_template {

        public $email_template_type;
        public $codes;
        public $type;
        public $group;
        public $user;
        public $employer_id;
        public $is_email_sent;
        public $email_template_prefix;
        public $email_template_group;
        public $default_content;
        public $default_subject;
        public $default_recipients;
        public $switch_label;
        public $email_template_db_id;
        public $default_var;
        public $rand;
        public static $is_email_sent1;

        public function __construct() {

            add_action('init', array($this, 'jobsearch_job_following_to_employer_template_init'), 1, 0);
            add_filter('jobsearch_job_following_to_employer_filter', array($this, 'jobsearch_job_following_to_employer_filter_callback'), 1, 4);
            add_filter('jobsearch_email_template_settings', array($this, 'template_settings_callback'), 12, 1);
            add_action('jobsearch_job_following_to_employer_notify', array($this, 'jobsearch_job_following_to_employer_callback'), 10, 2);
        }

        public function jobsearch_job_following_to_employer_template_init() {
            $this->user = array();
            $this->rand = rand(0, 99999);
            $this->group = 'job';
            $this->type = 'job_following_to_employer';
            $this->filter = 'job_following_to_employer';
            $this->email_template_db_id = 'job_following_to_employer';
            $this->switch_label = esc_html__('Followed by candidate to employer', 'wp-jobsearch');
            $this->default_subject = esc_html__('You are followed by {candidate_name}', 'wp-jobsearch');
            $this->default_recipients = '';
            $default_content = esc_html__('Default content', 'wp-jobsearch');
            $default_content = apply_filters('jobsearch_job_following_to_employer_filter', $default_content, 'html', 'following-to-employer', 'email-templates');
            $this->default_content = $default_content;
            $this->email_template_prefix = 'job_following_to_employer';
            $this->email_template_group = 'employer';
            $this->codes = apply_filters('jobsearch_follow_by_cand_toemp_codes', array(
                // value_callback replace with function_callback tag replace with var
                array(
                    'var' => '{employer_name}',
                    'display_text' => esc_html__('Employer name', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_employer_name'),
                ),
                array(
                    'var' => '{candidate_name}',
                    'display_text' => esc_html__('Candidate name', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_candidate_name'),
                ),
                array(
                    'var' => '{user_job_title}',
                    'display_text' => esc_html__('Candidate Job Title', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_user_job_title'),
                ),
                array(
                    'var' => '{user_phone}',
                    'display_text' => esc_html__('Candidate Phone', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_user_phone'),
                ),
                array(
                    'var' => '{profile_link}',
                    'display_text' => esc_html__('Candidate profile link', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_candidate_profile_link'),
                ),
            ), $this);

            $this->default_var = array(
                'switch_label' => $this->switch_label,
                'default_subject' => $this->default_subject,
                'default_recipients' => $this->default_recipients,
                'default_content' => $this->default_content,
                'group' => $this->group,
                'type' => $this->type,
                'filter' => $this->filter,
                'codes' => $this->codes,
            );
        }

        public function jobsearch_job_following_to_employer_callback($user = '', $employer_id = '') {

            global $sitepress, $jobsearch_plugin_options, $jobsearch_glob_userobj;
            $lang_code = '';
            if ( function_exists('icl_object_id') && function_exists('wpml_init_language_switcher') ) {
                $lang_code = $sitepress->get_current_language();
            }
            
            $jobsearch_glob_userobj = $user;
            
            $this->user = $user;
            $this->employer_id = $employer_id;
            $template = $this->get_template();
            // checking email notification is enable/disable
            if (isset($template['switch']) && $template['switch'] == 1) {

                $blogname = get_option('blogname');
                $admin_email = get_option('admin_email');
                
                $email_temps_data = get_option('jobsearch_email_templates');
                
                $sender_detail_header = '';
                if (isset($template['from']) && $template['from'] != '') {
                    $sender_detail_header = $template['from'];
                    if (isset($template['from_name']) && $template['from_name'] != '') {
                        $sender_detail_header = $template['from_name'] . ' <' . $sender_detail_header . '> ';
                    }
                }

                // getting template fields
                $subject = (isset($template['subject']) && $template['subject'] != '' ) ? $template['subject'] : __('You are followed', 'wp-jobsearch');
                $subject = JobSearch_plugin::jobsearch_replace_variables($subject, $this->codes);
                
                $this_temp_type = $this->type;
                $to_send_email = $this->get_job_added_email();

                $from = (isset($sender_detail_header) && $sender_detail_header != '') ? $sender_detail_header : esc_attr($blogname) . ' <' . $admin_email . '>';
                $recipients = (isset($template['recipients']) && $template['recipients'] != '') ? $template['recipients'] : $to_send_email;
                $email_type = (isset($template['email_type']) && $template['email_type'] != '') ? $template['email_type'] : 'html';
                
                $email_message = isset($template['email_template']) ? $template['email_template'] : '';
                
                if ( function_exists('icl_object_id') && function_exists('wpml_init_language_switcher') ) {
                    $temp_trnaslated = get_option('jobsearch_translate_email_templates');
                    $template_type = $this->type;
                    if (isset($temp_trnaslated[$template_type]['lang_' . $lang_code]['subject'])) {
                        $subject = $temp_trnaslated[$template_type]['lang_' . $lang_code]['subject'];
                        $subject = JobSearch_plugin::jobsearch_replace_variables($subject, $this->codes);
                    }
                    if (isset($temp_trnaslated[$template_type]['lang_' . $lang_code]['content'])) {
                        $email_message = $temp_trnaslated[$template_type]['lang_' . $lang_code]['content'];
                        $email_message = JobSearch_plugin::jobsearch_replace_variables($email_message, $this->codes);
                    }
                }
                
                //
                //var_dump($att_file_id);
                $user_email = $user->user_email;
                $user_name = $user->display_name;
                $user_name = apply_filters('jobsearch_user_display_name', $user_name, $user);
                
                $args = array(
                    'to' => $recipients,
                    'subject' => $subject,
                    'from' => $from,
                    'from_name' => $user_name,
                    'from_email' => $user_email,
                    'message' => $email_message,
                    'email_type' => $email_type,
                    'class_obj' => $this, // temprary comment
                );
                
                $args = apply_filters('jobsearch_job_following_to_employer_email_args', $args, $user, $employer_id);
                do_action('jobsearch_send_mail', $args);
                jobsearch_job_following_to_employer_template::$is_email_sent1 = $this->is_email_sent;
            }
        }

        public static function template_path() {
            return apply_filters('jobsearch_plugin_template_path', 'wp-jobsearch/');
        }

        public function jobsearch_job_following_to_employer_filter_callback($html, $slug = '', $name = '', $ext_template = '') {
            ob_start();
            $html = '';
            $template = '';
            if ($ext_template != '') {
                $ext_template = trailingslashit($ext_template);
            }
            if ($name) {
                $template = locate_template(array("{$slug}-{$name}.php", self::template_path() . "templates/{$ext_template}/{$slug}-{$name}.php"));
            }
            if (!$template && $name && file_exists(jobsearch_plugin_get_path() . "templates/{$ext_template}/{$slug}-{$name}.php")) {
                $template = jobsearch_plugin_get_path() . "templates/{$ext_template}{$slug}-{$name}.php";
            }
            if (!$template) {
                $template = locate_template(array("{$slug}.php", self::template_path() . "{$ext_template}/{$slug}.php"));
            }
            //echo $template;exit;
            if ($template) {
                load_template($template, false);
            }
            $html = ob_get_clean();
            return $html;
        }

        public function template_settings_callback($email_template_options) {

            $rand = rand(123, 8787987);
            $email_template_options['job_following_to_employer']['rand'] = $this->rand;
            $email_template_options['job_following_to_employer']['email_template_prefix'] = $this->email_template_prefix;
            $email_template_options['job_following_to_employer']['email_template_group'] = $this->email_template_group;
            $email_template_options['job_following_to_employer']['default_var'] = $this->default_var;
            return $email_template_options;
        }

        public function get_template() {
            return JobSearch_plugin::get_template($this->email_template_db_id, $this->codes, $this->default_content);
        }

        public function get_job_added_email() {

            $employer_id = $this->employer_id;
            $employer_user_id = jobsearch_get_employer_user_id($employer_id);
            $user_obj = get_user_by('ID', $employer_user_id);

            $email = $user_obj->user_email;
            return $email;
        }
        
        public function get_employer_name() {
            $employer_id = $this->employer_id;
            
            $name = get_the_title($employer_id);
            
            return $name;
        }

        public function get_candidate_name() {

            $user_name = $this->user->display_name;
            $user_obj = $this->user;
            $user_name = apply_filters('jobsearch_user_display_name', $user_name, $user_obj);
            return $user_name;
        }

        public function get_candidate_profile_link() {

            $user_id = $this->user->ID;
            $profile_link = '-';
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            if ($user_is_candidate) {
                $candidate_id = jobsearch_get_user_candidate_id($user_id);
                $profile_link = get_permalink($candidate_id);
            }
            return $profile_link;
        }

        public function get_user_phone() {
            $user_id = $this->user->ID;
            $phone_number = '-';
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            if ($user_is_candidate) {
                $candidate_id = jobsearch_get_user_candidate_id($user_id);
                $phone_number = get_post_meta($candidate_id, 'jobsearch_field_user_phone', true);
            }
            $phone_number = $phone_number != '' ? $phone_number : '-';

            return $phone_number;
        }

        public function get_user_job_title() {
            $user_id = $this->user->ID;
            $user_job_title = '-';
            $user_is_candidate = jobsearch_user_is_candidate($user_id);
            if ($user_is_candidate) {
                $candidate_id = jobsearch_get_user_candidate_id($user_id);
                $user_job_title = get_post_meta($candidate_id, 'jobsearch_field_candidate_jobtitle', true);
                $user_job_title = apply_filters('jobsearch_cand_jobtitle_indisplay', $user_job_title, $candidate_id);
            }
            $user_job_title = $user_job_title != '' ? $user_job_title : '-';
            
            return $user_job_title;
        }

    }

    new jobsearch_job_following_to_employer_template();
}