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
        return;
        require "data/club_whitelist.php";
        require "data/tables.php"; 
                
        $dbName = 'castorama';
        if (!$this->dbutil->database_exists($dbName))
            $this->dbforge->create_database($dbName);
        $this->db->query('use '.$dbName);
        $this->db->database = $dbName;
        
        foreach ($tables as $table => $fields) {
            if ($this->db->table_exists($table))
                $this->dbforge->drop_table($table);
            $this->dbforge->add_field('id');
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($table);  
        }        
        
        $this->db->query("ALTER TABLE `users` ADD UNIQUE INDEX (`username`)");
        
        $initialContent = array(
            'users' => array(array('username' => 'tb', 'password' => crypt("cooling",'$2a$09$anexamplestringforsalt$'))),
            'club_whitelist' => $club_whitelist
        );
        
        foreach($initialContent as $key => $param)
            $this->db->insert_batch($key, $param);
        
        $this->createResultDB('results');
    }
    
    private function createResultDB($tableName) {
        // Only create if non-existing
        if (!$this->db->table_exists($tableName)) {
            $this->dbforge->add_field('id');
            $this->dbforge->add_field(array(
              'sex' => array('type' => 'INT', 'constraint' => 1),
              'date' => array('type' => 'DATE'),
              'location' => array('type' => 'VARCHAR', 'constraint' => 50),
              'name' => array('type' => 'VARCHAR', 'constraint' => 50),
              'birthyear' => array('type' => 'INT', 'constraint' => 5),
              'club' => array('type' => 'VARCHAR', 'constraint' => 50),
              'score' => array('type' => 'INT', 'constraint' => 4),
              'shot' => array('type' => 'INT', 'constraint' => 4),
              'javelin' => array('type' => 'INT', 'constraint' => 5),
              'discus' => array('type' => 'INT', 'constraint' => 4),
              'hammer' => array('type' => 'INT', 'constraint' => 4),
              'key' => array('type' => 'VARCHAR', 'constraint' => 32),
            ));
            $this->dbforge->create_table($tableName);
            $this->db->query("ALTER TABLE `$tableName` ADD UNIQUE INDEX (`key`)");
        }
    }
}
