<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *  The Invoices Report index file.
 *
 * @package     report_invoices
 * @category    admin
 * @copyright   2022 Lukas Celinak, Edumood s.r.o. <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once('locallib.php');

ini_set('max_execution_time', '0');

require_login();

$download = optional_param('download', '', PARAM_ALPHA);
$action = optional_param('what', 'view', PARAM_CLEAN);
// Paging params for paging bars.
$page = optional_param('page', 0, PARAM_INT); // Which page to show.
$perpage = optional_param('perpage', 10, PARAM_INT); // How many per page.
$context = context_system::instance();

$url = new moodle_url('/report/invoices/index.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_context($context);

require_capability('report/invoices:view', $context);
admin_externalpage_setup('report_invoices', '', null, '', array('pagelayout'=>'report'));

$strcompletion = get_string('pluginname','report_invoices');

$mform = new \report_invoices\form\search();
$search = new stdClass();
/** @var cache_session $cache */
$cache = cache::make_from_params(cache_store::MODE_SESSION, 'report_invoices', 'search');
if ($cachedata = $cache->get('data')) {
    $mform->set_data($cachedata);
}

// Check if we have a form submission, or a cached submission.
$data = ($mform->is_submitted() ? $mform->get_data() : fullclone($cachedata));

if ($data instanceof stdClass) {
    $search->datefrom = !empty($data->datefrom)?$data->datefrom:null;
    $search->dateto = !empty($data->dateto)?$data->dateto:null;
    // Cache form submission so that it is preserved while paging through the report.
    unset($data->submitbutton);
    $cache->set('data', $data);
}

$mtable = new flexible_table('invoices');
$mtable->define_baseurl($PAGE->url);
$mtable->is_downloading($download, "report_invoices_".date('d-m-Y_H-i-s',time()), "report_invoices");

if (!$mtable->is_downloading()) {
   echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('laodinvoices', 'report_invoices'));

}
$PAGE->set_title($strcompletion);
$PAGE->set_heading($strcompletion);

if (!$mtable->is_downloading()) {
    $mform->display();
}

if(property_exists($search, "datefrom") && !empty($search->datefrom)&&property_exists($search, "dateto")&&!empty($search->dateto)){
report_invoices_get_table($mtable,$search);
}

if (!$mtable->is_downloading()) {
    echo $OUTPUT->footer();
}

$event = \report_invoices\event\report_viewed::create(array('context' => $context));
$event->trigger();