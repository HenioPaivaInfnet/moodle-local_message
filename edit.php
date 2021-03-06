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
 * pagina de criação e update das mensagensm onde implementa as operações do CRUD
 *
 * @package   local_message
 * @copyright Henio
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_message\manager;
use local_message\form\edit;

require_once(__DIR__ . '/../../config.php');
require_once('classes/form/edit.php');

global $DB;

require_login();
$context = context_system::instance();
require_capability('local/message:managemessages', $context);

$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edição de mensagens');

$messageid = optional_param('messageid', null, PARAM_INT);

$mform = new edit();
$manager = new manager();

if($mform->is_cancelled()){
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('cancelled_form', 'local_message'));
}
else if($fromform = $mform->get_data()){
    if($fromform->id) {
        $manager->update_message($fromform);
        redirect($CFG->wwwroot . '/local/message/manage.php', get_string('updated_form', 'local_message'));
    }
    
    $manager->create_message($fromform->messagetext, $fromform->messagetype);
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('sucessed_form', 'local_message'));
}

if($messageid) {
    $message = $manager->get_message($messageid);
    if(!$message) {
        throw new invalid_parameter_exception('Message not found');
    }
    $mform->set_data($message);
}


echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();