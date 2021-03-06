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
 * The Invoices Report plugin strings are defined here.
 *
 * @package     report_invoices
 * @category    string
 * @copyright   2022 Lukas Celinak, Edumood,  <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Invoices Report';

/****************** The report Users Attendance Invoices strings ****************/
$string['eventreportviewed'] = 'Invoices Report viewed';
$string['firstacess'] = 'First Access Site Date';
$string['settingsheader'] = 'Invoices Report settings';
$string['settingsheaderdesc'] = 'Invoices Report settings';
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

$string['name'] = 'Name';
$string['ico'] = 'Id number';
$string['vat'] = 'VAT id number';
$string['zip'] = 'Zip code';
$string['street'] = 'Street';
$string['currency'] = 'Currency';
$string['itemcode'] = 'Item code';
$string['itemname'] = 'Item name';
$string['description'] = 'Item Description';
$string['itemprice'] = 'Item price';
$string['quantity'] = 'Quantitiy';
$string['amount'] = 'Amount';
$string['totalamount'] = 'Amount incl. tax';
$string['taxdate'] = 'Tax performance date';
$string['issuancedate'] = 'Issuance date';
$string['duedate'] = 'Due date';

$string['dwn_name'] = 'protistrana_lv_nazev';
$string['dwn_ico'] = 'protistrana_lv_ic';
$string['dwn_vat'] = 'protistrana_lv_dic';
$string['dwn_zip'] = 'protistrana_lv_psc';
$string['dwn_street'] = 'protistrana_lv_ulice';
$string['dwn_city'] = 'protistrana_lv_obec';
$string['dwn_country'] = 'protistrana_lv_stat';
$string['dwn_currency'] = 'mena';
$string['dwn_itemcode'] = 'polozka_lv_kodzbozi';
$string['dwn_itemname'] = 'polozka_lv_nazevzbozi';
$string['dwn_description'] = 'polozka_lv_podrobnosti';
$string['dwn_itemprice'] = 'polozka_lv_cenamj';
$string['dwn_quantity'] = 'polozka_lv_pocet';
$string['dwn_amount'] = 'polozka_lv_castka';
$string['dwn_totalamount'] = 'polozka_lv_vcetnedph';
$string['dwn_taxdate'] = 'datum_zd_pl';
$string['dwn_issuancedate'] = 'datum_vystaveni';
$string['dwn_duedate'] = 'datum_splatnosti';