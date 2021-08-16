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

use local_message\manager;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

class local_message_external extends external_api {
    /**
     * Parametros requiridos pela api para executar função
     * @return external_funciotion_parameters
     */
    public static function delete_message_parameters(){
        return new external_function_parameters(
            ['messageid' => new external_value(PARAM_INT, 'id of message')],
        );
    }

    public static function delete_message($messageid) {
        $params = self::validate_parameters(self::delete_message_parameters(), array('messageid'=>$messageid));

        $manager = new manager();
        return $manager->delete_message($messageid);
    }

    public static function delete_message_returns() {
        return new external_value(PARAM_BOOL, 'True if message deleted');
    }
}