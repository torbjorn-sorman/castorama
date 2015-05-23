<?php

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
    } 
    
    function index()        
    {         
        $password = "cooling";
        $initialContent = array(
            'users' => 
                  array(
                    array(
                      'username' => 'tb', 
                      'password' => crypt($password,'$2a$09$anexamplestringforsalt$'))));
        foreach($initialContent as $key => $param)
            $this->db->insert_batch($key, $param);
    }  
}
