<?php
/**
 * Define all custom fields in menu
 *
 * @version: 1.0.0
 * @package  Kite/Template
 * @author   KiteThemes
 * @link	 http://kitethemes.com
 */

// Exit if accessed directly
if ( !defined('ABSPATH')) exit;

add_action( 'walker_nav_menu_custom_fields', 'kt_add_custom_fields', 10, 4 );
function kt_add_custom_fields( $item_id, $item, $depth, $args ) { ?>
    <div class="clearfix"></div>
    <div class="container-megamenu">
        <p class="field-icon description description-wide clearfix">
            <label for="menu-item-icon-<?php echo $item_id; ?>">
                <?php _e( 'Select icon of this item (set empty to hide). Ex: fa fa-home', 'wingman'); ?><br />
                <input type="text" id="edit-menu-item-icon-<?php echo $item_id; ?>" class="widefat edit-menu-item-icon" name="menu-item-megamenu-icon[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->icon ); ?>" />
            </label>
        </p>
        <div class="wrapper-megamenu">
            <p class="field-enable description description-wide">
                <label for="menu-item-enable-<?php echo $item_id; ?>">
                    <input type="checkbox" <?php checked($item->enable, 'enabled'); ?> data-id="<?php echo $item_id; ?>" id="menu-item-enable-<?php echo $item_id; ?>" name="menu-item-megamenu-enable[<?php echo $item_id; ?>]" value="enabled" class="edit-menu-item-enable"/>
                    <b><?php _e( 'Enable Mega Menu (only for main menu)', 'wingman'); ?></b>
                </label>
            </p>
            <div id="content-megamenu-<?php echo $item_id; ?>" class="megamenu-layout clearfix">
                <div class="megamenu-layout-depth-0">
                    <p class="field-columns description description-wide">
                        <label for="menu-item-columns-<?php echo $item_id; ?>">
                            <?php _e( 'Mega Menu number of columns', 'wingman'); ?><br />
                            <select id="menu-item-columns-<?php echo $item_id; ?>" name="menu-item-megamenu-columns[<?php echo $item_id; ?>]" class="widefat edit-menu-item-columns">
                                <?php for($i=4; $i>1; $i--){ ?>
                                    <option <?php selected($item->columns, $i); ?> value="<?php echo $i ?>"><?php echo $i; ?></option>    
                                <?php } ?>
                            </select>
                        </label>
                    </p>
                    <p class="field-layout description description-wide">
                        <label for="menu-item-layout-<?php echo $item_id; ?>">
                            <?php _e( 'Mega Menu layout', 'wingman'); ?><br />
                            <select id="menu-item-layout-<?php echo $item_id; ?>" name="menu-item-megamenu-layout[<?php echo $item_id; ?>]" class="widefat edit-menu-item-layout">
                                <option <?php selected($item->layout, 'default'); ?> value="default"><?php _e('Default', 'wingman'); ?></option>
                                <option <?php selected($item->layout, 'table'); ?> value="table"><?php _e('Table + Border', 'wingman'); ?></option>
                            </select>
                        </label>
                    </p>
                    <p class="field-width description description-wide">
                        <label for="menu-item-width-<?php echo $item_id; ?>">
                            <?php _e( 'Mega Menu width', 'wingman'); ?><br />
                            <select id="menu-item-width-<?php echo $item_id; ?>" name="menu-item-megamenu-width[<?php echo $item_id; ?>]" class="widefat edit-menu-item-width">
                                <option <?php selected($item->width, 'full'); ?> value="full"><?php _e('Full Width', 'wingman'); ?></option>
                                <option <?php selected($item->width, 'half'); ?> value="half"><?php _e('1/2', 'wingman'); ?></option>
                                <option <?php selected($item->width, 'three'); ?> value="three"><?php _e('3/4', 'wingman'); ?></option>
                                <option <?php selected($item->width, 'four'); ?> value="four"><?php _e('4/5', 'wingman'); ?></option>
                                <option <?php selected($item->width, 'five'); ?> value="five"><?php _e('9/10', 'wingman'); ?></option>
                            </select>
                        </label>
                    </p>
                    <p class="description description-position description-wide">
                        <label>
                            <?php _e( 'Mega menu position', 'wingman'); ?><br />
                            <select id="menu-item-position-<?php echo $item_id; ?>" name="menu-item-megamenu-position[<?php echo $item_id; ?>]" class="widefat edit-menu-item-position">
                                <option <?php selected($item->position, 'center'); ?> value="center">Center</option>
                                <option <?php selected($item->position, 'left-menubar'); ?> value=""><?php _e( 'Left edge of menu bar', 'wingman'); ?></option>
                                <option <?php selected($item->position, 'right-menubar'); ?> value="right-menubar"><?php _e( 'Right edge of menu bar', 'wingman'); ?></option>
                                <option <?php selected($item->position, 'left-parent'); ?> value="left-parent"><?php _e( 'Left Edge of Parent item', 'wingman'); ?></option>
                                <option <?php selected($item->position, 'right-parent'); ?> value="right-parent"><?php _e( 'Right Edge of Parent item', 'wingman'); ?></option>
                            </select>
                        </label>
                    </p>
                </div>
                <div class="megamenu-layout-depth-1">
                    <p class="field-image description description-wide">
                        <?php
                            $preview = false;
                            $img_preview = "";
                            if($item->image){
                                $file = get_thumbnail_attachment($item->image, 'full');
                                $preview = true;
                                $img_preview = $file['src'];
                            }
                        ?>
                        <label for="menu-item-image-<?php echo $item_id; ?>">
                            <?php _e( 'Menu image', 'wingman'); ?><br />
                            <input type="hidden" value="<?php echo esc_attr( $item->image ); ?>" name="menu-item-megamenu-image[<?php echo $item_id; ?>]" id="menu-item-image-<?php echo $item_id; ?>" class="widefat edit-menu-item-image" />
                        </label>
                        <span class="clearfix"></span>
                        <span class="kt_image_preview" style="<?php if($preview){ echo "display: block;";} ?>">
                            <img src="<?php echo esc_url($img_preview); ?>" alt="" title="" />
                            <i class="fa fa-times"></i>
                        </span>
                        <span class="clearfix"></span>
                        <input type="button" class="button-secondary kt_image_menu" value="<?php _e('Upload image', 'wingman'); ?>" style="width: 100%;" />
                    </p>
                    <p class="field-clwidth description description-wide">
                        <label for="menu-item-clwidth-<?php echo $item_id; ?>">
                            <?php _e( 'Mega Menu Column Width - Overrides parent colum (in percentage, ex: 30%)', 'wingman'); ?><br />
                            <input type="text" value="<?php echo esc_attr($item->clwidth); ?>" id="menu-item-clwidth-<?php echo $item_id; ?>" name="menu-item-megamenu-clwidth[<?php echo $item_id; ?>]" class="widefat edit-menu-item-clwidth"/>
                        </label>
                    </p>
                    <p class="field-columntitle description description-wide">
                        <label for="menu-item-columntitle-<?php echo $item_id; ?>">
                            <input type="checkbox" <?php checked($item->columntitle, 'enabled'); ?> id="menu-item-columntitle-<?php echo $item_id; ?>" name="menu-item-megamenu-columntitle[<?php echo $item_id; ?>]" value="enabled"/>
                            <?php _e('Disable Mega Menu Column Title', 'wingman'); ?>
                        </label>
                    </p>
                    <p class="field-columnlink description description-wide">
                        <label for="menu-item-columnlink-<?php echo $item_id; ?>">
                            <input type="checkbox" <?php checked($item->columnlink, 'enabled'); ?> id="menu-item-columnlink-<?php echo $item_id; ?>" name="menu-item-megamenu-columnlink[<?php echo $item_id; ?>]" value="enabled"/>
                            <?php _e('Disable Mega Menu Column Link', 'wingman'); ?>
                        </label>
                    </p>
                    <p class="field-endrow description description-wide">
                        <label for="menu-item-endrow-<?php echo $item_id; ?>">
                            <input type="checkbox" <?php checked($item->endrow, 'enabled'); ?> id="menu-item-endrow-<?php echo $item_id; ?>" name="menu-item-megamenu-endrow[<?php echo $item_id; ?>]" value="enabled" class="edit-menu-item-endrow"/>
                            <?php _e( 'End Row (Clear the next row and start a new one with next item)', 'wingman'); ?>
                        </label>
                    </p>
                    <p class="field-widget description description-wide">
                        <label for="menu-item-widget-<?php echo $item_id; ?>">
                            <?php _e('Mega Menu Widget Area', 'wingman'); ?><br />
                            <?php $sidebars = kt_sidebars();?>
                            <select id="menu-item-widget-<?php echo $item_id; ?>" name="menu-item-megamenu-widget[<?php echo $item_id; ?>]" class="widefat edit-menu-item-widget">
                                <option value="0"><?php _e( 'Select Widget Area', 'wingman'); ?></option>
                                <?php foreach($sidebars as $k=>$v){ ?>
                                    <option <?php selected($item->widget, $k); ?> value="<?php echo $k; ?>"><?php echo $v ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </p>
                </div>
            </div><!-- #content-megamenu-<?php echo $item_id; ?> -->
        </div><!-- .wrapper-megamenu -->
    </div><!-- .container-megamenu -->
<?php }
