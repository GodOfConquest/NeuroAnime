<?php
	$link=$_GET['link'];
	$episodeNumber=$_GET['ep'];
	$title=$_GET['title'];
	$language='English';
	$description='';
	require_once(dirname(getcwd()).'/modules.php');
	if(strlen($episodeNumber)==1){
		$episodeNumber='0'.$episodeNumber;
	}
	$ep=modules::getQueryResults('SELECT episode.episode_id FROM episode INNER JOIN anime ON episode.anime_id=anime.anime_id WHERE anime.title=\'%s\' AND episode.episode_number=\'%s\' LIMIT 1', array($title,$episodeNumber));
	$episodeid=$ep['episode_id'];
	$array=explode('{break}',$link);
	$addData=false;
	foreach($array as $row){
		$pattern = '/(?<=http:\/\/).*?(?=\/)/';
		preg_match($pattern, $row, $matches);
		$addData=modules::addData(array('episode_id','video_link', 'video_description', 'language', 'MD5'),array($episodeid,$row,$description.$matches[0],$language,md5($row)),'videos');
		if(!$addData){
			break;
		}
	}
	
	if($addData){
		echo $episodeNumber.' 200';
	}else{
		echo $episodeNumber.' 404';
	}
	modules::closeConnection();
?>
