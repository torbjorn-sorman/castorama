<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
      class Admin extends MY_Controller
      {  
          function __construct()
          {
              parent::__construct();
              $this->setPage('home');
              $this->load->helper('url');
          } 
          
          function index()
          {     
              if($this->loggedIn()) {    
                  $this->load->view('admin', $this->data);
              } else {
                  $this->load->helper('form');
                  $this->load->view('login');
              }
          }  
      }
?>
