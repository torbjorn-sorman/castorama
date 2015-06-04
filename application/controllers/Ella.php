<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ella extends MY_Controller
{  
    private $table = "ella_table";
    
    function __construct()
    {
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->dbutil();
        $this->load->dbforge();
    } 
    
    function index()
    {         
        $this->load->view('ella');
    }
	
	function prepdb()
	{
        $fields = array(
            'text' => array('type' => 'VARCHAR','constraint' => '100'),
            'url' => array('type' => 'VARCHAR','constraint' => '"200')
        );
        
        if ($this->db->table_exists($this->table))
        return;
            
        $this->dbforge->add_field('id');
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($this->table);
	}
	
	function content()
	{
        $this->order_by("datetime","desc");
        $query = $this->db->get($this->table);
        echo json_encode($query->result());
	}
    
    function add()   
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if($data === null) {
            echo "Not valid JSON";
            return;
        }
        $this->db->insert($this->table, $data);
    }
}
?>