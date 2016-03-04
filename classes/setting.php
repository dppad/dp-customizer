<?php

/**
 * Created by IntelliJ IDEA.
 * User: dpdev
 * Date: 2/24/16
 * Time: 9:46 AM
 */
class Setting {

    public function __construct($setting_name = "DefaultName", $section, $type, $label, $default = 'https://placehold.it/1920x1080') {
        $this->name = $setting_name;
        $this->slug = strtolower($setting_name);
        $this->label = $label;
        $this->type = $type;
        $this->default = $default;
        $this->text_context = $section->text_context;
        $this->section = $section;
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