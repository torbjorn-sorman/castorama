<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Main extends MY_Controller
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
      $this->load->view('templates/header', $this->data);
      $this->load->view('templates/footer', $this->data);
    } else {
      redirect('login', 'refresh');
    }
  }  
}
?>