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
 * The Attendance Invoices Report.
 *
 * @package     report_invoices
 * @category    admin
 * @copyright   2022 Lukas Celinak, Edumood,  <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use core\report_helper;

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();
$context = context_system::instance();
require_capability('report/invoices:view', $context);

$download = optional_param('download', '', PARAM_ALPHA);

// Paging params for paging bars.
$page = optional_param('page', 0, PARAM_INT); // Which page to show.
$pagesize = optional_param('perpage', 25, PARAM_INT); // How many per page.

$url = new moodle_url('/report/invoices/index.php');
$PAGE->set_url($url);
$PAGE->set_context($context);
admin_externalpage_setup('report_invoices', '', null, '', array('pagelayout' => 'report'));
$PAGE->set_title(get_string('pluginname', 'report_invoices'));
$PAGE->set_heading(get_string('pluginname', 'report_invoices'));

$search = new stdClass();
$mform = new \report_invoices\form\search();
/** @var cache_session $cache */
$cache = cache::make_from_params(cache_store::MODE_SESSION, 'report_invoices', 'search');
if ($cachedata = $cache->get('data')) {
    $mform->set_data($cachedata);
}

// Check if we have a form submission, or a cached submission.
$data = ($mform->is_submitted() ? $mform->get_data() : fullclone($cachedata));
if ($data instanceof stdClass) {
    $search->from = !empty($data->datefrom)?$data->datefrom:null;
    $search->to = !empty($data->dateto)?$data->dateto:null;
    $search->showall=!empty($data->showall)?1:null;
    // Cache form submission so that it is preserved while paging through the report.
    unset($data->submitbutton);
    $cache->set('data', $data);
}

$mtable = new \report_invoices\table\invoices_issued_table('invoicestable');
$mtable->is_downloading($download, get_string('pluginname', 'report_invoices') . " - " . date('d-M-Y g-i a'), 'invoicesexport');
$mtable->define_baseurl($url);


if (!$mtable->is_downloading()) {
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('pluginname', 'report_invoices'));
    $mform->display();
}
    $mtable->init_table($search);
    ob_start();
    $mtable->out($pagesize, false);
    $mtablehtml = ob_get_contents();
    ob_end_clean();


if (!$mtable->is_downloading()) {
    echo html_writer::tag(
            'p',
            get_string('userstotal', 'report_invoices', $mtable->totalrows),
            [
                'data-region' => 'reportinvoicestable-count',
            ]
    );
}

if (!$mtable->is_downloading()) {
    echo $mtablehtml;
}

if (!$mtable->is_downloading()) {
    echo $OUTPUT->footer();
}