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
        
        $this->db->order_by($input->orderby, $input->orderbydir);
        $query = $this->db->get_where($this->table, $cond, $input->limit, $input->offset);
        echo json_encode($query->result());
    }
    
    public function season($year = 2014, $sex = 1, $all = 0) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        $this->load->database();
        $nonCompete = "AUS|CAN|CYP|DEN|ESP|EST|FIN|FRA|GBR|GER|GRE|IRL|IRN|ISL|ISR|JAM|LAT|NOR|SRB|USA|USS";
        $select = "SELECT name, club, max(score) score";
        $where = "WHERE date BETWEEN '$year-01-01' AND '".($year + 1)."-01-01' AND sex = $sex" . ($all == 0 ? " AND club NOT REGEXP '$nonCompete'" : "");
        $sql = "$select FROM results $where GROUP BY name ORDER BY score DESC LIMIT 25";        
        $query = $this->db->query($sql);
        echo json_encode($query->result());
    }
}
