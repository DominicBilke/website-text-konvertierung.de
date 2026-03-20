<?php

function post_request($url, array $params) {
$postdata = http_build_query(
    $params
);
$opts = array('http' =>
    array(
        'method' => 'POST',
        'header' => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);
$context = stream_context_create($opts);
$result = file_get_contents($url, false, $context);
return $result;
}
$target_file_to = "./uploads/download_".uniqid().".wav";
file_put_contents($target_file_to, post_request("http://api.voicerss.org/", array("key"=>"252f4f361f0740cfab5b5ffd9d1df649", "hl"=>$_POST['hl'],"src"=>$_POST['src'])));

echo "https://www.text-konvertierung.de/".$target_file_to;

//header("Location: http://api.voicerss.org/?key=252f4f361f0740cfab5b5ffd9d1df649&hl=".$_GET['hl'].'&src='.$_GET['src']);

?>