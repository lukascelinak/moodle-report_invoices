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
 * The Invoices Report plugin administration.
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

$settings->add(new admin_setting_configtext('report_invoices/country', get_string('country', 'report_invoices'),
    get_string('country_help', 'report_invoices'), "CZ - Česká republika", PARAM_TEXT));

$settings->add(new admin_setting_configtext('report_invoices/currency', get_string('currency', 'report_invoices'),
    get_string('currency_help', 'report_invoices'), 'CZK', PARAM_TEXT));

$settings->add(new admin_setting_configtext('report_invoices/vatvalue', get_string('vatvalue', 'report_invoices'),
    get_string('vatvalue_help', 'report_invoices'), 21, PARAM_INT));

$settings->add(new admin_setting_configduration('report_invoices/duedatevalue', get_string('duedate', 'report_invoices'),
    get_string('duedate_help', 'report_invoices'), 1209600, 86400));
