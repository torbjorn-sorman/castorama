<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller 
{
  public function index()
  {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    echo "<html>Stats.php</html>";
  }
  
  public function view($text = 'text')
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    echo $text;
  }
  
  public function get($id) 
  {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
    $this->load->database();
    $query = $this->db->get_where('stats', array('id =' => $id), 1, 0);
    echo json_encode($query->result());
  }
  
	public function search() 
  {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
    $this->load->database();
    $input = json_decode(file_get_contents('php://input'));    
    $cond = array('date >=' => $input->fromdate, 'date <=' => $input->todate);    
    if ($input->gender != 'all')
      $cond['sex ='] = ($input->gender == 'men') ? 1 : 0;
    if ($input->name != '')
      $cond['name LIKE'] = "%".$input->name."%";
    if ($input->club != '')
      $cond['club LIKE'] = "%".$input->club."%";        
    
    $this->db->order_by($input->orderby, $input->orderbydir);
    $query = $this->db->get_where('stats', $cond, $input->limit, $input->offset);
    echo json_encode($query->result());
  }  
}
