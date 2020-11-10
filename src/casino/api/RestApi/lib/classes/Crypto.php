<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 30/04/2018
 * Time: 02:23 PM
 */

namespace lib\classes;


class Crypto
{
    public $r;
    public $n;
    public $en;

    public function cr_encode()
    {
        $r = $this->r;
        $n = $this->n;

        for ($e = 0, $t = strlen($r); $t > $e; $e++) "{" == $r[$e] ? $r[$e] = "}" : "}" == $r[$e] && ($r[$e] = "{");
        $r = base64_encode($r);
        $o = $r;
        $o = str_replace("A", "07{8w", $o);
        $o = str_replace("B", "0d9@8w", $o);
        $o = str_replace("C", "07s&98w", $o);
        $o = str_replace("D", "}0a678w", $o);
        $o = str_replace("E", "0z26a", $o);
        $o = str_replace("F", "cc%11", $o);
        $o = str_replace("G", "r85t896", $o);
        $o = str_replace("H", "z254d7w", $o);
        $o = str_replace("I", "ldkfo8", $o);
        $o = str_replace("Q", "ops8", $o);
        $o = str_replace("=", "gkapdsgvkxp2s5v8yb", $o);
        $n = base64_encode(sha1($n).md5($n));
        $obj = array("start"=> array("data"=>$n, "cipher"=> base64_encode($n)), "end"=> array("badg"=> base64_encode(md5($n)), "bin"=> $o), "log"=>array("DT"=> date(DATE_RFC822), "D"=> "IOS || ANDROID || WINPHONE || OTHER OS"));
        $obj = json_encode($obj);
        return($obj);
    }

    public function cr_decode()
    {
        $en = $this->en;

        $en = json_decode($en, true);
        $en = $en['end']['bin'];
        $en = str_replace("gkapdsgvkxp2s5v8yb", "=",$en);
        $en = str_replace("ops8", "Q",$en);
        $en = str_replace("ldkfo8", "I",$en);
        $en = str_replace("z254d7w", "H",$en);
        $en = str_replace("r85t896", "G",$en);
        $en = str_replace("cc%11", "F",$en);
        $en = str_replace("0z26a", "E",$en);
        $en = str_replace("}0a678w", "D",$en);
        $en = str_replace("07s&98w", "C",$en);
        $en = str_replace("0d9@8w", "B",$en);
        $en = str_replace("07{8w", "A",$en);
        $en = base64_decode($en);
        for ($e = 0, $t = strlen($en); $t > $e; $e++) "{" == $en[$e] ? $en[$e] = "}" : "}" == $en[$e] && ($en[$e] = "{");
        return($en);
    }
}