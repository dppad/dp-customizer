<?php

require_once('section.php');
require_once('setting.php');

class Customizer {
    private $defaults = array(
        array(
            'sectionName' => 'Footer',
            'setting' => 'footer-image',
            'label' => 'This is the label',
            'type' => 'image',
            'default' => 'http://placehold.it/640x480'
        )
    );
    private $sections = array();
    private $settings = array();

    function __construct($text_context, $default_fields) {
        $this->textContext = $text_context;
        if(is_array($default_fields)){
            $this->defaults = $default_fields;
        }
        $this->initialize();
        add_action('customize_register', array($this, 'onCustomizeRegister'));
    }

    private function initialize() {
        foreach ($this->defaults as $field_meta) {
            $this->addSection($field_meta['sectionName']);
            $this->addSetting($field_meta['setting'], $field_meta['sectionName'], $field_meta['type'], $field_meta['label'], $field_meta['default']);
        }
    }

    function addSection($section_name) {
        if(!isset($this->sections[$section_name])) {
            $this->sections[$section_name] = new Section($section_name, $this->textContext);
        }
    }

    function addSetting($setting_name, $section_name = 'Default', $type='image', $label = 'Default', $default = '') {
        $this->sections[$section_name]->addSetting($setting_name, $type, $label, $default);
    }

    function onCustomizeRegister($wp_customize) {
        //All our sections, settings, and controls will be added here

        foreach ($this->sections as $section) {
            $wp_customize->add_section($section->buildLabel(), $section->buildArgs());

            foreach ($section->settings as $setting) {
                $setting_label = $setting->buildLabel();
                $setting_args = $setting->buildArgs();
                $wp_customize->add_setting($setting_label, $setting_args);
                if((get_theme_mod($setting_label, true) === true) && isset($setting_args['default'])){
                    set_theme_mod( $setting_label, $setting_args['default'] );
                }
            }

            foreach ($section->settings as $setting) {

                switch ($setting->type) {
                    case 'image':
                        $wp_customize->add_control(
                            new WP_Customize_Image_Control(
                                $wp_customize,
                                $setting->buildLabel(),
                                $setting->buildControlArgs()
                            )
                        );
                        break;
                    case 'text':

                        $wp_customize->add_control(
                            new WP_Customize_Control(
                                $wp_customize,
                                $setting->buildLabel(),
                                $setting->buildControlArgs()
                            )
                        );
                        break;
                    default:
                        break;
                };
            }
        }

        return $wp_customize;
    }
}