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
if ( !class_exists( 'ReduxFramework_kt_imagesizes' ) ) {

    /**
     * Main ReduxFramework_kt_socials class
     *
     * @since       1.0.0
     */
    class ReduxFramework_kt_imagesizes {

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
            echo '<input type="text" class="kt-imagesizes-value" name="' . $this->field['name'] . $this->field['name_suffix'] . '" value="'.$this->value.'">';

        }

    }
}
