<?php

namespace Wp_JobsearchElementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;

if (!defined('ABSPATH'))
    exit;

/**
 * @since 1.1.0
 */
class SingleJobCustomFields extends Widget_Base
{

    /**
     * Retrieve the widget name.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'single-job-custom-fields';
    }

    /**
     * Retrieve the widget title.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title()
    {
        return __('Single Job Custom Fields', 'wp-jobsearch');
    }

    /**
     * Retrieve the widget icon.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'fa fa-link';
    }

    /**
     * Retrieve the list of categories the widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * Note that currently Elementor supports only one category.
     * When multiple categories passed, Elementor uses the first one.
     *
     * @since 1.1.0
     *
     * @access public
     *
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return ['jobsearch-job-single'];
    }

    /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section', [
                'label' => __('Job Custom Fields Settings', 'wp-jobsearch'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Custom Fields Styles', 'wp-jobsearch'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __('Heading', 'wp-jobsearch'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .jobsearch-jobdetail-services .jobsearch-services-text',
            ]
        );

        $this->add_control(
            'heading_text_color',
            [
                'label' => __('Text Color', 'wp-jobsearch'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .jobsearch-jobdetail-services li .jobsearch-services-text' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} .jobsearch-jobdetail-services .jobsearch-services-text',
            ]
        );


            $this->add_control(
                'icon_color',
                [
                    'label' => __('Icon Color', 'wp-jobsearch'),
                    'type' => Controls_Manager::COLOR,
                    'default' => '',
                    'selectors' => [
                        '{{WRAPPER}} .jobsearch-jobdetail-services li i' => 'fill: {{VALUE}}; color: {{VALUE}};',
                    ],
                ]
            );


        $this->add_control(
            'icon_size',
            array(
                'label' => __('Icon Size', 'wp-jobsearch'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => array('em'),
                'range' => array(
                    'em' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .jobsearch-jobdetail-services li i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; font-size: calc( {{SIZE}}px / 2 );',
                ),
            )
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Description', 'wp-jobsearch'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_desc',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_ACCENT,
                ],
                'selector' => '{{WRAPPER}} .jobsearch-jobdetail-services .jobsearch-services-text small',
            ]
        );

        $this->add_control(
            'color_txt_desc',
            [
                'label' => __('Text Color', 'wp-jobsearch'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jobsearch-jobdetail-services .jobsearch-services-text small' => 'color: {{VALUE}}  ',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow_desc',
                'selector' => '{{WRAPPER}} .jobsearch-jobdetail-services .jobsearch-services-text small',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        global $post;
        $job_id = is_admin() ? jobsearch_job_id_elementor() : $post->ID;

        ob_start();
        $cus_fields = array('content' => '');
        $cus_fields = apply_filters('jobsearch_custom_fields_list', 'job', $job_id, $cus_fields, '<li class="jobsearch-column-4">', '</li>');
        if (isset($cus_fields['content']) && $cus_fields['content'] != '') {
            ?>
            <div class="jobsearch-jobdetail-services">
                <ul class="jobsearch-row">
                    <?php
                    echo force_balance_tags($cus_fields['content']);
                    ?>
                </ul>
            </div>
            <?php
        }
        $html = ob_get_clean();
        echo $html;
    }

    protected function content_template()
    {

    }

}
