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

define('OC_ADMIN', true);

require_once dirname(__DIR__) . '/oc-load.php';

if (file_exists(ABS_PATH . '.maintenance')) {
    define('__OSC_MAINTENANCE__', true);
}

// register admin scripts
osc_register_script('admin-osc', osc_current_admin_theme_js_url('osc.js?v='.OSCLASS_VERSION), 'jquery');
osc_register_script('admin-ui-osc', osc_current_admin_theme_js_url('ui-osc.js?v='.OSCLASS_VERSION), 'jquery-ui');
osc_register_script('admin-location', osc_current_admin_theme_js_url('location.min.js?v='.OSCLASS_VERSION), 'bootstrap5');
osc_register_script('bootstrap5', osc_assets_url('bootstrap/bootstrap.min.js?v='.OSCLASS_VERSION));
osc_register_script('sortablejs', osc_assets_url('sortablejs/Sortable.min.js?v='.OSCLASS_VERSION));
// enqueue scripts
osc_enqueue_script('bootstrap5');
osc_enqueue_script('admin-osc');
osc_enqueue_script('admin-ui-osc');

osc_add_hook('admin_footer', array('FieldForm', 'i18n_datePicker'));

// register css styles
osc_register_style('jquery-ui', osc_assets_url('jquery-ui/jquery-ui.min.css?v='.OSCLASS_VERSION));
osc_register_style('admin-css', osc_current_admin_theme_styles_url('main.css?v='.OSCLASS_VERSION));
osc_register_style('bootstrap-icons', osc_assets_url('bootstrap-icons/bootstrap-icons.css?v='.OSCLASS_VERSION));

// enqueue css styles
osc_enqueue_style('admin-css');
osc_enqueue_style('jquery-ui');
osc_enqueue_style('bootstrap-icons');

switch (Params::getParam('page')) {
    case ('items'):
        $do = new CAdminItems();
        $do->doModel();
        break;
    case ('comments'):
        $do = new CAdminItemComments();
        $do->doModel();
        break;
    case ('media'):
        $do = new CAdminMedia();
        $do->doModel();
        break;
    case ('login'):
        $do = new CAdminLogin();
        $do->doModel();
        break;
    case ('categories'):
        $do = new CAdminCategories();
        $do->doModel();
        break;
    case ('emails'):
        $do = new CAdminEmails();
        $do->doModel();
        break;
    case ('pages'):
        $do = new CAdminPages();
        $do->doModel();
        break;
    case ('settings'):
        $do = new CAdminSettings();
        $do->doModel();
        break;
    case ('plugins'):
        $do = new CAdminPlugins();
        $do->doModel();
        break;
    case ('languages'):
        $do = new CAdminLanguages();
        $do->doModel();
        break;
    case ('admins'):
        $do = new CAdminAdmins();
        $do->doModel();
        break;
    case ('users'):
        $do = new CAdminUsers();
        $do->doModel();
        break;
    case ('ajax'):
        $do = new CAdminAjax();
        $do->doModel();
        break;
    case ('appearance'):
        $do = new CAdminAppearance();
        $do->doModel();
        break;
    case ('tools'):
        $do = new CAdminTools();
        $do->doModel();
        break;
    case ('stats'):
        $do = new CAdminStats();
        $do->doModel();
        break;
    case ('cfields'):
        $do = new CAdminCFields();
        $do->doModel();
        break;
    case ('upgrade'):
        $do = new CAdminUpgrade();
        $do->doModel();
        break;
    default:            //login of oc-admin
        $do = new CAdminMain();
        $do->doModel();
}

/* file end: ./oc-admin/index.php */
