<?php

/*
 * Osclass - software for creating and publishing online classified advertising platforms
 * Maintained and supported by Mindstellar Community
 * https://github.com/mindstellar/Osclass
 * Copyright (c) 2021.  Mindstellar
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *                     GNU GENERAL PUBLIC LICENSE
 *                        Version 3, 29 June 2007
 *
 *  Copyright (C) 2007 Free Software Foundation, Inc. <http://fsf.org/>
 *  Everyone is permitted to copy and distribute verbatim copies
 *  of this license document, but changing it is not allowed.
 *
 *  You should have received a copy of the GNU Affero General Public
 *  License along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * Gets urls for current theme administrations options
 *
 * @param string $file must be a relative path, from ABS_PATH
 *
 * @return string
 */
function osc_admin_render_theme_url($file = '')
{
    return osc_admin_base_url(true) . '?page=appearance&action=render&file=' . $file;
}


/**
 * Render the specified file
 *
 * @param string $file must be a relative path, from PLUGINS_PATH
 */
function osc_render_file($file = '')
{
    if ($file == '') {
        $file = __get('file');
    }
    // Clean $file to prevent hacking of some
    osc_sanitize_url($file);
    $file = str_replace(array(
                            "..\\",
                            '../'
                        ), '', str_replace('://', '', preg_replace('|http([s]*)|', '', $file)));
    if (file_exists(osc_themes_path() . osc_theme() . '/plugins/' . $file)) {
        include osc_themes_path() . osc_theme() . '/plugins/' . $file;
    } elseif (file_exists(osc_plugins_path() . $file)) {
        include osc_plugins_path() . $file;
    }
}


/**
 * Gets urls for render custom files in front-end
 *
 * @param string $file must be a relative path, from PLUGINS_PATH
 *
 * @return string
 */
function osc_render_file_url($file = '')
{
    osc_sanitize_url($file);
    $file = str_replace(array(
                            "..\\",
                            '../'
                        ), '', str_replace('://', '', preg_replace('|http([s]*)|', '', $file)));

    return osc_base_url(true) . '?page=custom&file=' . $file;
}


/**
 * Re-send the flash messages of the given section. Usefull for custom theme/plugins files.
 *
 * @param string $section
 */
function osc_resend_flash_messages($section = 'pubMessages')
{
    $messages = Session::newInstance()->_getMessage($section);
    if (is_array($messages)) {
        foreach ($messages as $message) {
            $message = Session::newInstance()->_getMessage($section);
            if (isset($message['msg'])) {
                if (isset($message['type']) && $message['type'] === 'info') {
                    osc_add_flash_info_message($message['msg'], $section);
                } elseif (isset($message['type']) && $message['type'] === 'ok') {
                    osc_add_flash_ok_message($message['msg'], $section);
                } else {
                    osc_add_flash_error_message($message['msg'], $section);
                }
            }
        }
    }
}


/**
 * Enqueue script
 *
 * @param string $id
 */
function osc_enqueue_script($id)
{
    Scripts::newInstance()->enqueueScript($id);
}


/**
 * Remove script from the queue, so it will not be loaded
 *
 * @param string $id
 */
function osc_remove_script($id)
{
    Scripts::newInstance()->removeScript($id);
}


/**
 * Add script to be loaded
 *
 * @param $id           string Id to identify the script
 * @param $url          string url of the .js file
 * @param $dependencies mixed, could be an array or a string
 */
function osc_register_script($id, $url, $dependencies = null)
{
    Scripts::newInstance()->registerScript($id, $url, $dependencies);
}


/**
 * Remove script from the queue, so it will not be loaded
 *
 * @param string $id
 */
function osc_unregister_script($id)
{
    Scripts::newInstance()->unregisterScript($id);
}


/**
 * Print the HTML tags to make the script load
 */
function osc_load_scripts()
{
    Scripts::newInstance()->printScripts();
    if (defined('OC_ADMIN') && OC_ADMIN) {
        osc_run_hook('admin_scripts_loaded');
    } else {
        osc_run_hook('scripts_loaded');
    }
}


/**
 * Register style with dependencies
 *
 * @param $id           string Id to identify the style
 * @param $url          string url of the .css file
 * @param $dependencies mixed, could be an array or a string
 */
function osc_register_style($id, $url, $dependencies = null)
{
    Styles::newInstance()->register($id, $url, $dependencies);
}


/**
 * Remove style from the queue, so it will not be loaded
 *
 * @param string $id
 */
function osc_unregister_style($id)
{
    Styles::newInstance()->unregister($id);
}


/**
 * Add style to be loaded
 * If style is already registered only id is needed to enqueue style
 *
 * @param $id  string Id to identify the style
 * @param $url string|null Url of the .css file
 */
function osc_enqueue_style($id, $url = null)
{
    if ($url === null) {
        Styles::newInstance()->enqueue($id);
    } else {
        Styles::newInstance()->addStyle($id, $url);
    }
}


/**
 * Remove style from the queue, so it will not be loaded
 *
 * @param $id
 */
function osc_remove_style($id)
{
    Styles::newInstance()->removeStyle($id);
}


/**
 * Print the HTML tags to make the style load
 */
function osc_load_styles()
{
    Styles::newInstance()->printStyles();
}


/**
 * @param        $id
 * @param        $name
 * @param        $options
 * @param string $class
 */
function osc_print_bulk_actions($id, $name, $options, $class = '')
{
    echo '<select id="' . $id . '" name="' . $name . '" ' . ($class != '' ? 'class="form-select ' . $class . '"' : 'form-select') . '>';
    foreach ($options as $o) {
        $opt   = '';
        $label = '';
        foreach ($o as $k => $v) {
            if ($k !== 'label') {
                $opt .= $k . '="' . $v . '" ';
            } else {
                $label = $v;
            }
        }
        echo '<option ' . $opt . '>' . $label . '</option>';
    }
    echo '</select>';
}


/* file end: ./oc-includes/osclass/hTheme.php */
