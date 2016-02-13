<?php
$client_id = '**********'; //client ID from imgur (register application)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/gallery/hot/viral/0?_format=xml');
curl_setopt($ch, CURLOPT_POST, FALSE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));

$reply = curl_exec($ch);
curl_close($ch);

//print($reply);
//put respoce in xml file
file_put_contents ('./imgurTest.xml', $reply)
?>
