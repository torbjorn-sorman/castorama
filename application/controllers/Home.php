<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
      class Home extends MY_Controller
      {  
          function __construct()
          {
              parent::__construct();
              $this->setPage('home');
              $this->load->helper('url');
          } 
          
          function index()
          {         
              $this->load->view('home', $this->data);              
          }  
      }
?>
