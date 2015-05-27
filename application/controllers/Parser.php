<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parser extends MY_Controller 
{
    private $cntM = 0;
    private $cntW = 0;
    private $url = "http://www.friidrott.se/rs/resultat.aspx";
    private $year = 0;
    private $clubRej = array();
    private $whitelist;    
    
    private $errorMen = array(
      "!" => "",
      "§" => "",
      "\t" => " ",
      ". " => ", ",
      "700m" => "700,",
      "81256" => "(1256",
      "(1348  7166  3690  5307" => "(1348  7166  3690  5307)",
      "1.2476" => "1.247",
      "1.,744" => "1744",
      "92703" => "92 703",
      "4777" => "477"
    );  
    private $errorWomen = array(
      "2-.087" => "2087",
      "1.420.l" => "1420",
      "\t" => " ",
      "!" => "",
      ". " => ", ",
      "(1173  2653  4343  2073" => "(1173  2653  4343  2073)",
      ")1108" => "(1108",
      "Sandin 912" => "Sandin 92",
      "HjelmHuddinge" => "Hjelm Huddinge",
      "4270" => "427"
    );
    private $errorGender = array(
      "M: Sofia " => "K: Sofia ",
      "M: Marie " => "K: Marie ",
      "M: Anna " => "K: Anna ",
      "M: Maria " => "K: Maria ",
      "K: Martin " => "M: Martin ",
      "K: Lars " => "M: Lars ",
      "M A: " => "M: ",
      "M B: " => ",",
      "K A: " => "K: ",
      "K B: " => ","
    );
    
    public function index()
    {
        if ($this->loggedIn())
            $this->load->view('parser');   
        else
            $this->load->view('needlogin');
    }
    
    public function update($year = 2014)
    {
        if (!$this->loggedIn()) {
            $this->load->view('needlogin');
            return;
        }
        $v = intval($year);
        if (!(is_numeric($v) && $v > 2000))
            return;
        
        $this->load->dbutil();
        $this->loadWhitelist();
        $tableName = "results";
        
        $this->year = $year;
        
        $doc = new DOMDocument();
        @$doc->loadHTML(file_get_contents($this->url."?year=$year&type=11"));        
        $list = $doc->getElementById('marginal')->getElementsByTagName('p');
        foreach($list as $item)
            $this->parseItem($doc, $item, $tableName);
        
        echo json_encode(array(
            "posts" =>  array(
                "men" => $this->cntM,
                "women" => $this->cntW)));
    }
    
    public function create($tableName = 'results', $start = '2001', $end = '2014')
    {
        if (!$this->loggedIn())
            return;
        
        $v = intval($start);
        $ve = intval($end);
        if (!(is_numeric($v) && $v > 2000 && is_numeric($ve) && $ve >= $v)) {
            return;
        }
        $time = microtime(TRUE);
        $memory = memory_get_usage();
                
        $this->load->dbutil();
        $this->loadWhitelist();
        
        foreach(range(intval($start), intval($end), 1) as $year) {
            $this->year = $year;
            $doc = new DOMDocument();
            @$doc->loadHTML(file_get_contents($this->url."?year=$year&type=11"));      
            $list = $doc->getElementById('marginal')->getElementsByTagName('p');
            foreach($list as $item)
                $this->parseItem($doc, $item, $tableName);
        }
        echo "<p>Rejected club names (should be names or part of names):</p><ul>";
        foreach($this->clubRej as $r)
            echo "<li>$r</li>";
        echo "</ul>";
        print "<br/>M:{$this->cntM} W:{$this->cntW}<br>";
        
        print sprintf("%.2f",(microtime(TRUE)-$time)). ' seconds and '. (memory_get_usage()-$memory). ' bytes';
    }
    
    private function loadWhitelist() {
        require "data/club_whitelist.php";
        $whitelist = array();
        foreach($club_whitelist as $club)
            $whitelist[$club['partial_name']] = true;
        $this->whitelist = $whitelist;
    }
    
    private function parseItem($doc, $item, $table) {
        if ($item->childNodes->length == 0)
            return false;     
        $all_club = false;
        foreach($item->childNodes as $child)
            if ($all_club = $this->getClubFromHeadline(trim($child->textContent)))
                break;
        $item->removeChild($resHeadline = $item->getElementsByTagName('span')->item(0));
        $headline = $this->extractHeadline($resHeadline->textContent, $all_club);    
        
        // Catch Critical known input errors (or irregularities) 
        $str = str_replace(array_keys($this->errorGender), $this->errorGender, $item->textContent);
        
        // capture common input errors    
        $this->str_replace_patterns($str);
        
        $mpos = stripos($str, "M:");
        $kpos = stripos($str, "K:");
        if ($mpos === false || $kpos === false || $mpos < $kpos) {
            $women = $this->extractResults($str, "/K\s*\"fel hand\"\s*:\s*(.+)/i");
            $women = $this->extractResults($str, "/K\s*:\s*(.+)/i").",$women";    
            $men = $this->extractResults($str, "/M\s*\"fel hand\"\s*:\s*(.+)/i");
            $men = $this->extractResults($str, "/M\s*:\s*(.+)/i").",$men";
        } else {
            $men = $this->extractResults($str, "/M\s*\"fel hand\"\s*:\s*(.+)/i");
            $men = $this->extractResults($str, "/M\s*:\s*(.+)/i").",$men";
            $women = $this->extractResults($str, "/K\s*\"fel hand\"\s*:\s*(.+)/i");
            $women = $this->extractResults($str, "/K\s*:\s*(.+)/i").",$women";  
        }
        // Catch known input errors (or irregularities)
        // Remove all '.' characters (must be done in this order).
        // strtr is much slower 
        $mRes = $this->explodeNoEmpty(",", str_replace(".", "", str_replace(array_keys($this->errorMen), $this->errorMen, $men))); 
        $wRes = $this->explodeNoEmpty(",", str_replace(".", "", str_replace(array_keys($this->errorWomen), $this->errorWomen, $women)));
        $results = array();
        
        $keys = $this->getKeys($table, $this->year); 
        $validKeys = array();
        if ($men) array_push($validKeys, $this->push_if_new($results, $keys, 1, $mRes, $headline));
        if ($women) array_push($validKeys, $this->push_if_new($results, $keys, 0, $wRes, $headline));
        
        $this->addRecords($table, $results);
        $this->removeRows($table, $keys, $validKeys);
        return true;
    }
    
    private function push_if_new(&$results, $keys, $sex, $list, $headline) {
        $validKeys = array();
        foreach($list as $record) {   
            $post = $this->wrapRecord($this->parseRecord($record), $sex, $headline);
            if (count(array_keys($post)) != 12)
                continue;
            $validKeys[$post['key']] = true;
            if (isset($keys[$post['key']]))
                continue;
            array_push($results, $post);
        }
        $validKeys;
    }
    
    private function getKeys($table, $year) {        
        $interval = $year + 1;
        $this->db->select('key');
		$this->db->from($table);
		$this->db->where("date between '$year-01-01' and '$interval-01-01'");
        $res = $this->db->get();
        if ($res->num_rows() > 0) {
            $result = array();
            foreach($res->result() as $row)
                $result[$row->key] = true;
            return $result;
        }
        return array();
    }
    
    private function wrapRecord($rec, $sex, $head)
    {
        $rec['sex'] = $sex;
        $rec['date'] = $head['date'];
        $rec['location'] = $head['location'];
        if (!array_key_exists('club', $rec) || !$rec['club']) {
            $rec['club'] = $head['club'] ? trim($head['club']) : false;
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
          'club' => $club == "" ? false : $club,
          'score' => $score,
          'events' => $eventRes
        );
    }
    
    private function extractHeadline($str, $club)
    {
        $res = array();
        $res['club'] = $club;
        $pattern = "/^(\s*|\d{1,2}.)(\d{1,2}|\?{1,2})\.(\d{1,2}|\?{1,2})\s*(.+)$/";
        if (preg_match($pattern, $str, $match) == 1) {
            $d = $match[2][0] == "?" ? 1 : $match[2];
            $m = $match[3][0] == "?" ? 10 : $match[3];
            $res['date'] = $this->year."-".sprintf("%02d", $m)."-".sprintf("%02d", $d);
            $res['location'] = trim(str_replace("(Castorama)", "", $match[4]));      
            return $res;
        }
        return false;
    }
    
    private function extractResults(&$str, $pattern)
    {
        if (preg_match($pattern, $str, $match) == 1) { 
            $str = str_replace($match[0], "", $str);      
            return $match[1];
        }
        return '';
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
    
    // REWRITE TO PREG_REPLACE
    private function str_replace_patterns(&$str)
    {   
        $replacements = array(
          "/(\d),(\d{3})/" => "$1$2",
          "/(\s|^)\d\)\s/" => " ",
          "/(\d\.\d{3})\d/" => "$1",
          "/(\d{2})(\d\.\d{3})/" => "$1 $2",
          "/([^\s\d])(\d\.\d{3})/" => "$1 $2",      
          "/Anm:.*/" => "",
          "/(\((\d{3,4}\s*){4})[^\d\)]/" => "$1)$3, ",
          "/\(([^\(\)]+\()/" => "$1",
          "/\s\d(\d{2}\s*\d\.\d{3})/" => " $1"
        );
        $count = 0;
        $str = preg_replace(array_keys($replacements), $replacements, $str, -1, $count);
        while (preg_match("/Dag \d(.*)Dag \d(.*)/i", $str, $match) == 1) {
            $men = "";
            $women = "";
            //$w; $m;
            if (preg_match("/(K:.+)$/", $match[1], $w) == 1) {
                $women = $w[1]; 
                if (preg_match("/(M:.+)$/", str_replace($w[0], "", $match[1]), $m) == 1) {
                    $men = $m[1];
                }       
            } else if (preg_match("/(M:.+)$/", $match[1], $m) == 1) {
                $men = $m[1];
            }
            if (preg_match("/K:(.+)$/", $match[2], $w) == 1) {
                $women .= $women == "" ? "K: " : "," . $w[1];
                if (preg_match("/M:(.+)$/", str_replace($w[0], "", $match[2]), $m) == 1) {
                    $men .= $men == "" ? "M: " : ", " . $m[1];
                }       
            } else if (preg_match("/(M:.+)$/", $match[2], $m) == 1) {
                $men .= $men == "" ? "M: " : ", " . $m[1];
            }
            $str = str_replace($match[0], "$men. $women.", $str);      
        }
    }
    
    private function getEvents($str) 
    {
        $res = array(0, 0, 0, 0);
        $pattern = "/([^\d]|[\(\)])(((\d{3,5}|[xX])\s*){4}).*[\)\(]/";
        if (preg_match($pattern, $str, $match) == 1) {
            $res = $this->explodeNoEmpty(" ", $match[2]);
            for($i = 0; $i < count($res); ++$i)
                if (strlen($res[$i]) > 4)
                    $res[$i] = substr($res[$i], 0, 4);
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
    
    private function whitelistedClub($partialName) 
    {
        if (!$partialName || $partialName == "")
            return false;        
        if (isset($this->whitelist[$partialName]))
            return true;
        array_push($this->clubRej, $partialName." \t".$this->year);
        return false;
    }
    
    private function addRecords($table, $res) 
    {
        if (count($res) == 0) {
            return false;
        }    
        $init = 0;
        while ($init < count($res) && ($res[$init] == null || count(array_keys($res[$init])) != 12))
            ++$init;
        if ($res[$init]['club'] === false) $res[$init]['club'] = '';
        $insert_query = $this->db->insert_string($table, $res[$init++]);    
        $sql = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);     
        if ($res[$init - 1]['sex'] == 1) ++$this->cntM; else ++$this->cntW; 
        if (count($res) > $init) {
            for($i = $init; $i < count($res); ++$i) {        
                if ($res[$i]['sex'] == 1) ++$this->cntM; else ++$this->cntW;
                if (count(array_keys($res[$i])) == 12)
                    $sql .= ",(".$this->iterValues($res[$i]).")";
            }
        }
        $sql .= ";";        
        $this->db->query($sql);
        return true;
    }
    
    private function removeRows($table, $keys, $valid) 
    {
        $remove = array();
        foreach(array_keys($keys) as $key) {
            if (!isset($valid[$key])) {
                array_push($remove, $key);                
                $this->db->where('key', $key);
                $this->db->delete($table); 
            }
        }
        foreach($remove as $removed)
            echo "Row with key: $removed was removed.<br />";
    }
    
    private function iterValues($obj)
    {
        $str = "";
        foreach($obj as $e) {
            $str .= "'$e', ";
        }
        return substr($str, 0, strlen($str) - 2);
    }
        
    private function getClubFromHeadline($str)
    {    
        if (preg_match("/(Sa?mt?lin?ga|Båda)\s+(.+)/i", $str, $match) == 1) {
            return trim(str_replace(array("om ej annat anges", "."), "", $match[2]));
        }    
        return false;
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
}
