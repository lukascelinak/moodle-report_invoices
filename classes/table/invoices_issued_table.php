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
 * The Attendance Invoices Report table class.
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

        $headers = [ get_string('username',)];
        $columns = ['protistranalv1nazev'];

        $columns[]="protistranalv1ic";
        $headers[]=get_string('firstname');

        $columns[]="protistranalv1dic";
        $headers[]=get_string('firstname');

        if($this->is_downloading()){
            $columns[]="protistranalv1ulice";
            $headers[]=get_string('firstname');

            $columns[]="protistranalv1psc";
            $headers[]= get_string('lastname');

            $columns[]="protistranalv1obec";
            $headers[]= get_string('lastname');
        }

        $columns[]="protistranalv1stat";
        $headers[]= get_string('lastname');

        $columns[]="mena";
        $headers[]= get_string('lastname');

        $columns[]="polozkalv1kodzbozi";
        $headers[]= get_string('lastname');

        $columns[]="polozkalv1nazevzbozi";
        $headers[]= get_string('lastname');

        $columns[]="polozkalv1podrobnosti";
        $headers[]= get_string('lastname');

        $columns[]="polozkalv1cenamj";
        $headers[]= get_string('lastname');

        $columns[]="polozkalv1pocet";
        $headers[]= get_string('lastname');

        $columns[]="polozkalv1castka";
        $headers[]= get_string('lastname');

        $columns[]="polozkalv1vcetnedph";
        $headers[]= get_string('lastname');

        $columns[]="datum_zd_pl";
        $headers[]=get_string('cohort','cohort');

        $columns[]="datum_vystaveni";
        $headers[]=get_string('visits','report_invoices');

        $columns[]="datum_splatnosti";
        $headers[]=get_string('totalduration','report_invoices');

        $extrafields = [];

        $this->define_columns($columns);
        $this->define_headers($headers);

        $this->no_sorting('datum_splatnosti');
        $this->no_sorting('datum_vystaveni');

        // Make this table sorted by last name by default.
        $this->sortable(true, 'protistrana-nazev');
        $this->extrafields = $extrafields;

        parent::out($pagesize, $useinitialsbar, $downloadhelpbutton);
    }

    /**
     * Generate the fullname column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_protistranalv1nazev($data) {
            return $data->username;
    }

    /**
     * Generate the fullname column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_protistranalv1ic($data) {
        return $data->firstname;
    }

    /**
     * Generate the fullname column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_protistranalv1dic($data) {
        return $data->lastname;
    }

    /**
     * Generate the last access column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_protistranalv1ulice($data) {

    }

    /**
     * Generate the email column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_mena($data) {

    }

    /**
     * Generate total duration of all sessions column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_protistranalv1psc($data) {
        return $data->successfullogins;
    }

    /**
     * Generate visits count column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_protistranalv1obec($data) {
        return $data->visits;
    }

    /**
     * Generate the total dedication column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_protistranalv1stat($data) {

    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_polozkalv1kodzbozi($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_polozkalv1nazevzbozi($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_polozkalv1podrobnosti($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_polozkalv1cenamj($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_polozkalv1pocet($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_polozkalv1castka($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_polozkalv1vcetnedph($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_datum_zd_pl($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_datum_vystaveni($data) {
        return $data->cohort;
    }

    /**
     * Generate user cohort column.
     *
     * @param \stdClass $data
     * @return string
     */
    public function col_datum_splatnosti($data) {
        return $data->cohort;
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
        $what = "u.id, u.username as ,
         u.username as protistranalv1nazev,
         u.username as protistranalv1ic,
         u.username as protistranalv1dic,
         u.username as protistranalv1ulice,
         u.username as protistranalv1psc,
         u.username as protistranalv1obec,
         u.username as protistranalv1stat,
         u.username as mena,
         u.username as polozkalv1kodzbozi,
         u.username as polozkalv1nazevzbozi,
         u.username as polozkalv1podrobnosti,
         u.username as polozkalv1cenamj,
         u.username as polozkalv1pocet,
         u.username as polozkalv1castka,
         u.username as polozkalv1vcetnedph,
         u.username as datum_zd_pl,
         u.username as datum_vystaveni,
         u.username as datum_splatnosti ";
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
