<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parse extends CI_Controller 
{
  private $cnt = 0;
  private $url = "http://www.friidrott.se/rs/resultat.aspx";
  private $whitelistTable = 'club_whitelist';
  private $year = 0;
  
  public function create($tableName = 'results')
  {
    if ($tableName == 'stats')
      return;
            
    $time = microtime(TRUE);
    $memory = memory_get_usage();
    
    $this->load->database();
    $this->load->dbutil();
    $this->load->dbforge();
    if ($this->db->table_exists($this->whitelistTable))
      $this->dbforge->drop_table($this->whitelistTable);
    
    if (!$this->db->table_exists($this->whitelistTable)) {
      $this->dbforge->add_field(array(
        'partial_name' => array('type' => 'VARCHAR', 'constraint' => 100),
        'year' => array('type' => 'INT', 'constraint' => 5),
        'valid' => array('type' => 'INT', 'constraint' => 2)
      ));
      $this->dbforge->create_table($this->whitelistTable);
      $this->db->query("ALTER TABLE `{$this->whitelistTable}` ADD UNIQUE INDEX (`partial_name`)");
    }
    
    foreach(range(2001, 2014, 1) as $year) {
      $this->year = $year;
      $doc = new DOMDocument();
      @$doc->loadHTML(file_get_contents($this->url."?year=$year&type=11"));      
      $list = $doc->getElementById('marginal')->getElementsByTagName('p');
      foreach($list as $item) {
        $this->parseItem($doc, $item);
      }
    }
    
    print (microtime(TRUE)-$time). ' seconds and '. (memory_get_usage()-$memory). ' bytes';
  }
  
  private function parseItem($doc, $item) {
    if ($item->childNodes->length == 0)
      return;      
    $headline = explode(" ", $item->getElementsByTagName('span')->item(0)->textContent);
    $date = $this->toDate($headline[0]);
    $location = implode(" ", array_slice($headline, 1, count($headline) - 2));    
    $m = "";
    $k = "";
    $club = array();
    for($i = 0; $i < $item->childNodes->length; ++$i) {
      if (stripos($item->childNodes->item($i)->textContent, "Anm") !== false)
        continue;
      if (stripos($item->childNodes->item($i)->textContent, "Samtliga") !== false) {        
        $club = $this->getClubFromHeadline($item->childNodes->item($i)->textContent);
        if ($i == $item->childNodes->length) break;
      }
      if (stripos($item->childNodes->item($i)->textContent, "M:") !== false || 
          stripos($item->childNodes->item($i)->textContent, "M \"fel hand\":") !== false)
        $m = $item->childNodes->item(++$i)->textContent;
      if (stripos($item->childNodes->item($i)->textContent, "K:") !== false)
        $k = $item->childNodes->item(++$i)->textContent;
    }
    $f_m = array("!", ". ", "700m", "81256", "(1348  7166  3690  5307");
    $r_m = array("", ", ", "700,", "(1256", "(1348  7166  3690  5307)");
    $mRes = str_replace(".", "", $this->explodeNoEmpty(",", str_replace($f_m, $r_m, $m)));
    $f_w = array(". ", "(1173  2653  4343  2073", ")1108");
    $r_w = array(", ", "(1173  2653  4343  2073)", "(1108");
    $kRes = str_replace(".", "", $this->explodeNoEmpty(",", str_replace($f_w, $r_w, $k)));
    $results = array(array(), array());
    if (count($m) > 0)
      foreach($mRes as $mRec)
        array_push($results[0], $this->parseRecord($mRec));
    if (count($k) > 0)
      foreach($kRes as $kRec)
        array_push($results[1], $this->parseRecord($kRec));
  }
  
  // Pattern Name [Birth] [Club] Score [events]
  private function parseRecord($str)
  {
    $eventRes = $this->extractParenthesis($str);
    $nStr = $this->explodeNoEmpty(" ", trim($str));
    $score = array_pop($nStr);
    if (!is_numeric($score))
      return false;
    // If year of birth given.
    $index = -1;
    $birthyear = $this->extractYear($nStr, $index);
    // The hard part, determine name and club...    
    $name_count = $index == -1 ? $this->getNameLength($nStr) : $index;
    $name = implode(" ", array_slice($nStr, 0, $name_count));
    $club = array_slice($nStr, $name_count);   
    //if (count($club) > 0) $this->addClubToWhitelist($club, $year);
    return $nStr;
  }
  
  private function extractParenthesis(&$str)
  {
    $res = array(0, 0, 0, 0);
    while (preg_match("/\((.*?)\)/", $str, $pMatch) == 1) {
      $str = str_replace($pMatch[0], "", $str);
      $res = $this->getEvents($pMatch[0]);
    }
    $test = -1;
    if ($test = strpos($str, "(")) {      
      $res = $this->getEvents(substr($str, $test));
      $str = substr($str, 0, $test);
    }
    if ($res[0] == 0 && $res[1] == 0 && $res[2] == 0 && $res[3] == 0)
      return false;
    return $res;
  }
  
  private function getEvents($str) 
  {
    $res = array(0, 0, 0, 0);
    if (preg_match("/([^\d]|[\(\)])(((\d{3,4}|[xX])\s*){4}).*[\)\(]/", $str, $match) == 1) {
      $res = $this->explodeNoEmpty(" ", $match[2]);
    } else {
      $cnt = 0;
      foreach(array("kula", "spjut", "diskus", "slägga") as $e) {      
        if (preg_match("/$e\s+([\d]{3,4})/i", $str, $match) == 1)
          $res[$cnt] = intval($match[1]);
        ++$cnt;
      }
    }
    return $res;
  }
  
  private function getNameLength($arr) {
    $count = 0;
    foreach($arr as $e) {
      if ($count > 1 && $this->whitelistedClub($e))
        break;
      ++$count;
    }
    return $count;
  }
  
  private function whitelistedClub($club) 
  {
    $this->addClubToWhitelist($club);
    return true;
    $sql = "SELECT EXISTS(SELECT 1 FROM {$this->whitelistTable} WHERE partial_name ='$club' AND valid =1 LIMIT 1)";
    $resp = $this->db->query($sql);        
    return $resp->num_rows();
  }
  
  private function addClubToWhitelist($names) {       
    if (count($names) == 0)
      return;
    
    $insert_query = $this->db->insert_string($this->whitelistTable, array(
      'partial_name' => trim($names[0]),
      'year' => $this->year,
      'valid' => 1
    ));
    $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
    if (count($names) > 1) {
      for($i = 1; $i < count($names); ++$i)
        $sql .= ",('".trim($names[$i])."', {$this->year}, 1)";
    }
    // Warnings suppressed!
    @$this->db->query($sql);
  }
  
  private function getClubFromHeadline($str)
  {
    $nStr = $this->explodeNoEmpty(" ", str_replace(array(".", ",", "om ej annat anges", "Samtliga"), "", $str));
    $club = "";
    foreach($nStr as $name) {      
      if ($this->whitelistedClub($name))
        $club .= " $name";
      else if ($club != "")
        break;
    }
    return $club == "" ? false : $club;
  }
  
  private function explodeNoEmpty($del, $str)
  {
    return array_values(array_filter(explode($del, $str), function($v){return trim($v)!=""?$v:null;}));
  }
  
  private function extractYear(&$arr, &$ind)
  {
    foreach(array_keys($arr) as $i) {
      if (is_numeric($arr[$i])) {
        $e = trim($arr[$i]);
        $ind = $i;
        unset($arr[$i]);
        $arr = array_values($arr);
        return $e;
      }
    }
    return false;
  }
  
  private function toDate($dayMonth) 
  {
    $dm = explode(".",$dayMonth);
    $date = sprintf("%02d", $dm[0]);
    $month = sprintf("%02d", $dm[1]);
    return "{$this->year}-$month-$date";
  }
}
