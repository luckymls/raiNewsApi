<?php

function estrai($url, $inizio, $fine, $includi){
$get = file_get_contents($url);

$limiter = $inizio;
$limiter2  = $fine;
$lenght = strlen($limiter);
$lenght2 = strlen($limiter2);
$pos1 = strpos($get, $limiter);   

$pos2 = strpos($get, $limiter2 , $pos1); 


$text = substr($get,$pos1+$lenght, $pos2-$pos1+$lenght2); 


$text = html_entity_decode($text);
$pos1 = strpos($text, $limiter);   
$pos2 = strpos($text, $limiter2 , $pos1); 

if($includi){
$text = $limiter.substr($text, $pos1, $pos2).$limiter2;}else{
$text = substr($text, $pos1, $pos2);
}
return $text;
}


if($_GET[get_news]){
$limiter = '<ul data-itemlimit="10" id="tic">';
$limiter2  = '</ul>';
$articolo = estrai("http://rainews.it", $limiter, $limiter2, false);

$val = $_GET[get_news];
$e = explode('<li>', $articolo);
$e_count = substr_count($articolo, '<li>');
$arg = array();
if($val != 'true'){
if(!is_numeric($val)){$false_num = 1; goto a;}
if($val > 10){
$e_count = 10;}elseif($val < 1){
$e_count = 1;}elseif($val > 0 && $val < 11){
$e_count = $val;}
}
while($i < $e_count){
$i++;
$trait = $e[$i];

#Time
$e_temp = explode("<span>", $trait);
$e_temp = $e_temp[1];
$ora = explode("</span>", $e_temp);
$ora = $ora[0];
#Content
$e_temp = explode("</a>", $trait);
$e_temp = $e_temp[0];
$content = explode('">',$e_temp);
$content = $content[1];

#Link
$e_temp = explode('<a href="', $trait);
$e_temp = $e_temp[1];
$link = explode('">', $e_temp);
$link = "http://www.rainews.it".$link[0];

#Description
$link_content = file_get_contents($link);
$e_temp = explode('<meta property="og:description" content="', $link_content)[1];
$description = explode('">', $e_temp)[0];

#Result
$arg[] = array(
'ok' => 'true',
'id' => $i,
'article' => array(
'time' => $ora,
'title' => $content,
'description' => $description,
'link' => $link));
}

a:
if($false_num){
#Error 400
$arg = array(
'ok' => 'false',
'error_code' => '400',
'description' => "accepted only numeric values");
}
echo json_encode($arg, JSON_UNESCAPED_UNICODE);
}else{

#Error 404
$arg = array(
'ok' => 'false',
'error_code' => '404',
'description' => "method not found");

echo json_encode($arg, JSON_UNESCAPED_UNICODE);

}


?>
