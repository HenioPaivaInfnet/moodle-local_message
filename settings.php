<?php

if ($hassiteconfig) {
    $ADMIN->add('localplugins', 
        new admin_category('local_message_category', 'categoria de menssagens')
);
    $settings = new admin_settingpage(
        'local_message',
        get_string('pluginname', 'local_message')
    );

    $ADMIN->add('local_message_category', $settings);
    
    $settings->add(new admin_setting_configcheckbox(
        'local_message/enabled', 
        'Option' , 
        'Information about plugin' , 
        '1'
        )
    );

    $ADMIN->add(
        'local_message_category',
        new admin_externalpage(
            'local_message_manage',
            get_string('manage', 'local_message'),
            $CFG->wwwroot . '/local/message/manage.php'
        )
    );
}
