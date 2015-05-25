<?php

/**
 * tables short summary.
 *
 * tables description.
 *
 * @version 1.0
 * @author Torbjörn
 */
$tables = array(
    'club_whitelist' => array(
        'partial_name' => array('type' => 'VARCHAR','constraint' => '100'),
        'ref' => array('type' => 'INT','constraint' => '6'),
        'valid' => array('type' => 'INT','constraint' => '4'),
        'year' => array('type' => 'INT','constraint' => '5')
    ),
    'users' => array(
        'username' => array('type' => 'VARCHAR','constraint' => '100'),
        'password' => array('type' => 'VARCHAR','constraint' => '100'))
    );
