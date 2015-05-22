<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
      class MY_Controller extends CI_Controller
      {
          protected $page;
          protected $data;
          
          function __construct()
          {
              parent::__construct();
              $this->load->library('session');
          }
          
          function index()
          {         
              if($this->loggedIn()) {
                  $this->load->view($this->page, $this->data);
              } else {
                  redirect('login', 'refresh');
              }
          }
          
          protected function setPage($page)
          {
              $this->page = $page;
          }
          
          protected function loggedIn()
          {
              if (!$this->data)
                  $this->data = array();
              return $this->session->userdata('logged_in') ? true : false;
          }
          
          function logout()
          {
              $this->session->unset_userdata('logged_in');
              session_destroy();
              redirect('home', 'refresh');
          }
      }