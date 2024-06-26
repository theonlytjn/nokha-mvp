<?php

/**
 * File Type: Password Change Request Email Templates
 * For trigger email use following hook
 * 
 * $user_data = wp_get_current_user();
 * do_action('jobsearch_reset_password_request', $user_data, $post_id);
 * 
 */
if (!class_exists('jobsearch_reset_password_request_template')) {

    class jobsearch_reset_password_request_template {

        public $email_template_type;
        public $codes;
        public $type;
        public $group;
        public $user;
        public $key;
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

            add_action('init', array($this, 'jobsearch_reset_password_request_template_init'), 1, 0);
            add_filter('jobsearch_reset_password_request_filter', array($this, 'jobsearch_reset_password_request_filter_callback'), 1, 4);
            add_filter('jobsearch_email_template_settings', array($this, 'template_settings_callback'), 12, 1);
            add_action('jobsearch_reset_password_request', array($this, 'jobsearch_reset_password_request_callback'), 10, 2);
        }

        public function jobsearch_reset_password_request_template_init() {
            $this->user = array();
            $this->key = '';
            $this->rand = rand(0, 99999);
            $this->group = 'job';
            $this->type = 'reset_password_request';
            $this->filter = 'reset_password_request';
            $this->email_template_db_id = 'reset_password_request';
            $this->switch_label = esc_html__('Request to change Password', 'wp-jobsearch');
            $this->default_subject = esc_html__('Request to change Password', 'wp-jobsearch');
            $this->default_recipients = '';
            $default_content = esc_html__('Default content', 'wp-jobsearch');
            $default_content = apply_filters('jobsearch_reset_password_request_filter', $default_content, 'html', 'reset-password-request', 'email-templates');
            $this->default_content = $default_content;
            $this->email_template_prefix = 'reset_password_request';
            $this->email_template_group = 'other';
            $this->codes = array(
                // value_callback replace with function_callback tag replace with var
                array(
                    'var' => '{username}',
                    'display_text' => esc_html__('Username', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_username'),
                ),
                array(
                    'var' => '{first_name}',
                    'display_text' => esc_html__('First Name', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_user_fname'),
                ),
                array(
                    'var' => '{last_name}',
                    'display_text' => esc_html__('Last Name', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_user_lname'),
                ),
                array(
                    'var' => '{user_email}',
                    'display_text' => esc_html__('User Email', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_user_email'),
                ),
                array(
                    'var' => '{reset_password_link}',
                    'display_text' => esc_html__('Reset Password Link', 'wp-jobsearch'),
                    'function_callback' => array($this, 'get_reset_pass_link'),
                ),
            );

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

        public function jobsearch_reset_password_request_callback($user = '', $key = '') {
            global $sitepress;
            $lang_code = '';
            if ( function_exists('icl_object_id') && function_exists('wpml_init_language_switcher') ) {
                $lang_code = $sitepress->get_current_language();
            }

            $this->user = $user;
            $this->key = $key;
            $template = $this->get_template();
            // checking email notification is enable/disable
            if (isset($template['switch']) && $template['switch'] == 1) {

                $blogname = get_option('blogname');
                $admin_email = get_option('admin_email');
                $sender_detail_header = '';
                if (isset($template['from']) && $template['from'] != '') {
                    $sender_detail_header = $template['from'];
                    if (isset($template['from_name']) && $template['from_name'] != '') {
                        $sender_detail_header = $template['from_name'] . ' <' . $sender_detail_header . '> ';
                    }
                }

                // getting template fields
                $subject = (isset($template['subject']) && $template['subject'] != '' ) ? $template['subject'] : __('Request Password Change', 'wp-jobsearch');
                $subject = JobSearch_plugin::jobsearch_replace_variables($subject, $this->codes);
                
                $from = (isset($sender_detail_header) && $sender_detail_header != '') ? $sender_detail_header : esc_attr($blogname) . ' <' . $admin_email . '>';
                $recipients = (isset($template['recipients']) && $template['recipients'] != '') ? $template['recipients'] : $this->get_user_email();
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

                $args = array(
                    'to' => $recipients,
                    'subject' => $subject,
                    'from' => $from,
                    'message' => $email_message,
                    'email_type' => $email_type,
                    'class_obj' => $this, // temprary comment
                );
                do_action('jobsearch_send_mail', $args);
                jobsearch_reset_password_request_template::$is_email_sent1 = $this->is_email_sent;
            }
        }

        public static function template_path() {
            return apply_filters('jobsearch_plugin_template_path', 'wp-jobsearch/');
        }

        public function jobsearch_reset_password_request_filter_callback($html, $slug = '', $name = '', $ext_template = '') {
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
            $email_template_options['reset_password_request']['rand'] = $this->rand;
            $email_template_options['reset_password_request']['email_template_prefix'] = $this->email_template_prefix;
            $email_template_options['reset_password_request']['email_template_group'] = $this->email_template_group;
            $email_template_options['reset_password_request']['default_var'] = $this->default_var;
            return $email_template_options;
        }

        public function get_template() {
            return JobSearch_plugin::get_template($this->email_template_db_id, $this->codes, $this->default_content);
        }

        public function get_username() {
            $user_name = $this->user->user_login;
            return $user_name;
        }

        public function get_user_fname() {
            $user_fname = $this->user->first_name;
            $user_fname = $user_fname != '' ? $user_fname : '-';
            return $user_fname;
        }

        public function get_user_lname() {
            $user_lname = $this->user->last_name;
            $user_lname = $user_lname != '' ? $user_lname : '-';
            return $user_lname;
        }

        public function get_user_email() {
            $email = $this->user->user_email;
            return $email;
        }

        public function get_reset_pass_link() {
            $key = $this->key;
            $user_login = $this->user->user_login;            
            $link = home_url("/?login_action=jobsearch_rp&key=$key&login=" . rawurlencode($user_login));
            return $link;
        }

    }

    new jobsearch_reset_password_request_template();
}