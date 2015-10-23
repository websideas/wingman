<?php
/**
 * Extension socials
 *
 *
 * KT socials - Modified For ReduxFramework
 *
 * @package     KT_Socials
 * @author      Cuongdv
 * @version     1.0
 */
 
 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_extension_kt_socials' ) ) {

    class ReduxFramework_extension_kt_socials {
        
        public static $instance;
        
        public $extension_dir;

        static $version = "1.0";

        protected $parent;
        
        public $extension_url;
        
        /**
         * Class Constructor
         *
         * @since       1.0
         * @access      public
         * @return      void
         */
        public function __construct( $parent ) {
            $this->parent = $parent;

            if ( !is_admin() ) return;


            $this->field_name = 'kt_socials';

            add_filter( 'redux/' . $this->parent->args['opt_name'] . '/field/class/' . $this->field_name, array( &$this,
                    'overload_field_path'
                ) );


        }
        
        public static function get_instance() {
            return self::$instance;
        }

        // Forces the use of the embeded field path vs what the core typically would use
        public function overload_field_path( $field ) {
            return dirname( __FILE__ ) . '/' . $this->field_name . '/field_' . $this->field_name . '.php';
        }
        
    }
}