<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parse extends CI_Controller 
{
  private $cntM = 0;
  private $cntW = 0;
  private $url = "http://www.friidrott.se/rs/resultat.aspx";
  private $whitelistTable = 'club_whitelist';
  private $year = 0;
  private $poulateWhitelist = false;
  
  public function create($tableName = 'results')
  {
    if ($tableName == 'stats')
      return;
            
    $time = microtime(TRUE);
    $memory = memory_get_usage();
    
    $this->load->database();
    $this->load->dbutil();
    $this->load->dbforge();
    
    if ($this->poulateWhitelist && $this->db->table_exists($this->whitelistTable))
      $this->dbforge->drop_table($this->whitelistTable);
    if ($this->db->table_exists($tableName))
      $this->dbforge->drop_table($tableName);
    
    if (!$this->db->table_exists($this->whitelistTable)) {
      $this->dbforge->add_field(array(
        'partial_name' => array('type' => 'VARCHAR', 'constraint' => 100),
        'ref' => array('type' => 'INT', 'constraint' => 8),
        'valid' => array('type' => 'INT', 'constraint' => 2),
        'year' => array('type' => 'INT', 'constraint' => 5)
      ));
      $this->dbforge->create_table($this->whitelistTable);
      $this->db->query("ALTER TABLE `{$this->whitelistTable}` ADD UNIQUE INDEX (`partial_name`)");
    }
    if (!$this->db->table_exists($tableName)) {
      $this->dbforge->add_field('id');
      $this->dbforge->add_field(array(
        'sex' => array('type' => 'INT', 'constraint' => 1),
        'date' => array('type' => 'DATE'),
        'location' => array('type' => 'VARCHAR', 'constraint' => 50),
        'name' => array('type' => 'VARCHAR', 'constraint' => 50),
        'birthyear' => array('type' => 'INT', 'constraint' => 5),
        'club' => array('type' => 'VARCHAR', 'constraint' => 50),
        'score' => array('type' => 'INT', 'constraint' => 4),
        'shot' => array('type' => 'INT', 'constraint' => 4),
        'javelin' => array('type' => 'INT', 'constraint' => 5),
        'discus' => array('type' => 'INT', 'constraint' => 4),
        'hammer' => array('type' => 'INT', 'constraint' => 4),
        'key' => array('type' => 'VARCHAR', 'constraint' => 32),
      ));
      $this->dbforge->create_table($tableName);
      $this->db->query("ALTER TABLE `$tableName` ADD UNIQUE INDEX (`key`)");
    }
    
    foreach(range(2001, 2014, 1) as $year) {
      $this->year = $year;
      $doc = new DOMDocument();
      @$doc->loadHTML(file_get_contents($this->url."?year=$year&type=11"));      
      $list = $doc->getElementById('marginal')->getElementsByTagName('p');
      foreach($list as $item) {
        $this->parseItem($doc, $item, $tableName);
      }
    }
    print "M:{$this->cntM} W:{$this->cntW}<br>";
    
    print sprintf("%.2f",(microtime(TRUE)-$time)). ' seconds and '. (memory_get_usage()-$memory). ' bytes';
  }
  
  private function parseItem($doc, $item, $table) {
    if ($item->childNodes->length == 0)
      return false;     
    $all_club = false;
    foreach($item->childNodes as $child) {
      if (stripos($child->textContent, "Samtliga") !== false) {
        $all_club = $this->getClubFromHeadline($child->textContent);
        break;
      }
    }
    $str = $item->textContent;
    $headline = $this->extractHeadline($str, $all_club);        
    $women = $this->extractResults($str, "/K\s*\"fel hand\"\s*:\s*(.+)/i");
    $women = $this->extractResults($str, "/K\s*:\s*(.+)/i").$women;    
    $men = $this->extractResults($str, "/M\s*\"fel hand\"\s*:\s*(.+)/i");
    $men = $this->extractResults($str, "/M\s*:\s*(.+)/i").$men;
    
    // Catch known manual input errors... dirty but effective ;-)
    $err = array("!", ". ", "700m", "81256", "(1348  7166  3690  5307", " 4) ", "1.2476");
    $cor = array("", ", ", "700,", "(1256", "(1348  7166  3690  5307)", " ", "1.247");
    $mRes = str_replace(".", "", $this->explodeNoEmpty(",", str_replace($err, $cor, $men)));
    
    $err = array("!", ". ", "(1173  2653  4343  2073", ")1108", " 1) ");
    $cor = array("", ", ", "(1173  2653  4343  2073)", "(1108", " ");
    $wRes = str_replace(".", "", $this->explodeNoEmpty(",", str_replace($err, $cor, $women)));
    
    $this->cntM += count($mRes);
    $this->cntW += count($wRes);
    
    $results = array();
    if ($men)
      foreach($mRes as $record)
        array_push($results, $this->wrapRecord($this->parseRecord($record), 1, $headline));
    if ($women)
      foreach($wRes as $record)
        array_push($results, $this->wrapRecord($this->parseRecord($record), 0, $headline));
    $this->addRecords($table, $results);
  }
  
  private function wrapRecord($rec, $sex, $head)
  {
    $rec['sex'] = $sex;
    $rec['date'] = $head['date'];
    $rec['location'] = $head['location'];
    if (!array_key_exists('club', $rec) || $rec['club'] == "") {
      var_dump($rec);
      var_dump($head);
      $rec['club'] = trim(implode(" ", $head['club']));
    }
    $ev = array_key_exists('events', $rec) && $rec['events'];
    $rec['shot'] = $ev ? $rec['events'][0] : 0;
    $rec['javelin'] = $ev ? $rec['events'][1] : 0;
    $rec['discus'] = $ev ? $rec['events'][2] : 0;
    $rec['hammer'] = $ev ? $rec['events'][3] : 0;
    unset($rec['events']);
    $rec['key'] = md5(serialize($rec));
    return $rec;
  }
  
  // Pattern Name [Birth] [Club] Score [events]
  private function parseRecord($str)
  { 
    $eventRes = $this->extractParenthesis($str);
    $strcpy = substr($str, 0);
    $nStr = $this->explodeNoEmpty(" ", trim($str));    
    $score = array_pop($nStr);
    if (!is_numeric($score)) {
      return false;
    }
    
    $index = -1;
    $birthyear = $this->extractYear($nStr, $index);
    $name_count = $index == -1 ? $this->getNameLength($nStr) : $index;
    $name = trim(implode(" ", array_slice($nStr, 0, $name_count)));
    $club = trim(implode(" ", array_slice($nStr, $name_count)));
    return array(
      'name' => $name,
      'birthyear' => $birthyear,
      'club' => $club,
      'score' => $score,
      'events' => $eventRes
    );
  }
  
  private function extractHeadline(&$str, $club)
  {
    $res = array();
    if ($club)
      $res['club'] = $club;
    $pattern = "/^\s*(\d{1,2})\.(\d{1,2})\s*(.+)\s*\(Castorama\)/";
    if (preg_match($pattern, $str, $match) == 1) {
      $str = str_replace($match[0], "", $str);
      $res['date'] = $this->year."-".sprintf("%02d", $match[2])."-".sprintf("%02d", $match[1]);
      $res['location'] = trim($match[3]);      
      return $res;
    }
    return false;
  }
  
  private function extractResults(&$str, $pattern)
  {
    $res = array();
    if (preg_match($pattern, $str, $match) == 1) { 
      $str = str_replace($match[0], "", $str);      
      return $match[1];
    }
    return false;
  }
  
  private function extractParenthesis(&$str)
  {
    $res = array(0, 0, 0, 0);
    $pattern = "/\((.*?)\)/";
    while (preg_match($pattern, $str, $match) == 1) {
      $str = str_replace($match[0], "", $str);
      $res = $this->getEvents($match[0]);
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
    $pattern = "/([^\d]|[\(\)])(((\d{3,4}|[xX])\s*){4}).*[\)\(]/";
    if (preg_match($pattern, $str, $match) == 1) {
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
    if ($this->poulateWhitelist) {
      $this->addClubToWhitelist(array($club));
      return true;
    }
    $sql = "SELECT EXISTS(SELECT 1 FROM {$this->whitelistTable} WHERE partial_name ='$club' AND valid =1 LIMIT 1)";
    $resp = $this->db->query($sql);        
    return $resp->num_rows();
  }
  
  private function addRecords($table, $res) 
  {
    if (count($res) == 0) {
      return false;
    }
    $init = 0;
    while ($init < count($res) && count(array_keys($res[$init])) != 12)
      var_dump($res[$init++]);
    $insert_query = $this->db->insert_string($table, $res[$init++]);
    $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);        
    if (count($res) > $init) {
      for($i = $init; $i < count($res); ++$i) {
        if (count(array_keys($res[$i])) == 12)
          $sql .= ",(".$this->iterValues($res[$i]).")";
        else
          var_dump($res[$i]);
      }
    }
    $sql .= ";";
    $this->db->query($sql);
  }
  
  private function iterValues($obj)
  {
    $str = "";
    foreach($obj as $e) {      
      $str .= "'$e', ";
    }
    return substr($str, 0, strlen($str) - 2);
  }
  
  private function addClubToWhitelist($names)
  {
    if (count($names) == 0)
      return;
    $sql = $this->db->insert_string($this->whitelistTable, array(
      'partial_name' => trim($names[0]),
      'ref' => 1,
      'valid' => 1,
      'year' => $this->year      
    ));
    if (count($names) > 1) {
      for($i = 1; $i < count($names); ++$i)
        $sql .= ",('".trim($names[$i])."', {$this->year}, 1)";
    }
    $sql .= "ON DUPLICATE KEY UPDATE ref=ref+1;";
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
    return array_values(array_filter(explode($del, $str), function($v){return trim($v)!=""?1:null;}));
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
