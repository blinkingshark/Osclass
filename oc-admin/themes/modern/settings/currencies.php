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
         . __("Add new currencies or edit existing currencies so users can publish listings in their country's currency.")
         . '</p>';
}

osc_add_hook('help_box', 'addHelp');

function customPageHeader()
{
    ?>
    <h1><?php _e('Settings'); ?>
        <a class="ms-1 bi bi-question-circle-fill float-right" data-bs-target="#help-box" data-bs-toggle="collapse"
           href="#help-box"></a>
        <a href="<?php echo osc_admin_base_url(true) . '?page=settings&action=currencies&type=add'; ?>"
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
    return sprintf(__('Currencies &raquo; %s'), $string);
}

osc_add_filter('admin_title', 'customPageTitle');

$aCurrencies = __get('aCurrencies');

$aData = array();
foreach ($aCurrencies as $currency) {
    $row   = array();
    $row[] = '<input type="checkbox" name="code[]" value="' . osc_esc_html($currency['pk_c_code']) . '" />';

    $options   = array();
    $options[] =
        '<a onclick="return delete_dialog(\'' . $currency['pk_c_code'] . '\');" href="' . osc_admin_base_url(true)
        . '?page=settings&amp;action=currencies&amp;type=delete&amp;code=' . $currency['pk_c_code'] . '">'
        . __('Delete') . '</a>';
    $options[] = '<a href="' . osc_admin_base_url(true) . '?page=settings&amp;action=currencies&amp;type=edit&amp;code='
                 . $currency['pk_c_code'] . '">' . __('Edit') . '</a>';

    $row[]   = $currency['pk_c_code'] . ' (' . implode(' &middot; ', $options) . ')';
    $row[]   = $currency['s_name'];
    $row[]   = $currency['s_description'];
    $aData[] = $row;
}

osc_current_admin_theme_path('parts/header.php'); ?>
    <h2 class="render-title"><?php _e('Currencies'); ?></h2>
    <div class="relative">
        <div id="currencies-toolbar" class="table-toolbar">
        </div>
        <form class="" id="datatablesForm" action="<?php echo osc_admin_base_url(true); ?>" method="post">
            <input type="hidden" name="page" value="settings"/>
            <input type="hidden" name="action" value="currencies"/>
            <input type="hidden" name="type" value="delete"/>
            <div id="bulk-actions">
                <div class="input-group input-group-sm">
                    <select id="bulk_actions" name="bulk_actions" class="select-box-extra form-select">
                        <option value=""><?php _e('Bulk actions'); ?></option>
                        <option value="delete_all"
                                data-dialog-content="<?php printf(
                                    __('Are you sure you want to %s the selected currencies?'),
                                    strtolower(__('Delete'))
                                ); ?>"><?php _e('Delete'); ?>
                        </option>
                    </select> <input type="submit" id="bulk_apply" class="btn btn-primary"
                                     value="<?php echo osc_esc_html(__('Apply')); ?>"/>
                </div>
            </div>
            <table class="table" cellpadding="0" cellspacing="0">
                <thead>
                <tr class="table-secondary">
                    <th class="col-bulkactions"><input id="check_all" type="checkbox"/></th>
                    <th><?php _e('Code'); ?></th>
                    <th><?php _e('Name'); ?></th>
                    <th><?php _e('Description'); ?></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($aData as $array) { ?>
                    <tr>
                        <?php foreach ($array as $key => $value) { ?>
                            <td <?php if ($key == 0) {
                                echo 'class="col-bulkactions"';
                                } elseif ($key === 1) {
                                    echo 'data-col-name ='. __('Code');
                                } elseif ($key === 2) {
                                    echo 'data-col-name ='. __('Name');
                                } elseif ($key === 3) {
                                    echo 'data-col-name ='. __('Description');
                                } else {
                                    echo 'data-col-name="'.ucfirst($key).'"';
                                } ?>>
                            <?php echo $value; ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </form>
    </div>
    <form id="deleteModal" method="get" action="<?php echo osc_admin_base_url(true); ?>"
          class="modal fade static">
        <input type="hidden" name="page" value="settings"/>
        <input type="hidden" name="action" value="currencies"/>
        <input type="hidden" name="type" value="delete"/>
        <input type="hidden" name="code" value=""/>
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <?php _e('Delete currency'); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php _e('Are you sure you want to delete this currency?'); ?>
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
            deleteModal.querySelector("input[name='code']").value = id;
            (new bootstrap.Modal(document.getElementById('deleteModal'))).toggle()
            return false;
        }
    </script>
<?php osc_current_admin_theme_path('parts/footer.php'); ?>