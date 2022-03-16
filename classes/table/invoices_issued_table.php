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
 * The Invoices Report table class.
 *
 * @package     report_invoices
 * @copyright   2022 Lukas Celinak <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
declare(strict_types=1);

namespace report_invoices\table;

use context;
use moodle_url;
use core_user\output\status_field;

defined('MOODLE_INTERNAL') || die;

global $CFG;

require_once($CFG->libdir . '/tablelib.php');
require_once($CFG->dirroot . '/user/lib.php');
require_once($CFG->dirroot . '/blocks/dedication/dedication_lib.php');

/**
 * Class for the displaying the table.
 *
 * @package     report_invoices
 * @copyright   2022 Lukas Celinak, Edumood,  <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class invoices_issued_table extends \table_sql {

    public $search;
    public $gradebookroles;
    private $profilefields;
    /**
     * Sets up the table.
     *
     * @param string|array $search The search string(s)
     */
    public function init_table($search) {
        global $DB, $CFG;
        $this->context = \context_system::instance();
        $this->gradebookroles = $CFG->gradebookroles;
        $this->search = $search;
        $this->profilefields = $DB->get_records('user_info_field', array('categoryid' => get_config("report_invoices", 'profilecustomfields')),"sortorder");
    }

    /**
     * Render the table.
     *
     * @param int $pagesize Size of page for paginated displayed table.
     * @param bool $useinitialsbar Whether to use the initials bar which will only be used if there is a fullname column defined.
     * @param string $downloadhelpbutton
     */
    public function out($pagesize, $useinitialsbar, $downloadhelpbutton = '') {
        global $DB;
        $this->downloadable = true;
        $this->set_attribute('class', 'table-bordered');


        $columns = ['nazev'];
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

        if($this->is_downloading()){
            $headers = [ get_string('dwn_name','report_invoices')];
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
            $headers = [ get_string('name','report_invoices')];
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

        $extrafields = [];

        $this->define_columns($columns);
        $this->define_headers($headers);

      //  $this->no_sorting('datum_splatnosti');
      //  $this->no_sorting('datum_vystaveni');

        // Make this table sorted by last name by default.
        $this->sortable(true, 'nazev');
        $this->extrafields = $extrafields;

        parent::out($pagesize, $useinitialsbar, $downloadhelpbutton);
    }

    /**
     * Generate the fullname column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_nazev($data) {
            return $data->nazev;
    }

    /**
     * Generate the fullname column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_ico($data) {
        return $data->ico;
    }

    /**
     * Generate the fullname column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_dic($data) {
        return $data->dic;
    }

    /**
     * Generate the last access column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_ulice($data) {
        return $data->ulice;
    }

    /**
     * Generate the email column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_mena($data) {
        return $data->mena;
    }

    /**
     * Generate total duration of all sessions column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_psc($data) {
        return $data->psc;
    }

    /**
     * Generate visits count column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_obec($data) {
        return $data->obec;
    }

    /**
     * Generate the total dedication column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_stat($data) {
        return $data->stat;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_kodzbozi($data) {
        return $data->kodzbozi;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_nazevzbozi($data) {
        return $data->nazevzbozi;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_podrobnosti($data) {
        return $data->podrobnosti;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_cenamj($data) {
        return $data->cenamj;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_pocet($data) {
        return $data->pocet;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_castka($data) {
        return $data->castka;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_vcetnedph($data) {
        return $data->vcetnedph;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_datum_zd_pl($data) {
        return $data->datum_zd_pl;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_datum_vystaveni($data) {
        return $data->datum_vystaveni;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_datum_splatnosti($data) {
        return $data->datum_splatnosti;
    }



    /**
     * This function is used for the extra user fields.
     *
     * These are being dynamically added to the table so there are no functions 'col_<userfieldname>' as
     * the list has the potential to increase in the future and we don't want to have to remember to add
     * a new method to this class. We also don't want to pollute this class with unnecessary methods.
     *
     * @param string $colname The column name
     * @param \stdClass $data
     * @return string
     */
    public function other_cols($colname, $data){
        global $DB, $OUTPUT;
        // Do not process if it is not a part of the extra fields.
        if (!in_array($colname, $this->extrafields)) {
            return '';
        }

    }

    /**
     * Query the database for results to display in the table.
     *
     * @param int $pagesize size of page for paginated displayed table.
     * @param bool $useinitialsbar do you want to use the initials bar.
     */
    public function query_db($pagesize, $useinitialsbar = true) {
        global $DB;

        //Count all users.
        $total = $this->count_data();

        if ($this->is_downloading()) {
            $this->pagesize($total, $total);
        } else {
            $this->pagesize($pagesize, $total);
        }

        //Get users data.
        $rawdata = $this->get_data($this->get_sql_sort(), $this->get_page_start(), $this->get_page_size());

        $this->rawdata = [];
        foreach ($rawdata as $user) {
            $this->rawdata[$user->id] = $user;
        }

        // Set initial bars.
        if ($useinitialsbar) {
            $this->initialbars(true);
        }
    }

    /**
     * Override the table show_hide_link to not show for select column.
     *
     * @param string $column the column name, index into various names.
     * @param int $index numerical index of the column.
     * @return string HTML fragment.
     */
    protected function show_hide_link($column, $index) {
        return '';
    }

    /**
     * Guess the base url for the participants table.
     */
    public function guess_base_url(): void {
        $this->baseurl = new moodle_url('/report/invoices/index.php');
    }

    /**
     * Query users for table.
     */
    public function count_data() {
        global $DB;
        $params=array();
        $select = "SELECT COUNT(u.id) ";
        $from = "FROM {user} u ";
        $join = "";
        $where = "";
        $groupby = "";
        $sql = $select . $from . $join . $where . $groupby;
        return $DB->count_records_sql($sql,$params);
    }

    /**
     * Query users for table.
     */
    public function get_data($sort, $start, $size) {
        global $DB;
        $params=array();
        $select = "SELECT ";
        $what = "u.id,
         u.firstname as nazev,
         u.firstname as ico,
         u.firstname as dic,
         u.firstname as ulice,
         u.firstname as psc,
         u.firstname as obec,
         u.firstname as stat,
         u.firstname as mena,
         u.firstname as kodzbozi,
         u.firstname as nazevzbozi,
         u.firstname as podrobnosti,
         u.firstname as cenamj,
         u.firstname as pocet,
         u.firstname as castka,
         u.firstname as vcetnedph,
         u.firstname as datum_zd_pl,
         u.firstname as datum_vystaveni,
         u.firstname as datum_splatnosti ";
        $from = "FROM {user} u ";
        $where = "";
        $groupby = "";
        $join = "";
        $orderby = "ORDER BY {$sort} ";
        $sql = $select . $what . $from . $join . $where . $groupby . $orderby;
        return $DB->get_records_sql($sql, $params, $start, $size);
    }

    /**
     * Get all user courses and user enrolment and user completion data
     * @param $userid
     * @return array
     * @throws dml_exception
     */
    public function get_user_courses($userid,$completed=null){
        global $DB,$CFG;
        $sqlstart = "SELECT ";
        $sqlwhat = "c.id,MIN(c.startdate) AS startdate ";
        $sqlfrom = "FROM {user_enrolments} ue ";
        $sqlinner = "LEFT JOIN {user} u ON ue.userid = u.id ";
        $sqlinner .= "LEFT JOIN {enrol} e ON ue.enrolid = e.id ";
        $sqlinner .= "LEFT JOIN {course} c ON c.id = e.courseid AND c.enablecompletion = '1' ";
        $sqlwhere = "WHERE u.id=:userid ";
        $sqlgroup = "GROUP BY c.id ";
        $sqlinner .= " JOIN (
                           SELECT DISTINCT ra.userid
                             FROM {role_assignments} ra
                            WHERE ra.roleid IN ($CFG->gradebookroles)
                       ) rainner ON rainner.userid = u.id ";

        $sqlwhere.= $completed? "AND ((SELECT COUNT(cc.coursemoduleid) 
                                            FROM {course_modules_completion} cc 
                                            LEFT JOIN {course_modules} cm ON cc.coursemoduleid = cm.id 
                                            WHERE cm.completion > 0 AND cc.userid = ue.userid AND cm.course=c.id 
                                            AND (cc.completionstate=:completionstate OR cc.completionstate=:completionstate1)) 
                                            >= (SELECT COUNT(cm.id) 
                                            FROM {course_modules} cm 
                                            WHERE cm.completion > 0 AND cm.course=c.id AND cm.visible = 1)) ":"";
        $sqlorder = "";
        $params=['userid'=>$userid];
        $params['completionstate']=COMPLETION_COMPLETE;
        $params['completionstate1']=COMPLETION_COMPLETE_PASS;
        $sql = $sqlstart . $sqlwhat . $sqlfrom . $sqlinner . $sqlwhere . $sqlgroup . $sqlorder;
        return $DB->get_records_sql($sql,$params);
    }

}
