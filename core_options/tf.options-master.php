<?php

/*
 * TF OPTIONS: MASTER FUNCTIONS
 * 
 */

// Register Menu Pages

require_once( TF_PATH . '/core_options/tf.of-uploader.php' );

// Register Pages
// -----------------------------------------

function themeforce_business_options() {
    add_menu_page( 'Dummy Header', 'Your Business', 'edit_posts', 'themeforce_business_options','', get_bloginfo('template_url').'/themeforce/assets/images/general_16.png', 25); // $function, $icon_url, $position 
    add_submenu_page('themeforce_business_options', 'Business Details', 'Business Details', 'edit_posts', 'themeforce_business', 'themeforce_business_page');
    add_submenu_page('themeforce_business_options', 'Logo', 'Logo', 'edit_posts', 'themeforce_logo', 'themeforce_logo_page');   
    add_submenu_page('themeforce_business_options', 'Your Location', 'Location', 'edit_posts', 'themeforce_location', 'themeforce_location_page');
}
add_action('admin_menu','themeforce_business_options');

function themeforce_social_options() {
    add_menu_page( 'Dummy Header', 'Social Proof', 'edit_posts', 'themeforce_social_options','themeforce_social_overview_page', get_bloginfo('template_url').'/themeforce/assets/images/social_16.png', 35); // $function, $icon_url, $position 
    add_submenu_page('themeforce_social_options', 'Yelp', 'Yelp', 'edit_posts', 'themeforce_yelp', 'themeforce_social_yelp_page');
    add_submenu_page('themeforce_social_options', 'Qype', 'Qype', 'edit_posts', 'themeforce_qype', 'themeforce_social_qype_page');
    add_submenu_page('themeforce_social_options', 'Foursquare', 'Foursquare', 'edit_posts', 'themeforce_foursquare', 'themeforce_social_foursquare_page');   
    add_submenu_page('themeforce_social_options', 'Gowalla', 'Gowalla', 'edit_posts', 'themeforce_gowalla', 'themeforce_social_gowalla_page');
}
add_action('admin_menu','themeforce_social_options');

// Load jQuery & relevant CSS
// -----------------------------------------

// js
function themeforce_business_options_scripts() {
    wp_enqueue_script( 'tfoptions', TF_URL . '/assets/js/themeforce-options.js', array('jquery'));
    wp_enqueue_script( 'iphone-checkbox', TF_URL . '/assets/js/jquery.iphone-style-checkboxes.js', array('jquery'));
    wp_enqueue_script( 'farbtastic', TF_URL . '/assets/js/jquery.farbtastic.js', array('jquery'));
    wp_enqueue_script( 'chosen', TF_URL . '/assets/js/jquery.chosen.min.js', array('jquery'));
}

add_action( 'admin_print_scripts', 'themeforce_business_options_scripts' );

// css
function themeforce_business_options_styles() {
    wp_enqueue_style( 'tfoptions', TF_URL . '/assets/css/themeforce-options.css');
    wp_enqueue_style( 'farbtastic', TF_URL . '/assets/css/farbtastic.css');
}

add_action( 'admin_print_styles', 'themeforce_business_options_styles' );

// Validation

function tf_settings_validate($input) {
    
        $newinput['text_string'] = trim($input['text_string']);
        if(!preg_match('/^[a-z0-9]{32}$/i', $newinput['text_string'])) {
        $newinput['text_string'] = '';
    }

return $newinput;
}

// Display Settings

function tf_display_settings($options) {

    foreach ($options as $value) {
        switch ( $value['type'] ) {

        case "title":
        ?>
        <h3><?php echo $value['name']; ?></h3>

        <?php break;    

        case "open":
        ?>

        <table>

        <?php break;

        case "close":
        ?>

        </table>

        <?php break;

        case 'text':
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td>
                <input name="<?php echo $value['id'] ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>">
                <br /><span class="desc"><?php echo $value['desc'] ?></span>
            </td>
        </tr>

        <?php
        break;

        case 'textarea':
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td><textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea>
            <br /><span class="desc"><?php echo $value['desc'] ?></span></td>
        </tr>

        <?php
        break;

        case 'select':
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td>
                <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                    <option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
                </select>
                <br /><span class="desc"><?php echo $value['desc'] ?></span>
            </td>
        </tr>
         <?php
        break;
        
        case 'multiple-select':
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td>
                <select name="<?php echo $value['id']; ?>" class="chzn-select" multiple id="<?php echo $value['id']; ?>">
                <?php foreach ($value['options'] as $option) { ?>
                    <option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
                </select>
                <br /><span class="desc"><?php echo $value['desc'] ?></span>
            </td>
        </tr>

        <?php
        
        
        break;

        case "checkbox":
        
        $std = $value['std'];     
            
        $saved_std = get_settings( $value['id'] );

        $checked = '';

        if(!empty($saved_std)) {
                if($saved_std == 'true') {
                $checked = 'checked="checked"';
                }
                else{
                   $checked = '';
                }
        }
        elseif( $std == 'true') {
           $checked = 'checked="checked"';
        }
        else {
                $checked = '';
        }
            
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td>
                <input type="checkbox" name="<?php echo $value['id']; ?>" class="iphone" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />
                <br /><span class="desc"><?php echo $value['desc'] ?></span>
            </td>
        </tr>

        <?php break;

        case "radio":
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td>
            <?php foreach ($value['options'] as $option) { ?>
                <div><input type="radio" name="<?php echo $value['id']; ?>" <?php if (get_settings( $value['id'] ) == $option) { echo 'checked'; } ?> /><?php echo $option; ?></div><?php } ?>
                <br /><span class="desc"><?php echo $value['desc'] ?></span>
            </td>
        </tr>
        
        <?php break;
        
        case "image":
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td>
            <?php
            if ( get_settings( $value['id'] ) != "") { $val = stripslashes(get_settings( $value['id'])  ); } else { $val =  $value['std']; }
            ?>
            <?php echo tf_optionsframework_medialibrary_uploader( $value['id'], $val ); ?>
            </td>
        </tr>
        
        <?php break;
        
        case "open-farbtastic":
        ?>
        
        </table>
        <div style="clear:both;"></div>
        <div class="tf-settings-wrap tf-farbtastic">
        <div id="farbtastic-picker"><div id="picker-bg"></div></div>
        <table> 
        
        
        <?php break;
        
        case "colorpicker":
        ?>

        <tr>
            <th><label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label></th>
            <td>
            <input class="colorwell" name="<?php echo $value['id'] ?>" type="text" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>">
            </td>
        </tr>
        
         <?php break;
        
        case "close-farbtastic":
        ?>
            
        </table>
        </div>
        <div style="clear:both;"></div>
        <table>      
        
        <?php break;
        }
	}
	
	foreach( $options as $option ) 
		if( !empty( $option['id'] ) )
			$option_ids[] = $option['id'];
	
	?>
	
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="<?php echo implode(',', $option_ids) ?>" />
    <?php wp_nonce_field( 'update-options' ); ?>
	    
    <?php
}

?>