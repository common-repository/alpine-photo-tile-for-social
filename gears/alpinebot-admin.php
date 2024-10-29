<?php

/**
 *  AlpineBot Secondary
 *
 *  ADMIN Functions
 *  Contains ONLY UNIVERSAL ADMIN functions
 *
 */
class PhotoTileForInstagramAdminSecondary extends PhotoTileForInstagramPrimary
{

    //////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////      Update Functions       ////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * Options Simple Update for Admin Page
     *
     * @since 1.2.0
     *
     */
    public function admin_simple_update($currenttab, $newoptions, $oldoptions)
    {
        $options = $this->option_defaults();
        $bytab = $this->admin_get_options_by_tab($currenttab);
        // var_dump($newoptions);
        foreach ($bytab as $id) {
            $new = (isset($newoptions[$id]) ? $newoptions[$id] : '');
            $old = (isset($oldoptions[$id]) ? $oldoptions[$id] : '');
            $opt = (isset($options[$id]) ? $options[$id] : '');
            $oldoptions[$id] = $this->MenuOptionsValidate($new, $old, $opt); // Make changes to existing options array
        }

        update_option($this->get_private('settings'), $oldoptions);
        return $oldoptions;
    }

    //////////////////////////////////////////////////////////////////////////////////////
    //////////////////////      Admin Option Functions      //////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     *  Create array of option names for a given tab
     *
     *  @ Since 1.2.0
     */
    public function admin_get_options_by_tab($tab = 'generator')
    {
        $default_options = $this->option_defaults();
        $return = array();
        foreach ($default_options as $key => $val) {
            if ($val['tab'] == $tab) {
                $return[$key] = $key;
            }
        }
        return $return;
    }
    /**
     *  Create array of option names and current values for a given tab
     *
     *  @ Since 1.2.0
     */
    public function admin_get_settings_by_tab($tab = 'generator')
    {
        $current = $this->get_all_options();
        $default_options = $this->option_defaults();
        $return = array();
        foreach ($default_options as $key => $val) {
            if ($val['tab'] == $tab) {
                $return[$key] = $current[$key];
            }
        }
        return $return;
    }
    /**
     *  Create array of positions for a given tab along with a list of settings for each position
     *
     *  @ Since 1.2.0
     *  @ Updated 1.2.3
     */
    public function get_option_positions_by_tab($tab = 'generator')
    {
        $positions = $this->admin_option_positions();
        $return = array();
        if (isset($positions[$tab])) {
            $options = $this->admin_get_options_by_tab($tab);
            $defaults = $this->option_defaults();

            foreach ($positions[$tab] as $pos => $info) {
                $return[$pos]['title'] = (isset($info['title']) ? $info['title'] : '');
                $return[$pos]['description'] = (isset($info['description']) ? $info['description'] : '');
                $return[$pos]['options'] = array();
            }
            foreach ($options as $name) {
                $pos = (isset($defaults[$name]['position']) ? $defaults[$name]['position'] : 'none');
                $return[$pos]['options'][] = $name;
            }
        }
        return $return;
    }
    /**
     *  Create array of positions for each widget along with a list of settings for each position
     *
     *  @ Since 1.2.0
     */
    public function admin_get_widget_options_by_position()
    {
        $default_options = $this->option_defaults();
        $positions = $this->admin_widget_positions();
        $return = array();
        foreach ($positions as $key => $val) {
            $return[$key]['title'] = $val;
            $return[$key]['options'] = array();
        }
        foreach ($default_options as $key => $val) {
            if (!empty($val['widget'])) {
                $return[$val['position']]['options'][] = $key;
            }
        }
        return $return;
    }

    //////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////      Admin Page Functions       //////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Create shortcode based on given options
     *
     * @ Since 1.1.0
     * @ Update 1.2.5
     */
    public function admin_generate_shortcode($options, $optiondetails)
    {
        $short = '[' . $this->get_private('short');
        $id = rand(100, 1000);
        $short .= ' id=' . $id;
        $trigger = '';
        foreach ($options as $key => $value) {
            if ($value && isset($optiondetails[$key]['short'])) {
                if (isset($optiondetails[$key]['child']) && isset($optiondetails[$key]['hidden'])) {
                    $hidden = @explode(' ', $optiondetails[$key]['hidden']);
                    if (!in_array($options[$optiondetails[$key]['child']], $hidden)) {
                        $short .= ' ' . $optiondetails[$key]['short'] . '="' . $value . '"';
                    }
                } else {
                    $short .= ' ' . $optiondetails[$key]['short'] . '="' . $value . '"';
                }
            }
        }
        $short .= ']';

        return $short;
    }
    /**
     * Define Settings Page Tab Markup
     *
     * @since 1.1.0
     * @link`http://www.onedesigns.com/tutorials/separate-multiple-theme-options-pages-using-tabs    Daniel Tara
     *
     */
    public function admin_options_page_tabs($current = 'general')
    {
        $tabs = $this->admin_settings_page_tabs();
        $links = array();

        foreach ($tabs as $tab) {
            $tabname = $tab['name'];
            $tabtitle = $tab['title'];
            if ($tabname == $current) {
                $links[] = '<a class="nav-tab nav-tab-active" href="?page=' . $this->get_private('settings') . '&tab=' . $tabname . '">' . $tabtitle . '</a>';
            } else {
                $links[] = '<a class="nav-tab" href="?page=' . $this->get_private('settings') . '&tab=' . $tabname . '">' . $tabtitle . '</a>';
            }
        }

        echo '<div class="AlpinePhotoTiles-title"><div class="icon32 icon-alpine"><br></div><h2>' . $this->get_private('name') . '</h2></div>';
        echo '<div class="AlpinePhotoTiles-menu"><h2 class="nav-tab-wrapper">';
        foreach ($links as $link) {
            echo $link;
        }
        echo '</h2></div>';
    }
    /**
     * Function for printing general settings page
     *
     * @ Since 1.2.0
     * @ Updated 1.2.4
     */
    public function admin_display_general()
    {

        ?>
    <h3><?php _e("Thank you for downloading the ");
        echo $this->get_private('name');
        _e(", <br>a WordPress plugin by the Alpine Press.");?></h3>
    <?php if ($this->check_private('termsofservice')) {
            echo '<p>' . $this->get_private('termsofservice') . '</p>';
        }?>
    <p><?php _e("On the 'Shortcode Generator' tab you will find an easy to use interface that will help you create shortcodes. These shortcodes make it simple to insert the PhotoTile plugin into posts and pages.");?></p>
    <p><?php _e("The 'Plugin Settings' tab provides additional back-end options.");?></p>
    <p><?php _e("The 'Plugin Tools' tab allows you to check for required PHP extensions on your server and to test the performance of the plugin (with step-by-step timestamps and messages).");?></p>
    <p><?php _e("Finally, I am a one man programming team and so if you notice any errors or places for improvement, please let me know.");?></p>
    <p><?php _e('If you liked this plugin, try out some of the other plugins by ')?><a href="http://thealpinepress.com/" target="_blank">the Alpine Press</a>.</p>
    <br>
    <h3><?php _e('Try the other free plugins in the Alpine PhotoTile Series:');?></h3>
    <?php
if ($this->check_private('plugins') && is_array($this->get_private('plugins'))) {
            foreach ($this->get_private('plugins') as $each) {
                ?><a href="http://wordpress.org/extend/plugins/alpine-photo-tile-for-<?php echo $each; ?>/" target="_blank"><img class="image-icon" src="<?php echo $this->get_private('url'); ?>/css/images/for-<?php echo $each; ?>.png" style="width:100px;"></a><?php
}
        }?>
    <?php
}
    /**
     * First function for printing options page
     *
     * @ Since 1.1.0
     * @ Updated 1.2.7
     *
     */
    public function admin_setup_options_form($currenttab)
    {
        $options = $this->get_all_options();
        $settings_section = $this->get_private('id') . '_' . $currenttab . '_tab';
        $submitted = ((isset($_POST["hidden"]) && ($_POST["hidden"] == "Y")) ? true : false);

        if ($submitted) {
            $options = $this->admin_simple_update($currenttab, $_POST, $options);
        }

        $buttom = (isset($_POST[$this->get_private('settings') . '_' . $currenttab]['submit-' . $currenttab]) ? $_POST[$this->get_private('settings') . '_' . $currenttab]['submit-' . $currenttab] : '');
        if ($buttom == 'Delete Current Cache') {
            //
            // DELETE CACHE BUTTON HAS BEEN DISABLED ON USER-SIDE
            //
            wp_verify_nonce($currenttab);
            $bot = new PhotoTileForInstagramBot();
            $bot->clearAllCache();
            echo '<div class="announcement">' . __("Cache Cleared") . '</div>';
        } elseif ($buttom == 'Save Settings') {
            wp_verify_nonce($currenttab);
            $bot = new PhotoTileForInstagramBot();
            $bot->clearAllCache();
            echo '<div class="announcement">' . __("Settings Saved") . '</div>';
        }
        echo '<form action="" method="post">';
        echo '<input type="hidden" name="hidden" value="Y">';
        wp_nonce_field($currenttab);
        $this->admin_display_opt_form($options, $currenttab);
        echo '<div class="AlpinePhotoTiles-breakline"></div>';
        echo '</form>';
    }
    /**
     * Second function for printing options page
     *
     * @ Since 1.1.0
     * @ Updated 1.2.7
     *
     */
    public function admin_display_opt_form($options, $currenttab)
    {

        $defaults = $this->option_defaults();
        $positions = $this->get_option_positions_by_tab($currenttab);
        $submitted = ((isset($_POST["hidden"]) && ($_POST["hidden"] == "Y")) ? true : false);

        if ('generator' == $currenttab) {
            $preview = (isset($_POST[$this->get_private('settings') . '_preview']['submit-preview']) && $_POST[$this->get_private('settings') . '_preview']['submit-preview'] == 'Preview') ? true : false;
            if ($submitted && isset($_POST['shortcode']) && $preview) {
                $sub_shortcode = sanitize_text_field($_POST['shortcode']);
                $short = str_replace('\"', '"', $sub_shortcode);
            } elseif ($submitted) {
                // Use filtered $options, not unflitered $_POST
                //var_dump($options);
                $short = $this->admin_generate_shortcode($options, $defaults);
            }
            ?>
      <div>
        <h3>This tool allows you to create shortcodes for the Alpine PhotoTile plugin.</h3>
        <p>A shortcode is a line of text that tells WordPress how to load a plugin inside the content of a page or post. Rather than explaining how to put together a shortcode, this tool will create the shortcode for you.</p>
      </div>
      <?php
if (!empty($short)) {
                ?>
        <div id="<?php echo $this->get_private('settings'); ?>-shortcode" style="position:relative;clear:both;margin-bottom:20px;">
          <div class="announcement" style="margin:0 0 10px 0;">
            Now, copy (Crtl+C) and paste (Crtl+V) the following shortcode into a page or post. <br>Or preview using the button below.</div>
          <div class="AlpinePhotoTiles-preview" style="border-bottom: 1px solid #DDDDDD;">
            <input type="hidden" name="hidden" value="Y">
            <textarea id="shortcode" class="auto_select" name="shortcode" style="margin-bottom:20px;"><?php echo $short; ?></textarea>
            <input name="<?php echo $this->get_private('settings'); ?>_preview[submit-preview]" type="submit" class="button-primary" value="Preview" />
            <br style="clear:both">
          </div>
        </div>
      <?php

                if ($submitted && isset($_POST['shortcode']) && $preview) {
                    echo '<div style="border-bottom: 1px solid #DDDDDD;padding-bottom:10px;margin-bottom:40px;">';
                    echo do_shortcode($short);
                    echo '</div>';
                }
            }
            echo '<input name="' . $this->get_private('settings') . '_' . $currenttab . '[submit-' . $currenttab . ']" type="submit" class="button-primary topbutton" value="Generate Shortcode" /><br> ';
        }
        if (!empty($positions) && count($positions)) {
            foreach ($positions as $position => $positionsinfo) {
                echo '<div class="' . $position . '">';
                if (!empty($positionsinfo['title'])) {
                    echo '<h4>' . $positionsinfo['title'] . '</h4>';
                }
                if (!empty($positionsinfo['description'])) {
                    echo '<div style="margin-bottom:15px;"><span class="describe" >' . $positionsinfo['description'] . '</span></div>';
                }
                echo '<table class="form-table">';
                echo '<tbody>';
                if (!empty($positionsinfo['options']) && count($positionsinfo['options'])) {
                    foreach ($positionsinfo['options'] as $optionname) {
                        $option = $defaults[$optionname];
                        $fieldname = ($option['name']);
                        $fieldid = ($option['name']);

                        if (!empty($option['hidden-option']) && !empty($option['check'])) {
                            $show = $this->get_option($option['check']);
                            if (!$show) {
                                continue;
                            }
                        }

                        if (isset($option['parent'])) {
                            $class = $option['parent'];
                        } elseif (isset($option['child'])) {
                            $class = ($option['child']);
                        } else {
                            $class = ('unlinked');
                        }
                        $trigger = (isset($option['trigger']) ? ('data-trigger="' . (($option['trigger'])) . '"') : '');
                        $hidden = (isset($option['hidden']) ? ' ' . $option['hidden'] : '');

                        if ('generator' == $currenttab) {
                            echo '<tr valign="top"> <td class="' . $class . ' ' . $hidden . '" ' . $trigger . '>';
                            $this->MenuDisplayCallback($options, $option, $fieldname, $fieldid);
                            echo '</td></tr>';
                        } else {
                            echo '<tr valign="top"><td class="' . $class . ' ' . $hidden . '" ' . $trigger . '>';
                            $this->AdminDisplayCallback($options, $option, $fieldname, $fieldid);
                            echo '</td></tr>';
                        }
                    }
                }
                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            }
        }

        if ('generator' == $currenttab) {
            echo '<input name="' . $this->get_private('settings') . '_' . $currenttab . '[submit-' . $currenttab . ']" type="submit" class="button-primary" value="Generate Shortcode" />';
        } elseif ('plugin-settings' == $currenttab) {
            echo '<input name="' . $this->get_private('settings') . '_' . $currenttab . '[submit-' . $currenttab . ']" type="submit" class="button-primary" value="Save Settings" />';
            //echo '<input name="'.$this->get_private('settings').'_'.$currenttab .'[submit-'. $currenttab.']" type="submit" class="button-primary" style="margin-top:15px;" value="Delete Current Cache" />';
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////
    //////////////////////      Menu Display Functions       /////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * Function for displaying forms in the widget page
     *
     * @ Since 1.0.0
     * @ Updated 1.2.6.1
     */
    public function MenuDisplayCallback($options, $option, $fieldname, $fieldid)
    {
        $default = (isset($option['default']) ? $option['default'] : '');
        $optionname = (isset($option['name']) ? $option['name'] : '');
        $optiontitle = (isset($option['title']) ? $option['title'] : '');
        $optiondescription = (isset($option['description']) ? $option['description'] : '');
        $fieldtype = (isset($option['type']) ? $option['type'] : '');
        $value = (isset($options[$optionname]) ? $options[$optionname] : $default);

        // Output checkbox form field markup
        if ('checkbox' == $fieldtype) {
            ?>
      <input type="checkbox" id="<?php echo $fieldid; ?>" name="<?php echo $fieldname; ?>" value="1" <?php checked($value);?> />
      <label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label>
      <span class="describe"><?php echo $optiondescription; ?></span>
    <?php
}
        // Output radio button form field markup
        else if ('radio' == $fieldtype) {
            $valid_options = array();
            $valid_options = $option['valid_options'];
            ?><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label><?php
foreach ($valid_options as $valid_option) {
                ?>
        <input type="radio" name="<?php echo $fieldname; ?>" <?php checked($valid_option['name'] == $value);?> value="<?php echo $valid_option['name']; ?>" />
        <span class="describe"><?php echo $optiondescription; ?></span>
      <?php
}
        }
        // Output select form field markup
        else if ('select' == $fieldtype) {
            $valid_options = array();
            $valid_options = $option['valid_options'];
            ?>
      <label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label>
      <select id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>">
        <?php
foreach ($valid_options as $valid_option) {
                ?>
          <option <?php selected($valid_option['name'] == $value);?> value="<?php echo $valid_option['name']; ?>"><?php echo $valid_option['title']; ?></option>
        <?php
}
            ?>
      </select>
      <div class="describe"><span class="describe"><?php echo $optiondescription; ?></span></div>
    <?php
} // Output select form field markup
        else if ('range' == $fieldtype) {
            ?>
      <label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label>
      <select id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>">
        <?php
for ($i = $option['min']; $i <= $option['max']; $i++) {
                ?>
          <option <?php selected($i == $value);?> value="<?php echo $i; ?>"><?php echo $i ?></option>
        <?php
}
            ?>
      </select>
      <span class="describe"><?php echo $optiondescription; ?></span>
    <?php
}
        // Output text input form field markup
        else if ('text' == $fieldtype) {
            ?>
      <label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label>
      <input type="text" id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>" value="<?php echo ($value); ?>" />
      <div class="describe"><span class="describe"><?php echo $optiondescription; ?></span></div>
    <?php
} else if ('textarea' == $fieldtype) {
            ?>
      <label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label>
      <textarea id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>" class="AlpinePhotoTiles_textarea"><?php echo $value; ?></textarea><br>
      <span class="describe"><?php echo (function_exists('esc_textarea') ? esc_textarea($optiondescription) : $optiondescription); ?></span>
    <?php
} else if ('color' == $fieldtype) {
            $value = ($value ? $value : $default);
            ?>
      <label for="<?php echo $fieldid ?>">
        <input type="text" id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>" class="AlpinePhotoTiles_color" value="<?php echo ($value); ?>" /><span class="describe"><?php echo $optiondescription; ?></span></label>
      <div id="<?php echo $fieldid; ?>_picker" class="AlpinePhotoTiles_color_picker"></div>
    <?php
}
    }

    /**
     * Function for displaying forms in the admin page
     *
     * @ Since 1.0.0
     * @ Updated 1.2.6.1
     */
    public function AdminDisplayCallback($options, $option, $fieldname, $fieldid)
    {
        $default = (isset($option['default']) ? $option['default'] : '');
        $optionname = (isset($option['name']) ? $option['name'] : '');
        $optiontitle = (isset($option['title']) ? $option['title'] : '');
        $optiondescription = (isset($option['description']) ? $option['description'] : '');
        $fieldtype = (isset($option['type']) ? $option['type'] : '');
        $value = (isset($options[$optionname]) ? $options[$optionname] : $default);

        // Output checkbox form field markup
        if ('checkbox' == $fieldtype) {
            ?>
      <div class="title"><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label></div>
      <input type="checkbox" id="<?php echo $fieldid; ?>" name="<?php echo $fieldname; ?>" value="1" <?php checked($value);?> />
      <div class="admin-describe"><?php echo $optiondescription; ?></div>
    <?php
}
        // Output radio button form field markup
        else if ('radio' == $fieldtype) {
            $valid_options = array();
            $valid_options = $option['valid_options'];
            ?><div class="title"><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label></div><?php
foreach ($valid_options as $valid_option) {
                ?>
        <input type="radio" name="<?php echo $fieldname; ?>" <?php checked($valid_option['name'] == $value);?> value="<?php echo $valid_option['name']; ?>" />
        <span class="admin-describe"><?php echo $optiondescription; ?></span>
      <?php
}
        }
        // Output select form field markup
        else if ('select' == $fieldtype) {
            $valid_options = array();
            $valid_options = $option['valid_options'];
            ?>
      <div class="title"><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label></div>
      <select id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>">
        <?php
foreach ($valid_options as $valid_option) {
                ?>
          <option <?php selected($valid_option['name'] == $value);?> value="<?php echo $valid_option['name']; ?>"><?php echo $valid_option['title']; ?></option>
        <?php
}
            ?>
      </select>
      <div class="admin-describe"><?php echo $optiondescription; ?></div>
    <?php
} // Output select form field markup
        else if ('range' == $fieldtype) {
            ?>
      <div class="title"><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label></div>
      <select id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>">
        <?php
for ($i = $option['min']; $i <= $option['max']; $i++) {
                ?>
          <option <?php selected($i == $value);?> value="<?php echo $i; ?>"><?php echo $i ?></option>
        <?php
}
            ?>
      </select>
      <div class="admin-describe"><?php echo $optiondescription; ?></div>
    <?php
}
        // Output text input form field markup
        else if ('text' == $fieldtype) {
            ?>
      <div class="title"><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label></div>
      <input type="text" id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>" value="<?php echo ($value); ?>" />
      <div class="admin-describe" style="width:50%;"><?php echo $optiondescription; ?></div>
    <?php
} else if ('textarea' == $fieldtype) {
            ?>
      <div class="title"><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label></div>
      <textarea id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>" class="AlpinePhotoTiles_textarea"><?php echo $value; ?></textarea><br>
      <span class="admin-describe"><?php echo (function_exists('esc_textarea') ? esc_textarea($optiondescription) : $optiondescription); ?></span>
    <?php
} else if ('color' == $fieldtype) {
            $value = ($value ? $value : $default);
            ?>
      <div class="title"><label for="<?php echo $fieldid; ?>"><?php echo $optiontitle ?></label></div>
      <input type="text" id="<?php echo $fieldid ?>" name="<?php echo $fieldname; ?>" class="AlpinePhotoTiles_color" value="<?php echo ($value); ?>" />
      <div class="admin-describe" style="width:40%;"><?php echo $optiondescription; ?></div></label>
      <div id="<?php echo $fieldid; ?>_picker" class="AlpinePhotoTiles_color_picker"></div>
      <?php
}
    }

    //////////////////////////////////////////////////////////////////////////////////////
    ////////////////////      Input Validation Functions       ///////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////
    /**
     * Options Validate Pseudo-Callback
     *
     * @ Since 1.0.0
     * @ Updated 1.2.6
     */
    public function MenuOptionsValidate($newinput, $oldinput, $optiondetails)
    {
        $valid_input = $oldinput;
        $type = (isset($optiondetails['type']) ? $optiondetails['type'] : '');
        // Validate checkbox fields
        if ('checkbox' == $type) {
            // If input value is set and is true, return true; otherwise return false
            $valid_input = ((isset($newinput) && true == $newinput) ? true : false);
        }
        // Validate radio button fields
        else if ('radio' == $type) {
            // Get the list of valid options
            $valid_options = $optiondetails['valid_options'];
            // Only update setting if input value is in the list of valid options
            $newinput = sanitize_text_field($newinput);
            $valid_input = (array_key_exists($newinput, $valid_options) ? $newinput : $valid_input);
        }
        // Validate select fields
        else if ('select' == $type || 'select-trigger' == $type) {
            // Get the list of valid options
            $valid_options = $optiondetails['valid_options'];
            $newinput = sanitize_text_field($newinput);
            // Only update setting if input value is in the list of valid options
            $valid_input = ((isset($newinput) && is_array($valid_options) && array_key_exists($newinput, $valid_options)) ? $newinput : $valid_input);
        }

        // Validate text input and textarea fields
        else if (('text' == $type || 'textarea' == $type || 'image-upload' == $type)) {
            $valid_input = strip_tags($newinput);
            $sanatize = (isset($optiondetails['sanitize']) ? $optiondetails['sanitize'] : '');
            // Validate no-HTML content
            // nospaces option offers additional filters
            if ('nospaces' == $sanatize) {
                // Pass input data through the wp_filter_nohtml_kses filter
                $valid_input = wp_filter_nohtml_kses($newinput);

                // Remove specified character(s)
                if (isset($optiondetails['remove'])) {
                    if (is_array($optiondetails['remove'])) {
                        foreach ($optiondetails['remove'] as $remove) {
                            $valid_input = str_replace($remove, '', $valid_input);
                        }
                    } else {
                        $valid_input = str_replace($optiondetails['remove'], '', $valid_input);
                    }
                }
                // Switch or encode characters
                if (isset($optiondetails['encode']) && is_array($optiondetails['encode'])) {
                    foreach ($optiondetails['encode'] as $find => $replace) {
                        $valid_input = str_replace($find, $replace, $valid_input);
                    }
                }
                // Replace spaces with provided character or just remove spaces
                if (isset($optiondetails['replace'])) {
                    $valid_input = str_replace(array('  ', ' '), $optiondetails['replace'], $valid_input);
                } else {
                    $valid_input = str_replace(' ', '', $valid_input);
                }
            }
            // Check if numeric
            elseif ('numeric' == $sanatize && is_numeric(wp_filter_nohtml_kses($newinput))) {
                // Pass input data through the wp_filter_nohtml_kses filter
                $valid_input = wp_filter_nohtml_kses($newinput);
                if (isset($optiondetails['min']) && $valid_input < $optiondetails['min']) {
                    $valid_input = $optiondetails['min'];
                }
                if (isset($optiondetails['max']) && $valid_input > $optiondetails['max']) {
                    $valid_input = $optiondetails['max'];
                }
            } elseif ('int' == $sanatize && is_numeric(wp_filter_nohtml_kses($newinput))) {
                // Pass input data through the wp_filter_nohtml_kses filter
                $valid_input = round(wp_filter_nohtml_kses($newinput));
                if (isset($optiondetails['min']) && $valid_input < $optiondetails['min']) {
                    $valid_input = $optiondetails['min'];
                }
                if (isset($optiondetails['max']) && $valid_input > $optiondetails['max']) {
                    $valid_input = $optiondetails['max'];
                }
            } elseif ('tag' == $sanatize) {
                // Pass input data through the wp_filter_nohtml_kses filter
                $valid_input = wp_filter_nohtml_kses($newinput);
                $valid_input = str_replace(' ', '-', $valid_input);
            }
            // Validate no-HTML content
            elseif ('nohtml' == $sanatize) {
                // Pass input data through the wp_filter_nohtml_kses filter
                $valid_input = wp_filter_nohtml_kses($newinput);
                $valid_input = str_replace(' ', '', $valid_input);
            }
            // Validate HTML content
            elseif ('html' == $sanatize) {
                // Pass input data through the wp_filter_kses filter using allowed post tags
                $valid_input = wp_kses_post($newinput);
            }
            // Validate URL address
            elseif ('url' == $sanatize) {
                $valid_input = esc_url($newinput);
            }
            // Validate CSS
            elseif ('css' == $sanatize) {
                $valid_input = wp_htmledit_pre(stripslashes($newinput));
            }
            // Just strip slashes
            elseif ('stripslashes' == $sanatize) {
                $valid_input = stripslashes($newinput);
            }
        } else if ('wp-textarea' == $type) {
            // Text area filter
            $valid_input = wp_kses_post(force_balance_tags($newinput));
        } elseif ('color' == $type) {
            $value = wp_filter_nohtml_kses($newinput);
            if ('#' == $value) {
                $valid_input = '';
            } else {
                $valid_input = $value;
            }
        }
        return $valid_input;
    }
}
/**
 *
 *
 *    AlpineBot
 *
 *    ADMIN Functions
 *    Contains ONLY UNIQUE ADMIN functions
 *
 *
 */

class PhotoTileForInstagramAdmin extends PhotoTileForInstagramAdminSecondary
{

    //////////////////////////////////////////////////////////////////////////////////////
    ////////////////////        Unique Admin Functions        ////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////
    public $message;
    public $instagram_url = 'https://www.instagram.com';
    private $redirect_uri = 'https://thealpinepress.com/auth/index.php';
    private $app_id = '621225125153883';
    private $instagram_graph_url = 'https://graph.instagram.com';
    private $facebook_graph_url = 'https://graph.facebook.com';

    /**
     * Add User
     *
     * @ Since 1.2.0
     * @ Updated 1.2.6.2
     */
    public function AddUser($post_content)
    {

       // var_dump($post_content);

        if (isset($post_content['access_token']) && !empty($post_content['access_token']) && isset($post_content['username']) && !empty($post_content['username']) && isset($post_content['user_id']) && !empty($post_content['user_id'])) {
            // All necessary data is accounted for

            // Empty array to store users
            $currentUsers = array();

            $username = $post_content['username'];
            $oldoptions = $this->get_all_options();

            if (isset($oldoptions['users']) && !empty($oldoptions['users'])) {
                // Check current record of users
                $currentUsers = $oldoptions['users'];
            }

            $post_content['name'] = $username;
            $post_content['title'] = $username;
            // Add user to users array
            $currentUsers[$username] = $post_content;

            // Re-assign users array
            $oldoptions['users'] = $currentUsers;
            update_option($this->get_private('settings'), $oldoptions);
        }
        return true;
    }
    /**
     * Delete User
     *
     * @since 1.2.0
     *
     */
    public function DeleteUser($user)
    {
        $oldoptions = $this->get_all_options();
        $currentUsers = $oldoptions['users'];
        if (!empty($currentUsers[$user])) {
            unset($currentUsers[$user]);
        }
        $oldoptions['users'] = $currentUsers;
        update_option($this->get_private('settings'), $oldoptions);
    }
    /**
     * Update User
     *
     * @since 1.2.0
     *
     */
    public function UpdateUser($data)
    {
        $oldoptions = $this->get_all_options();
        $currentUsers = $oldoptions['users'];

        if (!empty($data['username']) && !empty($oldoptions['users']) && !empty($oldoptions['users'][$data['username']])) {
            $current = $oldoptions['users'][$data['username']];
            foreach ($data as $k => $v) {
                if (!empty($v)) {
                    $current[$k] = $v;
                }
            }
            $oldoptions['users'][$data['username']] = $current;
            update_option($this->get_private('settings'), $oldoptions);
        }
    }

    /**
     * Alpine PhotoTile: Options Page
     *
     * @ Since 1.1.1
     * @ Updated 1.2.7.6
     */
    public function admin_build_settings_page()
    {
        $currenttab = isset($_GET['tab']) ? $_GET['tab'] : 'general';
        $currenttab = sanitize_text_field($currenttab);
        // Check for valid tab
        $possible_tabs = array_keys($this->admin_settings_page_tabs());
        if (!in_array($currenttab, $possible_tabs)) {
            $currenttab = 'general';
        }

        echo '<div class="wrap AlpinePhotoTiles_settings_wrap">';
        $this->admin_options_page_tabs($currenttab);

        echo '<div class="AlpinePhotoTiles-container ' . $this->get_private('domain') . '">';
        echo '<div class="AlpinePhotoTiles-' . $currenttab . '" style="position:relative;width:100%;">';
        if ('general' == $currenttab) {
            $this->admin_display_general();
        } elseif ('add' == $currenttab) {
            $this->admin_display_add();
        } elseif ('plugin-tools' == $currenttab) {
            $this->admin_display_tools();
        } else {
            $this->admin_setup_options_form($currenttab);
        }
        echo '</div>';

        echo '<div class="bottom" style="position:relative;width:100%;margin-top:20px;">';
        echo '<div class="help-link"><p>' . __('Need Help? Visit ') . '<a href="' . $this->get_private('info') . '" target="_blank">the Alpine Press</a>' . __(' for more about this plugin.') . '</p></div>';
        echo '</div>';
        echo '</div>'; // Close Container

        echo '</div>'; // Close wrap
    }

    /**
     * Show User Function
     *
     * @ Since 1.2.0
     * @ Updated 1.2.5
     */
    public function show_user($info)
    {
        $name = (isset($info['username']) ? $info['username'] : 'user');
        $picture = (isset($info['picture']) ? $info['picture'] : 'user');
        $output = '<div id="user-icon-' . $name . '" class="user-icon" style="padding-bottom:10px;">';
        $output .= '<div><h4>' . $name . '</h4></div>';
        $output .= '<div><img src="' . $picture . '" style="width:80px;height:80px;"></div>';
        // Not currently needed
        $output .= '<form id="' . $this->get_private('settings') . '-user-' . $name . '" action="" method="post">';
        $output .= '<input type="hidden" name="hidden" value="Y">';
        $output .= '<input type="hidden" name="update-user" value="Y">';
        $output .= '<input type="hidden" name="user" value="' . $name . '">';
        $output .= '<input id="' . $this->get_private('settings') . '-submit" name="' . $this->get_private('settings') . '_update[submit-update]" type="submit" class="button-primary" style="margin-top:15px;float:none;" value="Update User" />';
        $output .= '</form>';
        $output .= '<form id="' . $this->get_private('settings') . '-delete-' . $name . '" action="" method="post">';
        $output .= '<input type="hidden" name="hidden" value="Y">';
        $output .= '<input type="hidden" name="delete-user" value="Y">';
        $output .= '<input type="hidden" name="user" value="' . $name . '">';
        $output .= '<input id="' . $this->get_private('settings') . '-submit" name="' . $this->get_private('settings') . '_delete[submit-delete]" type="submit" class="button-primary" style="margin-top:15px;float:none;" value="Delete User" />';
        $output .= '</form>';
        $output .= '</div>';
        return $output;
    }
    /**
     * Show User Javascript
     *
     * @ Since 1.2.0
     *
     * // Not currently needed
     */
    public function show_user_js($info)
    {
        $redirect = admin_url('options-general.php?page=' . $this->get_private('settings') . '&tab=add');
        $output = 'jQuery(document).ready(function() {var url = "https://api.instagram.com/oauth/authorize/"+"?redirect_uri=" + encodeURIComponent("' . $redirect . '")+ "&response_type=code" + "&client_id=' . $info['client_id'] . '" + "&display=touch";jQuery("#' . $this->get_private('settings') . '-user-' . $info['username'] . '").ajaxForm({ success: function(responseText){  window.location.replace(url); } });  });';
        return $output;
    }
    /**
     * Display Add User Page
     *
     * @ Since 1.2.0
     * @ Updated 1.2.7
     */
    public function admin_display_add()
    {

        $currenttab = 'add';
        $options = $this->get_all_options();
        $settings_section = $this->get_private('id') . '_' . $currenttab . '_tab';
        $submitted = ((isset($_POST["hidden"]) && ($_POST["hidden"] == "Y")) ? true : false);

        $redirect = admin_url('options-general.php?page=' . $this->get_private('settings') . '&tab=' . $currenttab);
        $success = false;
        $errormessage = null;
        $errortype = null;

        if (isset($_POST['add-user']) && ($_POST['add-user'] === "Y")) {

        } elseif (isset($_POST['delete-user']) && isset($_POST['user'])) {
            // Delete User button was pressed
            wp_verify_nonce($currenttab);
            $delete = true;
            $user = $_POST['user'];
            $this->DeleteUser(sanitize_text_field($user));
        } elseif (isset($_POST['update-user']) && isset($_POST['user'])) {
            // Update User button was pressed
            wp_verify_nonce($currenttab);
            $user = sanitize_text_field($_POST['user']);
            $users = $this->get_instagram_users();
            if (!empty($users) && !empty($users[$user]) && !empty($users[$user]['access_token']) && !empty($users[$user]['user_id'])) {
                $request = 'https://api.instagram.com/v1/users/' . $users[$user]['user_id'] . '/?access_token=' . $users[$user]['access_token'];
                $response = wp_remote_get(
                    $request,
                    array(
                        'method' => 'GET',
                        'timeout' => 10,
                        'sslverify' => apply_filters('https_local_ssl_verify', false),
                    )
                );
                if (is_wp_error($response) || !isset($response['body'])) {
                    // Try again

                    $errormessage = 'User not updated';
                } else {
                    $content = $response['body'];
                }

                if (isset($content)) {
                    // Before decoding JSON, remove Emoji characters from content

                    if (function_exists('json_decode')) {
                        $_instagram_json = @json_decode($content, true);
                    } elseif (function_exists('alpine_json_decode')) {
                        $_instagram_json = @alpine_json_decode($content, true);
                    }

                    if (empty($_instagram_json) || 200 != $_instagram_json['meta']['code']) {
                        $errormessage = 'User not updated';
                    } elseif (!empty($_instagram_json['data'])) {
                        $data = $_instagram_json['data'];
                        $post_content = array(
                            'access_token' => $users[$user]['access_token'],
                            'username' => $data['username'],
                            'picture' => $data['profile_picture'],
                            'fullname' => $data['full_name'],
                            'user_id' => $users[$user]['user_id'],
                        );
                        $this->UpdateUser($post_content);
                        $update = true;
                    }
                }
            }
        } elseif ($submitted && isset($_POST['manual-user-form']) && !empty($_POST['access_token']) && !empty($_POST['user_id']) && !empty($_POST['client_id']) && !empty($_POST['username'])) {
            $success = $this->AddUser($_POST);
        }  else {
                   // $errormessage = 'No access token found';
                }

                if (isset($_GET['access_token'])) {
                  // Callback has been received from Instagram

                     $username = $_GET['username'];
                      $access_token = $_GET['access_token'];
                      $user_id = $_GET['user_id'];
                      $media_count = $_GET['media_count'];
                      $acc_type = $_GET['acc_type'];
                      $image = $this->get_private('url') . "/css/images/avatar.png";

                          $post_content = array(
                              'access_token' => $access_token,
                              'username' => $username,
                              'picture' => $image,
                              'fullname' => $username,
                              'user_id' => $user_id,
                              'media_count' => $media_count,
                              'acc_type' => $acc_type

                          );
                          $success = $this->AddUser($post_content);
                          $redirect = admin_url('options-general.php'). "?page=alpine-photo-tile-for-instagram-settings&tab=add";
                          ?>
                      <script>
                        window.location = " <?php echo $redirect;  ?>"

                      </script>

                      <?php

                      }


        $defaults = $this->option_defaults();
        $positions = $this->get_option_positions_by_tab($currenttab);
        $state = admin_url('options-general.php');
        $app_id = "621225125153883";

        echo '<div class="AlpinePhotoTiles-add">';
        echo '<a href="https://www.instagram.com/oauth/authorize?app_id=' . $app_id . '&redirect_uri=https://thealpinepress.com/auth/&response_type=code&scope=user_profile,user_media&state=' . $state . '" class="button Alpine-add-account"><span class="dashicons dashicons-instagram"></span> Add Instagram Account</a>';

        if (!empty($success)) {
            echo '<div class="announcement"> User successfully authorized. </div>';
        } elseif (!empty($update)) {
            echo '<div class="announcement"> User (' . $user . ') updated. </div>';
        } elseif (!empty($delete)) {
            echo '<div class="announcement"> User (' . $user . ') deleted. </div>';
        } elseif (!empty($errormessage)) {
            echo '<div class="announcement"> An error occured (' . $errormessage . '). </div>';
        }
        if (count($positions)) {
            foreach ($positions as $position => $positionsinfo) {
                if ($position == 'top') {
                    echo '<div id="AlpinePhotoTiles-user-list" style="margin-bottom:20px;padding-bottom:20px;overflow:hidden;border-bottom: 1px solid #DDDDDD;">';
                    if ($positionsinfo['title']) {

                        echo '<h4 style="border-bottom: 1px solid #DDDDDD; padding-bottom: 20px;">' . $positionsinfo['title'] . '</h4>';
                    }
                    $users = $this->get_instagram_users();
                    //var_dump($users);
                    if (empty($users) || (is_array($users) && isset($users['none']) && is_array($users['none']))) {
                        echo '<p id="AlpinePhotoTiles-user-empty">No users available. Add a user by following the instructions below.</p>';
                    } elseif (!empty($users) && is_array($users)) {
                        foreach ($users as $name => $info) {
                            echo $this->show_user($info);
                            //echo '<script type = "text/javascript">'.$this->show_user_js($info).'</script>'; // Not currently needed
                        }
                    }
                    echo '</div>';
                } else {

                    echo '<div style="max-width:680px;">';
                    // Display Message about adding users
                    $this->admin_display_add_message();
                    // Show directions for Add User Method Two
                    $this->admin_display_method_two();
                    // Show directions for Add User Method One
                  //  $this->admin_display_method_one($redirect);
                    echo '</div>';

                    // Display Form
                    echo '<div id="AlpinePhotoTiles-user-form" style="overflow:hidden;">';
                    echo '</div>';
                }
            }
        }
        echo '</div>'; // close add div

    }

    /**
     * Display Add User Method One
     *
     * @ Since 1.2.6.3
     */
    public function admin_display_method_one($redirect)
    {
        ?>

        <?php
    }

    /**
     * Display Add User Method Two
     *
     * @ Since 1.2.6.3
     */
    public function admin_display_method_two()
    {
        ?>
      <div style="margin-top:40px;padding-top:10px;border-top: 1px solid #DDDDDD;">
        <h2><?php _e("Method Two");?>:</h2>
        <p>Your Internet browser or the server that your WordPress site is hosted on may cause Method One to fail. Therefore, in Method Two you will directly enter the information and then manually submit it to the plugin using the form below.</p>
          <ol>
            <li>
              <?php _e('Follow directions in video');?>  <?php _e('to register another Instagram client and to retrieve your Instagram user information. Do not skip any of the steps.');?>
            </li>
            <li>
              <?php _e('Once this is done, your Instagram information will be displayed in a green box. Fill out the "Manually Add New User" form below and click "Store User Information".');?>
            </li>
          </ol>

         <div id="AlpinePhotoTiles-manual-user-form" style="overflow:hidden;padding:20px;border: 1px solid #DDDDDD;">
            <h4>Manually Add New User</h4>
            <form id="alpine-photo-tile-for-instagram-settings-add-user" method="post" action="">
              <input type="hidden" value="Y" name="hidden">
                <div class="center">
                  <table class="form-table">
                    <tbody>
                      <?php $the_content = array('username' => 'Username', 'user_id' => 'User ID', 'access_token' => 'Access Token', 'client_id' => 'Client ID', 'client_secret' => 'Client Secret', 'picture' => 'Picture');
        foreach ($the_content as $name => $title) {
            ?>
                        <tr valign="top">
                        <td>
                          <div class="title">
                          <label for="<?php echo $name; ?>"><?php echo $title; ?> : </label>
                          </div>
                          <input id="<?php echo $name; ?>" type="text" value="" name="<?php echo $name; ?>" style="width:400px">
                        </td>
                        </tr>

                        <?php }?>
                    </tbody>
                  </table>
                </div>
              <input id="manual-form-submit" class="button-primary" type="submit" value="Store User Information" style="margin-top:15px;" name="manual-user-form">
            </form>
          <br style="clear:both;">
        </div>

      </div>
    <?php
}

/**
 * Display Add User Message
 *
 * @ Since 1.2.6.3
 */
    public function admin_display_add_message()
    {
        ?>
      <h1><?php _e('How to add an Instagram User');?>:</h1>
      <!-- <h3>(<?php _e("Don't worry. I promise it's EASY");?>!!!)</h3> -->
      <p><?php _e("Below are two different methods for adding users to the Alpine PhotoTile for Instagram plugin. Try Method One first. If it does not work, try Method Two. Please <a href='http://wordpress.org/support/plugin/alpine-photo-tile-for-social/'>let me know</a> if these directions become outdated.");?>
    <?php
}

    /**
     * Display Tools Page
     *
     * @ Since 1.2.7
     */
    public function admin_display_tools()
    {
        echo '<div class="top">';
        echo '<h4>System Check</h4>';
        echo '<div style="margin-bottom:15px;"><span class="describe" >Check the settings and extensions on your web server.</span></div>';
        echo '<table class="form-table">';
        echo '<tbody>';
        // PHP Version
        echo '<tr valign="top"><td class="unlinked "><div class="title">';
        if (function_exists('phpversion')) {
            echo '<b>Current PHP version of your server:</b> ' . phpversion();
        } else {
            echo '<b>Current PHP version of your server:</b> < 4';
        }
        echo '</div></td></tr>';
        // cURL
        echo '<tr valign="top"><td class="unlinked "><div class="title">';
        if (function_exists('curl_init')) {
            echo '<b>Check:</b> <span style="color:green">curl_init function found</span>.';
            $version = curl_version();
            echo '<br><b>cURL Version:</b> ' . $version['version'];
            // Try connecting to Instagram.com
            $request = 'http://instagram.com/';
            $response = wp_remote_get($request, array('timeout' => 10));
            if (is_wp_error($response)) {
                echo '<br><b>Check:</b> <span style="color:red">Plugin failed to connect to Instagram.com.</span>';
                echo '<br><b>WordPress Error Message:</b> ' . $response->get_error_message() . '.';
            } else {
                if (isset($response['response']) && isset($response['response']['code']) && isset($response['response']['message'])) {
                    if ($response['response']['code'] == 200) {
                        echo '<br><b>Check:</b> <span style="color:green">Plugin successfully connected to Instagram.com.</span>';
                    } else {
                        echo '<br><b>Check:</b> <span style="color:red">Plugin failed to connect to Instagram.com.</span>';
                        echo '<br><b>Code:</b> ' . $response['response']['code'] . ', <b>Message:</b> ' . $response['response']['message'] . '.';
                    }
                }
            }
        } else {
            echo '<p><b>Check:</b> <span style="color:red">curl_init function not found.</span> To connect to Instagram.com, your server needs to have the cURL extension enabled. Unfortunately, this extension was not found on your server.</p>';
            echo '<p><b>Recommendation(s):</b></p>';
            echo '<ol>';
            echo '<li>Contact your web host. They may need to simply enable a PHP extension or open a port.</li>';
            echo '</ol>';
        }
        // JSON Decode
        echo '<tr valign="top"><td class="unlinked "><div class="title">';
        if (function_exists('json_decode')) {
            echo '<b>Check:</b> <span style="color:green">json_decode function found.</span>';
            $m = json_decode('{"code":1}', true);
            if (!empty($m) && isset($m['code']) && $m['code'] == 1) {
                echo '<br><b>Check:</b> <span style="color:green">Sample JSON successfully decoded and parsed.</span>';
            } else {
                echo '<br><b>Check:</b> <span style="color:red">Server failed to decode sample JSON.</span>';
            }
        } else {
            echo '<p><b>Check:</b> <span style="color:red">json_decode function not found</span>. Instagram feeds are in a format known as JSON. Servers with PHP 5.2.0+ have a JSON extension that allows the server to quickly interpret the JSON feed. Unfortunately, this function was not found on your server.</p>';
            echo '<p><b>Recommendation(s):</b></p>';
            echo '<ol>';
            echo '<li>Contact your web host. A good hosting provider should be using an updated version of PHP with JSON extensions enabled.</li>';
            echo '<li>The Alpine plugin includes a backup function that can interpret JSON, but it is <b>very slow</b>. You can expect loading times of 20+ seconds. Therefore, I recommended visiting the Plugin Settings page and setting the cache time to between 24 and 48 hours.</li>';
            echo '</ol>';
            //echo '<div class="announcement"> PHP Server is missing . </div>';
        }
        echo '</div></td></tr>';
        // Rec
        echo '<tr valign="top"><td class="unlinked "><div class="title">';

        echo '</div></td></tr>';

        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // Close top

        $currenttab = 'plugin-tools';

        $defaults = $this->option_defaults();
        $submitted = ((isset($_POST["hidden"]) && ($_POST["hidden"] == "Y")) ? true : false);
        $user = wp_get_current_user();
        $allowed_roles = array('administrator');

        $test = (isset($_POST[$this->get_private('settings') . '_test']['submit-test']) && $_POST[$this->get_private('settings') . '_test']['submit-test'] == 'Test Plugin') ? true : false;
        if (array_intersect($allowed_roles, $user->roles) && $submitted && isset($_POST['shortcode']) && $test) {
            $sub_shortcode = sanitize_text_field($_POST['shortcode']);
            $short = str_replace('\"', '"', $sub_shortcode);
        } else {
            $short = '';
        }

        ?>
        <div>
          <h3>Plugin Loading Test</h3>
          <p>Create a shortcode using the Shortcode Generator and paste it into the box below. Then, click "Test Plugin" to use the tool.
            The plugin will be loaded once directly from the Instagram feed and once from the cache (unless disabled).
            This test shows the server-side loading times only and does not include delays from loading photos into a browser or running the JS/jQuery code
            (It should be clear that loading from the cache is much faster).</p>
        </div>
        <?php

        ?>
        <form action="" method="post">
          <input type="hidden" name="hidden" value="Y">
          <div id="<?php echo $this->get_private('settings'); ?>-shortcode" style="position:relative;clear:both;margin-bottom:20px;">
            <div class="AlpinePhotoTiles-test" style="">
              <input type="hidden" name="hidden" value="Y">
              <textarea id="shortcode" name="shortcode" style="margin-bottom:20px;height:100px;"><?php echo $short; ?></textarea>
              <input name="<?php echo $this->get_private('settings'); ?>_test[submit-test]" type="submit" class="button-primary" value="Test Plugin" />
              <br style="clear:both">
            </div>
          </div>
        </form>
    <?php

        if ($submitted && isset($_POST['shortcode']) && $test) {
            // Use "plugin-loading-test" as widget id and set "test" variable to true.
            $plugin_id = "plugin-loading-test";
            $short_one = str_replace(']', ' id=' . $plugin_id . ' testmode=1]', $short); // No cache
            $short_two = str_replace(']', ' id=' . $plugin_id . ' testmode=2]', $short); // With cache
            //echo $short;

            echo '<br>';
            echo '<h3>Load from the Instagram feed</h3>';
            echo '<div style="border-top: 1px solid #DDDDDD;padding-bottom:10px;margin-bottom:30px;">';
            echo do_shortcode($short_one);
            echo '</div>';
            echo '<h3>Load from cache</h3>';
            echo '<div style="border-top: 1px solid #DDDDDD;padding-bottom:0px;margin-bottom:0px;">';
            echo do_shortcode($short_two);
            echo '</div>';
        }
    }
}
