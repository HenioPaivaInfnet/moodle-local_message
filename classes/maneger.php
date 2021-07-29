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

class maneger{

    public function create_message($messagetext, $messagetype): bool
    {
        global $DB;
        $recordtoinsert = new stdClass();
        $recordtoinsert->messagetext = $messagetext;
        $recordtoinsert->messagetype = $messagetype;
        try{
           return $DB->insert_record('local_message', $recordtoinsert);
        }
        catch(dml_exception $e){
            return false;
        }
    }

    public function get_messages($userid){
        global $DB;
        $sql = "SELECT lm.id, lm.messagetext, lm.messagetype
            FROM {local_message} lm 
            left join {local_message_read} lmr
            on lm.id=lmr.messageid
            where lmr.userid IS NULL
            ";

        $params = [
            'userid' => $userid,
        ];
        try{
            return $DB->get_records_sql($sql, $params);
        }
        catch(dml_exception $e){
            return [];
        }
    }
}