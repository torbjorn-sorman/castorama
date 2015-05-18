<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Verify extends CI_Controller 
{  
  public function index()
  {
    
    $time = microtime(TRUE);
    $memory = memory_get_usage();
    
    $cnt = 0;
    $this->load->database();
    $cols = array("name", "score", "date");
    $ref = "";
    $new = "";
    $cmp = "";
    foreach($cols as $c) {
      $ref .= "stats.$c, ";
      $new .= "results.$c, ";
      $cmp .= "stats.$c != results.$c AND ";
    }
    $new = substr($new, 0, strlen($new) - 2);
    $cmp = substr($cmp, 0, strlen($cmp) - 5);
    $res = $this->db->query("SELECT $ref$new FROM stats, results WHERE stats.date > '2000-01-01' AND $cmp ORDER BY stats.date DESC");    
    foreach($res->result() as $row) {
      foreach($cols as $col) {
        echo $row->$col." ";
      }
      echo "<br/>";
    }
    echo "Total: {$res->num_rows()}<br/>";
    print sprintf("%.2f",(microtime(TRUE)-$time)). ' seconds and '. (memory_get_usage()-$memory). ' bytes';
  }
  function compareDate($ref, $row)
  {
    return intval(str_replace("-", "", $ref->date)) > intval(str_replace("-", "", $row->date));
  }
}
