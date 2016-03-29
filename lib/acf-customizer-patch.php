<?php
/*
Plugin Name: ACF Customizer Patch
Plugin URI: https://gist.github.com/fabrizim/9c0f36365f20705f7f73
Description: A class to allow acf widget fields to be stored with normal widget settings and allow for use in customizer.
Author: Mark Fabrizio
Version: 1.0
Author URI: http://owlwatch.com/
*/
class acf_customizer_patch
{
  
  protected $_capture_options = false;
  protected $_captured_options = array();
  protected $_instance;
  
  public function __construct()
  {
    add_filter('widget_display_callback', array($this, 'before_widget_display'), 20, 3);
    add_filter('in_widget_form',          array($this, 'before_edit_form'),       9, 3);
    add_filter('widget_update_callback',  array($this, 'before_save_acf_fields'), 9, 3);
    add_filter('pre_update_option',       array($this, 'pre_update_option'),      8, 3);
    add_filter('widget_update_callback',  array($this, 'after_save_acf_fields'), 11, 3);
    
    // need to include the acf input scripts
    // This could be included in the 'acf_input_listener' class
    add_action('customize_controls_print_footer_scripts', array( $this, 'admin_footer'), 20 );
  }
  
  public function admin_footer() {
    do_action('acf/input/admin_footer');
  }
  
  /**
   * There may be a better way to find out if we are in the customizer,
   * but this works for now. If someone has a better way, let me know.
   */
  protected function in_customizer()
  {
    return @$_REQUEST['wp_customize'] == 'on' || basename($_SERVER['SCRIPT_NAME']) == 'customize.php';
  }
  
  /**
   * @wp.filter       widget_display_callback
   * @wp.priority     20
   */
  public function before_widget_display( $instance, $widget, $args )
  {
    $this->prepare_widget_options( $widget, $instance );
    return $instance;
  }
  
  /**
   * @wp.filter       in_widget_form
   * @wp.priority     9
   */
  public function before_edit_form( $widget, $return, $instance )
  {
    $this->prepare_widget_options( $widget, $instance );
    return $widget;
  }
  
  public function prepare_widget_options( $widget, $instance )
  {
    $this->_instance = $instance;
    $field_groups = acf_get_field_groups(array(
      'widget'  => $widget->id_base
    ));
    
    if( count( $field_groups ) ) foreach( $field_groups as $group  ){
      $fields = acf_get_fields( $group );
      if( count($fields) ) foreach( $fields as $field ){
        $name = $field['name'];
        add_filter("pre_option_widget_{$widget->id}_{$name}", array($this, 'pre_get_option') );
      }
    }
    return $widget;
  }
  
  public function pre_get_option( $value )
  {
    $filter = current_filter();
    $name = str_replace('pre_option_widget_', '', $filter);
    preg_match('/(.+\-\d+?)_(.+)/', $name, $matches );
    
    list( $full, $widget_id, $var ) = $matches;
    
    if( $this->_instance && isset( $this->_instance[$var] ) ) return $this->_instance[$var];
    return $value;
  }
  
  /**
   * @wp.filter       widget_update_callback
   * @wp.priority     9
   */
  public function before_save_acf_fields( $instance, $new_instance, $old_instance )
  {
    global $wp_customize;
    $this->_capture_options = true;
    if( $this->in_customizer() )
      remove_filter( 'pre_update_option', array( $wp_customize->widgets, 'capture_filter_pre_update_option' ), 10);
    return $instance;
  }
  
  /**
   * @wp.filter       pre_update_option
   * @wp.priority     8
   */
  public function pre_update_option( $value, $option, $old_value )
  {
    global $wp_customize;
    
    if( !$this->_capture_options ) return $value;
    
    if( preg_match('/^([^_].+\-\d+?)_(.+)/', $option, $matches ) ){
      $this->_captured_options[$matches[2]] = $value;
    }
    // if this is not an acf field, this should be the actual
    // widget option (which needs to be captured by the customizer)
    if( !preg_match('/^(.+\-\d+?)_(.+)/', $option ) && $this->in_customizer() ){
      $wp_customize->widgets->capture_filter_pre_update_option( $value, $option, $old_value );
    }
    
     return $this->in_customizer() ? $old_value : $value;
  }
  
  /**
   * @wp.filter       widget_update_callback
   * @wp.priority     11
   */
  public function after_save_acf_fields( $instance, $new_instance, $old_instance )
  {
    $instance = array_merge( $instance, $this->_captured_options );
    $this->_capture_options = false;
    return $instance;
  }
  
}

$acf_customizer_patch = new acf_customizer_patch();