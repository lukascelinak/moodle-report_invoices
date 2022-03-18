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
 *  The Invoices Report functions library file.
 *
 * @package     report_invoices
 * @category    admin
 * @copyright   2022 Lukas Celinak, Edumood s.r.o. <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->dirroot . '/user/lib.php');

/**
 * Count records
 *
 * @param flexible_table $mtable
 * @param stdClass $search
 * @return int Count of rows
 * @throws dml_exception
 */
function report_invoices_count_data($mtable, $search) {
    global $DB;
    $params=array();
    $select = "SELECT ";
    $what = "a.id, COUNT(*) OVER () AS total ";
    $from = "FROM {attendance_sessions} AS att ";
    $join = "LEFT JOIN {user} AS u ON att.lasttakenby = u.id
    JOIN {attendance} AS a ON att.attendanceid = a.id
    JOIN {course} AS c ON a.course = c.id
    LEFT OUTER JOIN {context} AS ctx ON c.id = ctx.instanceid
    LEFT OUTER JOIN {role_assignments} AS ra ON ctx.id = ra.contextid AND (ra.roleid = '3' OR ra.roleid = '4')
    LEFT OUTER JOIN {user} AS rau ON ra.userid = rau.id
    JOIN {user_info_data} AS uid ON rau.id = uid.userid AND uid.fieldid = '19'
    JOIN {data_content} as dc ON c.shortname = dc.content
    LEFT OUTER JOIN {data_content} as dck ON dc.recordid = dck.recordid AND dck.fieldid = '1340'
    LEFT OUTER JOIN {course_modules} cm ON cm.instance = a.id AND cm.module = '23' ";

    $where = "WHERE ctx.contextlevel = '50' AND att.description NOT LIKE '%Status \"A\"%' AND dck.content = 'Regular' ";

    $where .= (property_exists($search, "datefrom") && !empty($search->datefrom))&&(!property_exists($search, "dateto"))
        ? "AND att.sessdate > :datefrom {$search->datefrom} " : "";

    $where .= (property_exists($search, "dateto") && !empty($search->dateto))&&(!property_exists($search, "datefrom"))
        ? "AND att.sessdate < {$search->dateto} " : "";

    $where .= (property_exists($search, "datefrom") && !empty($search->datefrom)&&property_exists($search, "dateto")&&!empty($search->dateto))
        ? "AND att.sessdate BETWEEN {$search->datefrom} AND {$search->dateto} " : "";

    $groupby = "GROUP BY c.shortname LIMIT 1";

    $sql = $select .$what. $from . $join . $where . $groupby;

    return property_exists($search, "dateto")&& property_exists($search, "datefrom") ? $DB->count_records_sql($sql,$params):0;
}

/**
 * Query for get data for flexible table
 *
 * @param flexible_table $mtable
 * @param stdClass $search
 * @return array Invoices rows prepared for export via dataformat_xmlinvoices
 * @throws dml_exception
 */
function report_invoices_get_data($mtable, $search) {
    global $DB;
    $params=array();
    $select = "SELECT ";
    $what = "att.id AS sessionid, 
             c.id as courseid,
             ctx.id as cmid,
             c.fullname as coursefullname,
             c.shortname as courseshortname,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '1333') as kodzbozi,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '1334') as nazevzbozi,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '15') as nazev,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '1335') as cenamj,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '78') as ico,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '1336') as dic,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '77') as ulice,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '1337') as psc,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '1338') as obec,
             (SELECT dca.content FROM {data_content} AS dca WHERE dca.recordid = dc.recordid AND dca.fieldid = '1339') as podrobnosti,
             dck.content as payment,
             count(*) as pocet ";
    $from = "FROM {attendance_sessions} AS att ";
    $join = "LEFT JOIN {user} AS u ON att.lasttakenby = u.id
    LEFT JOIN {attendance} AS a ON att.attendanceid = a.id
    LEFT JOIN {course} AS c ON a.course = c.id
    LEFT OUTER JOIN {context} AS ctx ON c.id = ctx.instanceid
    LEFT OUTER JOIN {role_assignments} AS ra ON ctx.id = ra.contextid AND (ra.roleid = '3' OR ra.roleid = '4')
    LEFT OUTER JOIN {user} AS rau ON ra.userid = rau.id
    JOIN {user_info_data} AS uid ON rau.id = uid.userid AND uid.fieldid = '19'
    JOIN {data_content} as dc ON c.shortname = dc.content
    LEFT OUTER JOIN {data_content} as dck ON dc.recordid = dck.recordid AND dck.fieldid = '1340'
    LEFT OUTER JOIN {course_modules} cm ON cm.instance = a.id AND cm.module = '23' ";

    $where = "WHERE ctx.contextlevel = '50' AND att.description NOT LIKE '%Status \"A\"%' AND dck.content = 'Regular' ";

    $where .= (property_exists($search, "datefrom") && !empty($search->datefrom))&&(!property_exists($search, "dateto"))
        ? "AND att.sessdate > {$search->datefrom} " : "";

    $where .= (property_exists($search, "dateto") && !empty($search->dateto))&&(!property_exists($search, "datefrom"))
        ? "AND att.sessdate < {$search->dateto} " : "";

    $where .= (property_exists($search, "datefrom") && !empty($search->datefrom)&&property_exists($search, "dateto")&&!empty($search->dateto))
        ? "AND att.sessdate BETWEEN {$search->datefrom} AND {$search->dateto} " : "";

    $groupby = "GROUP BY c.shortname ";

    $sort=$mtable->get_sql_sort() ;
    $orderby = "ORDER BY {$sort} ";
    $sql = $select . $what . $from . $join . $where . $groupby . $orderby;
    return $DB->get_records_sql($sql, $params, $mtable->get_page_start(), $mtable->get_page_size());
}

/**
 * Report table view function, preparing data for export
 *
 * @param flexible_table $mtable
 * @param stdClass $search
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function report_invoices_get_table($mtable, $search) {
    global $CFG, $DB;

    // Columns definition
    $columns = ['smer'];
    !$mtable->is_downloading()? $columns[]="course":false;
    $columns[]="nazev";
    $columns[]="ico";
    $columns[]="dic";
    $columns[]="ulice";
    $columns[]="psc";
    $columns[]="obec";
    $columns[]="stat";
    $columns[]="mena";
    $columns[]="kodzbozi";
    $columns[]="nazevzbozi";
    $columns[]="podrobnosti";
    $columns[]="cenamj";
    $columns[]="pocet";
    $columns[]="castka";
    $columns[]="vcetnedph";
    $columns[]="datum_zd_pl";
    $columns[]="datum_vystaveni";
    $columns[]="datum_splatnosti";

    // Headers definition, for download you can name headers in langstrings and make them compatible with import
    if( $mtable->is_downloading()){
        $headers = [ get_string('dwn_inout','report_invoices')];
        $headers[]=get_string('dwn_name','report_invoices');
        $headers[]=get_string('dwn_ico','report_invoices');
        $headers[]=get_string('dwn_vat','report_invoices');
        $headers[]=get_string('dwn_street','report_invoices');
        $headers[]=get_string('dwn_zip','report_invoices');
        $headers[]=get_string('dwn_city','report_invoices');
        $headers[]=get_string('dwn_country','report_invoices');
        $headers[]=get_string('dwn_currency','report_invoices');
        $headers[]=get_string('dwn_itemcode','report_invoices');
        $headers[]=get_string('dwn_itemname','report_invoices');
        $headers[]=get_string('dwn_description','report_invoices');
        $headers[]=get_string('dwn_itemprice','report_invoices');
        $headers[]=get_string('dwn_quantity','report_invoices');
        $headers[]=get_string('dwn_amount','report_invoices');
        $headers[]=get_string('dwn_totalamount','report_invoices');
        $headers[]=get_string('dwn_taxdate','report_invoices');
        $headers[]=get_string('dwn_issuancedate','report_invoices');
        $headers[]=get_string('dwn_duedate','report_invoices');
    }else{
        $headers = [ get_string('actions','report_invoices')];
        $headers[]=get_string('course');
        $headers[]=get_string('name','report_invoices');
        $headers[]=get_string('ico','report_invoices');
        $headers[]=get_string('vat','report_invoices');
        $headers[]=get_string('street','report_invoices');
        $headers[]=get_string('zip','report_invoices');
        $headers[]=get_string('city');
        $headers[]=get_string('country');
        $headers[]=get_string('currency','report_invoices');
        $headers[]=get_string('itemcode','report_invoices');
        $headers[]=get_string('itemname','report_invoices');
        $headers[]=get_string('description','report_invoices');
        $headers[]=get_string('itemprice','report_invoices');
        $headers[]=get_string('quantity','report_invoices');
        $headers[]=get_string('amount','report_invoices');
        $headers[]=get_string('totalamount','report_invoices');
        $headers[]=get_string('taxdate','report_invoices');
        $headers[]=get_string('issuancedate','report_invoices');
        $headers[]=get_string('duedate','report_invoices');}

    $mtable->define_columns($columns);
    $mtable->define_headers($headers);
    $mtable->set_attribute('class', 'generaltable');

    $count = report_invoices_count_data($mtable,$search);
    if (!$mtable->is_downloading()) {
        echo get_string("count", "report_invoices", $count);
    }

    $mtable->sortable(true, 'nazev', SORT_DESC);

    if ($mtable->is_downloading() && $count) {
        $mtable->pagesize($count, $count);
    } else {
        $mtable->pagesize(20, $count);
    }

    $mtable->setup();

    $invoicesdata = report_invoices_get_data($mtable, $search);

    foreach ($invoicesdata as $invoicedata) {
        $config=get_config('report_invoices');

        $courseurl = new moodle_url('/course/view.php', array('id' => $invoicedata->courseid));
        $coursebtn = html_writer::link($courseurl,$invoicedata->coursefullname);

        $attendanceurl=new moodle_url('/mod/attendance/manage.php', array('id' => $invoicedata->cmid));
        $edit=get_string('edit','report_invoices');
        $attendancebtn=html_writer::link($attendanceurl,"<img src=\"{$CFG->wwwroot}/pix/t/edit.png\" title=\"{$edit}\" />");

        $data= $mtable->is_downloading()? array("V"):array($attendancebtn);
            !$mtable->is_downloading()? $data[]=$coursebtn:false;
            $data[] = $invoicedata->nazev;
            $data[] = $invoicedata->ico;
            $data[] = $invoicedata->dic;
            $data[] = $invoicedata->ulice;
            $data[] = $invoicedata->psc;
            $data[] = $invoicedata->obec;
            $data[] = $config->country;
            $data[] = $config->currency;
            $data[] = $invoicedata->kodzbozi;
            $data[] = $invoicedata->nazevzbozi;
            $data[] = date("m/Y",$search->dateto)." ".$invoicedata->podrobnosti;
            $data[] = $invoicedata->cenamj;
            $data[] = $invoicedata->pocet;
            $data[] = round($invoicedata->cenamj*$invoicedata->pocet, 2);
            $data[] = round(($invoicedata->cenamj+($invoicedata->cenamj/100)*$config->vatvalue)*$invoicedata->pocet, 2);
            $data[] = date("Y-m-t",$search->dateto);
            $data[] = date("Y-m-d",time());
            $data[] = date("Y-m-d",time()+$config->duedatevalue);
        $mtable->add_data($data);
    }
    $mtable->finish_output();
}
