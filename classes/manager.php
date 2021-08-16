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

namespace local_message;

use stdClass;
use dml_exception;

class manager{

    /** Cria e insere uma nova mensagem no banco.
     * @param string $message_text
     * @param string $message_type
     * @return bool true if successful
     */
    public function create_message($messagetext, $messagetype): bool {
        global $DB;
        $recordtoinsert = new stdClass();
        $recordtoinsert->messagetext = $messagetext;
        $recordtoinsert->messagetype = $messagetype;
        try {
            return $DB->insert_record('local_message', $recordtoinsert);
        } catch(dml_exception $e) {
            return false;
        }
    }

    /** recupera as mensagens do banco.
     * @param int $userid utiliza o ID do usuario para determinar se vai mostrar a mensagem
     * @return array retorna as uma array das mensagens existentes
     */
    public function get_messages($userid): array {
        global $DB;
        $sql = "SELECT lm.id, lm.messagetext, lm.messagetype
            FROM {local_message} lm
            LEFT OUTER JOIN {local_message_read} lmr ON lm.id = lmr.messageid AND lmr.userid = :userid
            WHERE lmr.userid IS NULL";

        $params = [
            'userid' => $userid,
        ];
        try {
            return $DB->get_records_sql($sql, $params);
        } catch(dml_exception $e) {
            return [];
        }
    }

    public function mark_message_read($messageid, $userid): bool {
        global $DB;
        $readrecord = new stdClass();
        $readrecord->messageid = $messageid;
        $readrecord->userid = $userid;
        $readrecord->timeread = time();
        try {
           return $DB->insert_record('local_message_read', $readrecord, false);
        } catch(dml_exception $e) {
            return false;
        }
        
    }

    public function get_message($messageid) {
        global $DB;
        return $DB->get_record('local_message', ['id' => $messageid]);

    }

    public function update_message($message){
        global $DB;
        $object = new stdClass();
        $object->id = $message->id;
        $object->messagetext = $message->messagetext;
        $object->messagetype = $message->messagetype;
        return $DB->update_record('local_message', $object);
    }

    public function delete_message($messageid) {
        global $DB;
        $transaction = $DB->start_delegated_transaction();
        $deletemessage = $DB->delete_records('local_message', ['id' => $messageid]);
        $deleteread = $DB->delete_records('local_message_read', ['messageid' => $messageid]);
        if($deletemessage && $deleteread){
            $DB->commit_delegated_transaction($transaction);
        }
        return true;
    }
}
