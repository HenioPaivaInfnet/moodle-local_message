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

defined('MOODLE_INTERNAL') || die();
require_once('classes/maneger.php');

function local_message_before_footer() {
    global $DB, $USER;

    $maneger = new maneger();
    $messages = $maneger->get_messages($USER->id);
     
    foreach($messages as $message){
        $type = \core\output\notification::NOTIFY_INFO;
        if($message->messagetype === '1'){
            $type = \core\output\notification::NOTIFY_SUCCESS;
        }
        if($message->messagetype === '2'){
            $type = \core\output\notification::NOTIFY_WARNING;
        }
        if($message->messagetype === '3'){
            $type = \core\output\notification::NOTIFY_ERROR;
        }
        \core\notification::add($message->messagetext, $type);

        $readrecord = new stdClass();
        $readrecord->messageid = $message->id;
        $readrecord->userid = $USER->id;
        $readrecord->timeread = time();
        $DB->insert_record('local_message_read', $readrecord);
    }
}