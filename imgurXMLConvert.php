<?php
//php xml parse test
//set up db connection
$servername = "localhost";
$username = "USERNAME";
$password = "PASSWORDHERE";
$db="imgurTest";
// Create connection
$conn = new mysqli($servername, $username, $password, $db) or die("Error: Cannot create object");


$file = 'imgurTest.xml';
$xml=simplexml_load_file("imgurTest.xml") or die("Error: Cannot create object");
file_put_contents('imgurTestExport.txt', print_r($xml, true));
foreach($xml as $item) {
        $imgurID = $item->id;
        $title = $item->title;
        $description = $item->description;
        $datetime = $item->datetime;
        $type = $item->type;
        $animated = $item->animated;
        $width = $item->width;
        $height = $item->height;
        $size = $item->size;
        $views = $item->views;
        $bandwidth = $item->bandwidth;
        $vote = $item->vote;
        $favorite = $item->favorite;
        $nsfw = $item->nsfw;
        $section = $item->section;
        $account_url = $item->account_url;
        $account_id = $item->account_id;
        $topic = $item->topic;
        $topic_id = $item->topic_id;
        $link = $item->link;
        $is_album = $item->is_album;
        $comment_count = $item->comment_count;
        $ups = $item->ups;
        $downs = $item->downs;
        $points = $item->points;
        $score = $item->score;

	//sql insert/update
	$sql="
	INSERT INTO item (id, imgurID, title, description, datetime, type, animated, width, height, size, views, bandwidth, vote, favorite, nsfw, section, account_url, account_id, topic, topic_id, link, is_album, comment_count, ups, downs, points, score) VALUES (DEFAULT, '$imgurID', '$title', '$description', FROM_UNIXTIME('$datetime'), '$type', '$animated', '$width', '$height', '$size', '$views', '$bandwidth', '$vote', '$favorite', '$nsfw', '$section', '$account_url', '$account_id', '$topic', '$topic_id', '$link', '$is_album', '$comment_count', '$ups', '$downs', '$points', '$score')
	ON DUPLICATE KEY UPDATE views='$views',bandwidth='$bandwidth',comment_count='$comment_count',ups='$ups',downs='$downs',points='$points',score='$score';
	";
	$conn->query($sql);

	if ($is_album == "true") {
		$client_id = '3561e9164c35e4d';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/album/'.$imgurID.'?_format=xml');
		curl_setopt($ch, CURLOPT_POST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));

		$reply = curl_exec($ch);
		curl_close($ch);

		$album = simplexml_load_string($reply);
		file_put_contents('albumTest.txt', print_r($album, true));
		foreach($album as $thing) {
			$picCount = $album->images->item->count();
			$sql = "INSERT INTO albums (id, albumid, picturecount, views) VALUES (DEFAULT, '$imgurID', '$picCount', '$views')
				ON DUPLICATE KEY UPDATE views='$views';";
			$conn->query($sql);
		}

	}
}
$conn->close();
?>

