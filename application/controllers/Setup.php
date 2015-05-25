<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Setup short summary.
 *
 * Setup description.
 *
 * @version 1.0
 * @author Torbjörn
 */
class Setup extends CI_Controller
{  
    function __construct()
    {
        parent::__construct();  
        $this->load->dbutil();
        $this->load->dbforge();
    } 
    
    function index()        
    {         
        require "data/club_whitelist.php";
        require "data/tables.php"; 
                
        $dbName = 'castorama';
        if (!$this->dbutil->database_exists($dbName))
            $this->dbforge->create_database($dbName);
        $this->db->query('use '.$dbName);
        $this->db->database = $dbName;
        
        $newTables = array();
        foreach ($tables as $table => $fields) {
            if ($this->db->table_exists($table)) {
                foreach ($fields as $field => $properties) {
                    if ($this->db->field_exists($field, $table))
                        $this->dbforge->modify_column($table, array($field => $properties));
                    else
                        $this->dbforge->add_column($table, array($field => $properties));
                }
            } else {
                $this->dbforge->add_field('id');
                $this->dbforge->add_field($fields);
                $this->dbforge->create_table($table);  
                array_push($newTables, $table);
            }
        }        
        
        $this->db->query("ALTER TABLE `users` ADD UNIQUE INDEX (`username`)");
        
        $initialContent = array(
            'users' => array(array('username' => 'tb', 'password' => crypt("cooling",'$2a$09$anexamplestringforsalt$'))),
            'club_whitelist' => $club_whitelist
        );
        foreach($initialContent as $key => $param)
            //if (array_search($key, $newTables))
                $this->db->insert_batch($key, $param);
    }
}
