<?php if (!defined('OC_ADMIN')) {
    exit('Direct access is not allowed.');
}
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

function addHelp()
{
    echo '<p>'
         . __('Add, edit or delete information associated to registered users. Keep in mind that deleting a user also '
              . 'deletes all the listings the user published.')
         . '</p>';
}


osc_add_hook('help_box', 'addHelp');

function customPageHeader()
{
    ?>
    <h1><?php _e('Users'); ?>
        <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=settings'; ?>"
           class="ms-1 text-dark float-end" title="<?php _e('Settings'); ?>"><i class="bi bi-gear-fill"></i></a>
        <a class="ms-1 bi bi-question-circle-fill float-right" data-bs-target="#help-box" data-bs-toggle="collapse" href="#help-box"></a>
        <a href="<?php echo osc_admin_base_url(true) . '?page=users&action=create'; ?>"
           class="ms-1 text-success float-end" data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php _e('Add'); ?>"><i
                    class="bi bi-plus-circle-fill"></i></a>
    </h1>
    <?php
}


osc_add_hook('admin_page_header', 'customPageHeader');

/**
 * @param $string
 *
 * @return string
 */
function customPageTitle($string)
{
    return sprintf(__('Manage users &raquo; %s'), $string);
}


osc_add_filter('admin_title', 'customPageTitle');

//customize Head
function customHead()
{
    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            // users autocomplete
            $('input[name="user"]').attr("autocomplete", "off");
            $('#user,#fUser').autocomplete({
                source: "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=userajax", //+$('input[name="user"]').val(), // &term=
                minLength: 0,
                select: function (event, ui) {
                    if (ui.item.id == '')
                        return false;
                    $('#userId').val(ui.item.id);
                    $('#fUserId').val(ui.item.id);
                },
                search: function () {
                    $('#userId').val('');
                    $('#fUserId').val('');
                }
            });

            $('.ui-autocomplete').css('zIndex', 10000);
        });
    </script>
    <?php
}


osc_add_hook('admin_header', 'customHead', 10);

$aData          = __get('aData');
$aRawRows       = __get('aRawRows');
$iDisplayLength = __get('iDisplayLength');
$sort           = Params::getParam('sort');
$direction      = Params::getParam('direction');

$columns     = $aData['aColumns'];
$rows        = $aData['aRows'];
$withFilters = __get('withFilters');
?>
<?php osc_current_admin_theme_path('parts/header.php'); ?>
    <h2 class="render-title"><?php _e('Manage users'); ?></h2>
    <div class="relative">
        <div id="users-toolbar" class="table-toolbar d-flex justify-content-end">
            <form method="get" action="<?php echo osc_admin_base_url(true); ?>" class="inline nocsrf">
                <?php foreach (Params::getParamsAsArray('get') as $key => $value) { ?>
                    <?php if ($key !== 'iDisplayLength') { ?>
                        <input type="hidden" name="<?php echo osc_esc_html($key); ?>"
                               value="<?php echo osc_esc_html($value); ?>"/>
                    <?php }
                } ?>
                <select name="iDisplayLength" class="form-select form-select-sm"
                        onchange="this.form.submit();">
                    <option value="10"><?php printf(__('%d Listings'), 10); ?></option>
                    <option value="25" <?php if (Params::getParam('iDisplayLength') == 25) {
                        echo 'selected';
                                       } ?> ><?php printf(__('%d Listings'), 25); ?></option>
                    <option value="50" <?php if (Params::getParam('iDisplayLength') == 50) {
                        echo 'selected';
                                       } ?> ><?php printf(__('%d Listings'), 50); ?></option>
                    <option value="100" <?php if (Params::getParam('iDisplayLength') == 100) {
                        echo 'selected';
                                        } ?> ><?php printf(__('%d Listings'), 100); ?></option>
                </select>
            </form>
            <form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="shortcut-filters"
                  class="inline nocsrf">
                <fieldset class="input-group input-group-sm">
                    <input type="hidden" name="page" value="users"/>
                    <input id="fUser" name="user" type="text" class="fUser input-text input-actions"
                           value="<?php echo osc_esc_html(Params::getParam('user')); ?>"/>
                    <input id="fUserId" name="userId" type="hidden"
                           value="<?php echo osc_esc_html(Params::getParam('userId')); ?>"/>
                    <?php if ($withFilters) { ?>
                        <a id="btn-hide-filters" href="<?php echo osc_admin_base_url(true) . '?page=users'; ?>"
                           class="btn btn-dim"><?php _e('Reset filters'); ?></a>
                    <?php } ?>
                    <a data-bs-toggle="modal" data-bs-target="#display-filters" href="#" class="btn btn-dim <?php if ($withFilters) {
                        echo 'btn-red';
                                                                                                            } ?>" title="<?php _e('Show filters'); ?>"><i class="bi bi-filter"></i>
                    </a>
                    <button type="submit" class="btn btn-primary" title="<?php echo osc_esc_html(__('Find')); ?>">
                        <i class="bi bi-search"></i>
                    </button>
                </fieldset>
            </form>
        </div>
        <form id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="users"/>

            <div id="bulk-actions">
                <div class="input-group input-group-sm">
                    <?php osc_print_bulk_actions('bulk_actions', 'action', __get('bulk_options'),
                                                 'select-box-extra'); ?>
                    <input type="submit" id="bulk_apply" class="btn btn-primary" value="<?php echo osc_esc_html(__('Apply')); ?>"/>
                </div>
            </div>
            <div class="table-contains-actions shadow-sm">
                <table class="table" cellpadding="0" cellspacing="0">
                    <thead>
                    <tr class="table-secondary">
                        <?php foreach ($columns as $k => $v) {
                            if ($direction === 'desc') {
                                echo '<th class="col-' . $k . ' ' . ($sort == $k ? ('sorting_desc') : '') . '">' . $v . '</th>';
                            } else {
                                echo '<th class="col-' . $k . ' ' . ($sort == $k ? ('sorting_asc') : '') . '">' . $v . '</th>';
                            }
                        } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (count($rows) > 0) { ?>
                        <?php foreach ($rows as $key => $row) { ?>
                            <tr class="<?php echo implode(' ',
                                                          osc_apply_filter('datatable_user_class', array(), $aRawRows[$key], $row)); ?>">
                                <?php foreach ($row as $k => $v) { ?>
                                    <td class="col-<?php echo $k; ?>" data-col-name="<?php echo ucfirst($k); ?>"><?php echo $v; ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9" class="text-center">
                                <p><?php _e('No data available in table'); ?></p>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div id="table-row-actions"></div> <!-- used for table actions -->
            </div>
        </form>
    </div>
<?php
function showingResults()
{
    $aData = __get('aData');
    echo '<ul class="showing-results"><li><span>' . osc_pagination_showing((Params::getParam('iPage') - 1)
                                                                           * $aData['iDisplayLength'] + 1,
                                                                           ((Params::getParam('iPage') - 1) * $aData['iDisplayLength'])
                                                                           + count($aData['aRows']),
                                                                           $aData['iTotalDisplayRecords'], $aData['iTotalRecords'])
         . '</span></li></ul>';
}


osc_add_hook('before_show_pagination_admin', 'showingResults');
osc_show_pagination_admin($aData);
?>
    <form method="get" action="<?php echo osc_admin_base_url(true); ?>" id="display-filters"
          class="modal fade nocsrf"
          data-bs-backdrop="static"
          data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel"><?php _e('Filters') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="page" value="users"/>
                    <input type="hidden" name="iDisplayLength" value="<?php echo $iDisplayLength; ?>"/>
                    <input type="hidden" name="sort" value="<?php echo $sort; ?>"/>
                    <input type="hidden" name="direction" value="<?php echo $direction; ?>"/>
                    <div class="form-horizontal">
                        <div class="row row-cols-lg-2">
                            <div class="col-lg-6">
                                <div class="row-wrapper">
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('Email'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <input id="s_email" name="s_email" type="text"
                                                   value="<?php echo osc_esc_html(Params::getParam('s_email')); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('Name'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <input id="s_name" name="s_name" type="text"
                                                   value="<?php echo osc_esc_html(Params::getParam('s_name')); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('Username'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <input id="s_username" name="s_username" type="text"
                                                   value="<?php echo osc_esc_html(Params::getParam('s_username')); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('Active'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <select id="b_active" name="b_active">
                                                <option value="" <?php echo((Params::getParam('b_active') == '')
                                                    ? 'selected="selected"' : '') ?>><?php _e('Choose an option'); ?></option>
                                                <option value="1" <?php echo((Params::getParam('b_active') == '1')
                                                    ? 'selected="selected"' : '') ?>><?php _e('ON'); ?></option>
                                                <option value="0" <?php echo((Params::getParam('b_active') == '0')
                                                    ? 'selected="selected"' : '') ?>><?php _e('OFF'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row-wrapper">
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('Country'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <input id="countryName" name="countryName" type="text"
                                                   value="<?php echo osc_esc_html(Params::getParam('countryName')); ?>"/>
                                            <input id="countryId" name="countryId" type="hidden"
                                                   value="<?php echo osc_esc_html(Params::getParam('countryId')); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('Region'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <input id="region" name="region" type="text"
                                                   value="<?php echo osc_esc_html(Params::getParam('region')); ?>"/>
                                            <input id="regionId" name="regionId" type="hidden"
                                                   value="<?php echo osc_esc_html(Params::getParam('regionId')); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('City'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <input id="city" name="city" type="text"
                                                   value="<?php echo osc_esc_html(Params::getParam('city')); ?>"/>
                                            <input id="cityId" name="cityId" type="hidden"
                                                   value="<?php echo osc_esc_html(Params::getParam('cityId')); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-label">
                                            <?php _e('Block'); ?>
                                        </div>
                                        <div class="form-controls">
                                            <select id="b_enabled" name="b_enabled">
                                                <option value="" <?php echo((Params::getParam('b_enabled') == '')
                                                    ? 'selected="selected"' : '') ?>><?php _e('Choose an option'); ?></option>
                                                <option value="0" <?php echo((Params::getParam('b_enabled') == '0')
                                                    ? 'selected="selected"' : '') ?>><?php _e('ON'); ?></option>
                                                <option value="1" <?php echo((Params::getParam('b_enabled') == '1')
                                                    ? 'selected="selected"' : '') ?>><?php _e('OFF'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="wrapper">
                        <input id="show-filters" type="submit" value="<?php echo osc_esc_html(__('Apply filters')); ?>"
                               class="btn btn-success btn-sm"/>
                        <a class="btn btn-warning btn-sm"
                           href="<?php echo osc_admin_base_url(true) . '?page=users'; ?>"><?php _e('Reset filters'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <form id="deleteModal" method="get" action="<?php echo osc_admin_base_url(true); ?>"
          class="modal fade static">
        <input type="hidden" name="page" value="users"/>
        <input type="hidden" name="action" value="delete"/>
        <input type="hidden" name="id[]" value=""/>
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <?php echo osc_esc_html(__('Delete user')); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php _e('Are you sure you want to delete this user?'); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><?php _e('Cancel'); ?></button>
                    <button id="deleteSubmit" class="btn btn-sm btn-red" type="submit">
                        <?php echo __('Delete'); ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
    <div id="bulkActionsModal" class="modal fade static" tabindex="-1" aria-labelledby="bulkActionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkActionsModalLabel"><?php _e('Bulk actions'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><?php _e('Cancel'); ?></button>
                    <button id="bulkActionsSubmit" onclick="bulkActionsSubmit()"
                            class="btn btn-sm btn-red"><?php echo osc_esc_html(__('Delete')); ?></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // check_all bulkactions
            $("#check_all").change(function () {
                var isChecked = $(this).prop("checked");
                $('.col-bulkactions input').each(function () {
                    this.checked = isChecked == 1;
                });
            });

        });

        function delete_dialog(id) {
            var deleteModal = document.getElementById("deleteModal")
            deleteModal.querySelector("input[name='id[]']").value = id;
            (new bootstrap.Modal(document.getElementById('deleteModal'))).toggle()
            return false;
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function () {

            $('#countryName').attr("autocomplete", "off");
            $('#region').attr("autocomplete", "off");
            $('#city').attr("autocomplete", "off");

            $('#countryId').change(function () {
                $('#regionId').val('');
                $('#region').val('');
                $('#cityId').val('');
                $('#city').val('');
            });

            $('#countryName').on('keyup.autocomplete', function () {
                $('#countryId').val('');
                $(this).autocomplete({
                    source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_countries",
                    minLength: 0,
                    select: function (event, ui) {
                        $('#countryId').val(ui.item.id);
                        $('#regionId').val('');
                        $('#region').val('');
                        $('#cityId').val('');
                        $('#city').val('');
                    }
                });
            });

            $('#region').on('keyup.autocomplete', function () {
                $('#regionId').val('');
                if ($('#countryId').val() != '' && $('#countryId').val() != undefined) {
                    var country = $('#countryId').val();
                } else {
                    var country = $('#country').val();
                }
                $(this).autocomplete({
                    source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_regions&country=" + country,
                    minLength: 2,
                    select: function (event, ui) {
                        $('#cityId').val('');
                        $('#city').val('');
                        $('#regionId').val(ui.item.id);
                    }
                });
            });

            $('#city').on('keyup.autocomplete', function () {
                $('#cityId').val('');
                if ($('#regionId').val() != '' && $('#regionId').val() != undefined) {
                    var region = $('#regionId').val();
                } else {
                    var region = $('#region').val();
                }
                $(this).autocomplete({
                    source: "<?php echo osc_base_url(true); ?>?page=ajax&action=location_cities&region=" + region,
                    minLength: 2,
                    select: function (event, ui) {
                        $('#cityId').val(ui.item.id);
                    }
                });
            });
        });
    </script>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>