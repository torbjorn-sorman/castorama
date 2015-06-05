<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends CI_Controller 
{
    private $table = "results";
    public function index()
    {
        $this->load->view('stats');
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
        $query = $this->db->get_where($this->table, array('id =' => $id), 1, 0);
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
        if ($input->location != '')
            $cond['location LIKE'] = "%".$input->location."%";        
        
        $this->db->order_by($input->orderby, $input->orderbydir);
        if ($this->isEvent($input->orderby)) {
            $cond[$input->orderby . ' >'] = 0;
        }
        $query = $this->db->get_where($this->table, $cond, $input->limit, $input->offset);
        echo json_encode($query->result());
    }
    
    private function isEvent($val) {
        return $val == 'shot' || $val == 'javelin' || $val == 'discus' || $val == 'hammer';
    }
    
    public function season($year = 2014, $sex = 1, $all = 0) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        
        $s = intval($sex);
        $a = intval($all);               
        $this->load->database();  
        $table = (($s == 2) ? 'season_clubs' : ($a == 0 ? 'season_all' : 'season'));
        $cond = (($s < 2) ? "WHERE sex = $s" : "");
        $sql = "SELECT * FROM $table $cond ORDER BY score DESC";        
        $query = $this->db->query($sql);
        echo json_encode($query->result());
    }
}
