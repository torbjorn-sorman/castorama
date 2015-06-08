<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Templates extends CI_Controller
{  
    function __construct()
    {
        parent::__construct();
    } 
    
    public function get($template)
    {         
        $this->load->view("/templates/$template");
    }  
}
?>
