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
    $res = $this->db->query("SELECT old.* FROM `stats` old LEFT JOIN `results` new ON new.name LIKE CONCAT(old.name,'%') AND old.date = new.date AND old.score = new.score WHERE new.id IS NULL AND old.date > '1997-01-01'");  
    $match = $this->findPossibleMatches($res->result());
    $cols = array("name", "score", "date");
    $colsMatch = array("name", "score", "date");
    
    $this->show($res->result(), $match, 'name', $cols, $colsMatch, "Total number of missmatches: ". $res->num_rows());
    
    print sprintf("%.2f",(microtime(TRUE)-$time)). ' seconds and '. (memory_get_usage()-$memory). ' bytes';
  }
  
  public function total($s = 2001, $e = 2014)
  {
    $total = 0;
    foreach(range(intval($s), intval($e), 1) as $year) {
      $url = "http://www.friidrott.se/rs/resultat2/castarkiv/cast".substr(strval($year), 2).".aspx";
      $doc = new DOMDocument();
      @$doc->loadHTML(file_get_contents($url));
      $list = $doc->getElementById('marginal')->getElementsByTagName('table')->item(3)->getElementsByTagName('tr');
      $sum = 0;
      echo $list->length."<br/>";
      for($i = 1; $i < $list->length; ++$i) {
        $l = $list->item($i)->getElementsByTagName('td');
        $v = intval(trim($l->item($l->length - 1)->textContent));
        $sum += $v;
      }
      echo "$year: $sum<br/>";
      $total += $sum;
    }
    echo "All: $total";
  }
  
  private function findPossibleMatches($results)
  {
    $cond = "";
    foreach($results as $row)
      $cond .= "(name = '{$row->name}' AND date = '{$row->date}') OR ";
    $cond = substr($cond, 0, strlen($cond) - 4);
    return $this->db->query("SELECT * FROM `results` WHERE $cond;")->result();
  }  
  
  private function show($results, $match, $p, $columns, $columnsMatch, $title)
  {
    $headers = "";
    foreach($columns as $col) $headers .= "<th>$col</th>";
    foreach($columnsMatch as $col) $headers .= "<th>$col</th>";
    echo "$title<br/><table><tr>$headers</tr>";
    foreach($results as $row) {
      echo "<tr>";
      foreach($columns as $col) echo "<td>".$row->$col."</td>";
      if ($e = $this->find($match, $row, $p))
        foreach($e as $m)
          foreach($columnsMatch as $col) echo "<td>".$m->$col."</td>";
      echo "</tr>";
    }
    echo "</table>";
  }
  
  private function find($haystack, $needle, $p)
  {
    $hits = array();
    foreach($haystack as $hay)
      if ($hay->$p == $needle->$p)
        array_push($hits, $hay);
    return $hits;
  }
  
}
