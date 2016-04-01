<?php

/**
 * Created by IntelliJ IDEA.
 * User: dpdev
 * Date: 2/24/16
 * Time: 9:46 AM
 */
class Setting {
    /**
     * Setting constructor.
     *
     * @param string $setting_name
     * @param Section $section
     * @param string $type
     * @param string $label
     * @param string $default
     * @param array $options
     */
    public function __construct($setting_name = "DefaultName", $section, $type, $label, $default = 'https://placehold.it/1920x1080', $options = array()) {
        $this->name = $setting_name;
        $this->slug = strtolower($setting_name);
        $this->label = $label;
        $this->type = $type;
        $this->default = $default;
        $this->text_context = $section->text_context;
        $this->section = $section;
        if(isset($options['image_size'])){
            $this->image_size = $options['image_size'];
        }
    }

    public function buildLabel() {
        return $this->slug;
    }

    public function buildArgs() {
        return array(
            'default' => $this->default,
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options'
        );
    }

    public function buildControlArgs() {

        return array(
            'label' => __($this->label, $this->text_context),
            'section' => $this->section->slug,
            'settings' =>  $this->slug
        );
    }

}