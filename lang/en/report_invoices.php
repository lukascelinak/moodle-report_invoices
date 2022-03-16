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
 * The Attendance Invoices Report plugin strings are defined here.
 *
 * @package     report_invoices
 * @category    string
 * @copyright   2022 Lukas Celinak, Edumood,  <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Attendance Invoices Report';

/****************** The report Users Attendance Invoices strings ****************/
$string['eventreportviewed'] = 'Attendance Invoices Report viewed';
$string['firstacess'] = 'First Access Site Date';
$string['settingsheader'] = 'Attendance Invoices Report settings';
$string['settingsheaderdesc'] = 'Attendance Invoices Report settings';
$string['userstotal'] = '{$a} users loaded.';
$string['showall'] = 'Show only users with duration > 0';
$string['totalduration'] = 'Total duration on the platform';
$string['successfullogins'] = 'Successful logins';
$string['visits'] = 'Unique visits';
$string['profilecustomfields'] = 'Profile custom fields category';
$string['profilecustomfields_help'] = 'Select category or profile custom fields which would be shown in report, 
                                       order of columns is same as order of fields in category.';
$string['timelimit'] = 'Time limit';
$string['timelimit_help'] = 'Time limit for calculations.';