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
if ( !class_exists( 'ReduxFramework_kt_socials' ) ) {

    /**
     * Main ReduxFramework_kt_socials class
     *
     * @since       1.0.0
     */
    class ReduxFramework_kt_socials {

        /**
         * Field Constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

        }

        /**
         * Field Render Function.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {
            $socials = array(
                'facebook' => 'fa fa-facebook',
                'twitter' => 'fa fa-twitter',
                'pinterest' => 'fa fa-pinterest-p',
                'dribbble' => 'fa fa-dribbble',
                'vimeo' => 'fa fa-vimeo-square',
                'tumblr' => 'fa fa-tumblr',
                'skype' => 'fa fa-skype',
                'linkedin' => 'fa fa-linkedin',
                'googleplus' => 'fa fa-google-plus',
                'youtube' => 'fa fa-youtube-play',
                'instagram' => 'fa fa-instagram'
            );
            $arr_val = ($this->value) ? explode(',', $this->value) : array();
            
            echo '<div class="kt-socials-options">';
                echo '<ul class="kt-socials-lists clearfix">';
                    foreach($socials as $key => $social){
                        $class = (in_array($key, $arr_val)) ? 'selected' : '';
                        printf('<li data-type="%s" class="%s"><i class="%s"></i><span></span></li>', $key, $class, $social);
                    }
                echo "</ul>";
                echo '<ul class="kt-socials-profiles clearfix">';
                    
                    if(count($arr_val)){
                        foreach($arr_val as $item){
                            printf('<li data-type="%s"><i class="%s"></i><span></span></li>', $item,  $socials[$item]);
                        }
                    }
                echo "</ul>";
                
                echo '<input type="hidden" class="kt-socials-value" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="'.$this->value.'">';
                
            echo '</div>';

        }


        /**
         * Enqueue Function.
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since ReduxFramework 1.0.0
         */
        function enqueue() {
            wp_enqueue_script(
                'redux-field-socials-js',
                KT_FW_JS.'kt_socials.js',
                time(),
                true
            );
        } //function
    }
}
