<?php

/**
 * Created by IntelliJ IDEA.
 * User: dpdev
 * Date: 2/24/16
 * Time: 9:46 AM
 */
class Section {
    public $settings = array();
    public $name;
    public $text_context = null;
    public $slug;

    public function __construct($section_name = "Default", $text_context = null) {
        $this->name = $section_name;
        $this->slug = strtolower($section_name);
        $this->text_context = $text_context;
    }

    function addSetting($setting_name, $type, $label, $default) {
        array_push($this->settings, new Setting($setting_name, $this, $type, $label, $default));
    }

    public function buildLabel() {
        return $this->slug;
    }

    public function buildArgs() {
        return array(
            'title' => __($this->name, $this->text_context)
        );
    }
}