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
 * Version details
 *
 * @package   local_message
 * @copyright Henio
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message\form;
use moodleform;

global $CFG;
require_once("$CFG->libdir/formslib.php");

class edit extends moodleform{

    public function definition(){
        $mform = $this->_form;
    
        $mform->addElement('text', 'messagetext', get_string('message_text','local_message'));
        $mform->setType('messagetext', PARAM_NOTAGS);
        $mform->setDefault('messagetext', get_string('message_content', 'local_message'));
        
        
        $choices = array();
        $choices['0'] = \core\output\notification::NOTIFY_INFO;
        $choices['1'] = \core\output\notification::NOTIFY_SUCCESS;
        $choices['2'] = \core\output\notification::NOTIFY_WARNING;
        $choices['3'] = \core\output\notification::NOTIFY_ERROR;
        $mform->addElement('select', 'messagetype', get_string('message_type','local_message'), $choices);
        $mform->setDefault('messagetype', '0');
        $this->add_action_buttons();
    }

    function validation($data, $files){
        return array();
    }
}