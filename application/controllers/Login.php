<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller
{  
    function __construct()
    {
        parent::__construct();
        $this->load->model('user','',TRUE);
    }
    
    function index()
    {
        $this->load->view('login');
    }
    
    function login() 
    {        
        $login = json_decode(file_get_contents('php://input'));        
        $result = $this->user->login($login->username, $login->password);        
        if($result) {
            $sess_array = array();
            foreach($result as $row) {                
                $sess_array = array(
                  'id' => $row->id,
                  'username' => $row->username
                );
                $this->session->set_userdata('logged_in', $sess_array);      
                echo json_encode(array(
                    'success' => true,
                    'username' => $login->username
                ));
            }
        } else {      
            echo false;
        }
    }
    
    function logout()
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        echo true;
    }
    
    function isloggedin()
    {
        echo $this->loggedIn();
    }
}

?>