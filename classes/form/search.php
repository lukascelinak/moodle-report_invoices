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
 * The Invoices Report search form definition.
 *
 * @package     report_invoices
 * @category    admin
 * @copyright   2022 Lukas Celinak, Edumood,  <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_invoices\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Report search form class.
 *
 * @package     report_invoices
 * @copyright   2022 Lukas Celinak, Edumood,  <lukascelinak@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class search extends \moodleform {

    /**
     * Form definition
     *
     * @return void
     */
    public function definition() {
        global $DB, $CFG;
        $mform = $this->_form;
        $mform->addElement('date_selector', 'datefrom', get_string('datefrom', 'report_invoices'));
        $mform->addHelpButton('datefrom','datefrom','report_invoices');
        $mform->addElement('date_selector', 'dateto', get_string('dateto', 'report_invoices'));
        $mform->addHelpButton('dateto','dateto','report_invoices');
        $this->add_action_buttons(false, get_string('search'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);
        if ($data['datefrom']>$data['dateto']) {
            $errors['datefrom'] = get_string("datefrom_error", "report_invoices");
        }
        return $errors;
    }

}
