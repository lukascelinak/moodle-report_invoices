<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * The Attendance Invoices Report plugin administration.
 *
 * @package     report_invoices
 * @category    admin
 * @copyright   2022 Lukas Celinak, Edumood,  <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$ADMIN->add('reports', new admin_externalpage('report_invoices', get_string('pluginname',
                        'report_invoices'),
                "$CFG->wwwroot/report/invoices/index.php",
                'report/invoices:view'));

if($categories = $DB->get_records('user_info_category')){
    $categoriesarray = [];
    foreach ($categories as $category) {
        $categoriesarray[$category->id] = $category->name;
    }

    $settings->add(new admin_setting_configselect('report_invoices/profilecustomfields', get_string('profilecustomfields', 'report_invoices'),
        get_string('profilecustomfields_help', 'report_invoices'), NULL,
        $categoriesarray));
}


$settings->add(new admin_setting_configduration('report_invoices/timelimit',
    get_string('timelimit', 'report_invoices'),
    get_string('timelimit_help', 'report_invoices'), 1800,60));
