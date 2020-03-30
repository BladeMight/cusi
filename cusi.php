<?php

// info variables
$N = "cusi-php";
$ver = "1.0";
// print help
function help() {
  global $N, $ver;
  echo "     $N By BladeMight, v$ver\n";
  echo "$N <sibnet-url> <*commands-str>\n";
  echo "In command str you can use variables:\n";
  echo " \\\$du = direct url\n";
  echo " \\\$ti = title\n";
  echo " \\\$id = id\n";
  echo " \\\$au = accept url\n";
  echo "You must escape variables, so they can be processed in code!, Or you can use single quotes ''\n";
  echo "command str examples:\n";
  echo "  cusi <url> 'aria2c \"\$du\" -o \"\$ti\"'\n";
  echo "  cusi <url> 'ffmpeg -i \"\$du\"'\n";
  exit(0);
}
$DEBUG = 0;
function dw($str) {
  global $DEBUG;
  if ($DEBUG == 1) {
    print("$str\n");
  }
}
$i = 1;
if ($argc > 1) {
  if ($argv[$i] == "--debug") {
    $DEBUG = 1;
    $i++;
  }
}
// print($argc);
if ($argc <= $i || $argv[$i] == "-h") {
  help();
}
// curl, functions
function init_ch($u, $o = null) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $u);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 4.4.2; Nexus 4 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/34.0.1847.114 Mobile Safari/537.3");
  curl_setopt($ch, CURLOPT_REFERER, $u);
  curl_setopt($ch, CURLOPT_ACCEPT_ENCODING, "utf-8,cp1251");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
  if ($o != null) {
    curl_setopt_array($ch, $o);
  }
  return $ch;
}
function get_data($u, $o = null) {
  $ch = init_ch($u, $o);
  $data = curl_exec($ch);
  $ret = $data;
  if(curl_errno($ch)){
    throw new Exception(curl_error($ch));
  }
  curl_close($ch);
  return $ret;
}
function headers($raw) {
  $headers = array();
  $lines = explode("\n", $raw);
  $f = 0;
  foreach($lines as $line) {
    if ($f == 0) {
      dw("STATUS LINE: $line");
      $f = 1;
      $headers["STATUS"] = explode(" ", $line, 3);
      continue;
    }
    if ($line == null || trim($line[0]) == "") {
      continue;
    }
    dw("Header-Line: $line");
    list($k, $v) = explode(":", $line, 2);
    $xk = ucfirst(strtolower($k));
    if (!array_key_exists($xk, $headers)) {
      $headers[$xk] = trim($v);
    } else {
      dw("Add value: $xk: $v");
      $headers[$xk] = $headers[$xk] . " " . trim($v);
    }
  }
  return $headers;
}
function tocookies($setcookies) {
  $m = preg_replace('/(expires|path|domain|SameSite)=.*?;|\shttponly(;|\s|$)|\spath=[^;]*;?/i', '', $setcookies);
  $n = preg_replace('/;\s+/', '; ', $m);
  return $n;
}
// main use
$url = $argv[$i];
preg_match('/video.sibnet.ru\/(?:(?:shell\.php\?videoid=)|(?:video))(\d+)/', $url, $iurl);
$id = "";
$sib = 1;
// print_r($iurl);
$cookies = ""; $e_url = ""; $title = ""; $durl = ""; $aurl = ""; 
if (sizeof($iurl) >= 2) {
  $id = $iurl[1];
  // print($id);
  $e_url = "https://video.sibnet.ru/shell.php?videoid=$id";
  $mydata = get_data($e_url);
  // print($mydata);
  preg_match('/.*(\/v\/.*?mp4)", type.*/m', $mydata, $ddg);
  preg_match('/og:title" content="(.+?)"/m', $mydata, $ttg);
  $title = $ttg[1];
  $aurl = "https://video.sibnet.ru".$ddg[1];
  $myinfo = get_data($aurl, Array(CURLOPT_REFERER => $e_url, CURLOPT_HEADER => TRUE, CURLOPT_NOBODY => TRUE));
  $hed = headers($myinfo);
  $durl = "https:".$hed["Location"];

} else { // Not sibnet 
  $sib = 0;
  dw("Try with myvi/ourvideo.ru...");
  preg_match('/(ourvideo.ru|myvi).*embed/', $url, $myv);
  // print_r($myv);
  if (sizeof($myv) < 2) {
    dw("Not embed-url, try to get...");
    $myvh = get_data($url, Array(CURLOPT_HEADER => TRUE, CURLOPT_NOBODY => TRUE));
    $hed = headers($myvh);
    $cookies = tocookies($hed["Set-cookie"]);
    print "$cookies\n";
    $myvdata = get_data($url, Array(CURLOPT_HTTPHEADER => array("Cookie:".$cookies)));
    #$cookies = "Cookie: ".str_replace("Set-Cookie: ", "", $myvhed[4]. "; ".$myvhed[7]);
    // print($myvdata);
    preg_match('/content="(\/\/myvi.ru\/player\/embed.*?)"/m', $myvdata, $myve);
    $e_url = "https:".$myve[1];
    // get Unique User Id cookie
    $myvh2 = get_data($e_url, Array(CURLOPT_HEADER => TRUE, CURLOPT_NOBODY => TRUE, CURLOPT_HTTPHEADER => array("Cookie:".$cookies)));
    $hed2 = headers($myvh2);
    $cookies = $cookies . " " . tocookies($hed2["Set-cookie"]);
    $myvedata = get_data($e_url, Array(CURLOPT_COOKIE => $cookies));
    preg_match('/<title\>(.*?)\<\/title\>/m', $myvedata, $ttg);
    $title = $ttg[1];
    // print($title);
    preg_match('/.*?\("v=(.+?)".*/m', $myvedata, $ddg);
    $aurl = explode("&tp=", str_replace('\u0026', "&", urldecode($ddg[1])))[0];
    $myvhed = get_data($aurl, Array(CURLOPT_REFERER => $e_url, CURLOPT_HEADER => TRUE, CURLOPT_NOBODY => TRUE, CURLOPT_HTTPHEADER => array("Cookie:".$cookies)));
    $durl = headers($myvhed)["Location"];
  }

}
// print info, in debug mode
dw("Title: $title");
dw("Direct-URL: $durl");
dw("Embed-URL: $e_url");
dw("Video-ID: $id");
dw("Accept-URL: $aurl");
if ($cookies != "") {
  dw("Cookies: $cookies");
}
if ($argc > $i+1) {
  $cstr = $argv[$i+1];
  dw("Command str parse: $cstr");
  if ($cstr == "#echo") {
    print "$title\n$durl\n";
    if ($sib == 0) {
      print "Cookie: $cookies\n";
    }
  }
}
