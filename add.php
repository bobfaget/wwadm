<?php 
error_reporting(0);
include "sql.php";

// Currently In Page 3

$get_tv_maze_last_id = mysql_query("SELECT * FROM tvshows ORDER BY tv_maze_id DESC LIMIT 1");
$get_tv_maze_last_id = mysql_num_rows($get_tv_maze_last_id);
$tv_maze_page = $_GET['page'];

$get_shows = file_get_contents("http://api.tvmaze.com/shows?page=".$tv_maze_page);
$show_s = json_decode($get_shows, true);
if($show_s !== null) {
foreach($show_s as $show) {
	$find_show = mysql_query("SELECT * FROM tvshows WHERE tv_maze_id='".$show['id']."'");
	if(mysql_num_rows($find_show) > 0) {
		continue;
	} else {
		$show_info = file_get_contents("http://api.tvmaze.com/shows/".$show['id']);
		$info = json_decode($show_info, true);
		$show_name = $info['name'];
		$show_name = mysql_real_escape_string($show_name);
		$released = explode("-", $info['premiered']);
		$released = $released[0];
		$tv_maze_id = $info['id'];
		$genre = $info['genres'][0];
		$genre = mysql_real_escape_string($genre);
		$summary = strip_tags($info['summary']);
		$summary = mysql_real_escape_string($summary);
		$poster = $info['image']['medium'];
		$poster = mysql_real_escape_string($poster);
		$views = "0";
		$rank = "NA";
		$lw_rank = "NA";
		$status = $info['status'];
		$status = mysql_real_escape_string($status);
		$add_show = mysql_query("INSERT INTO tvshows(show_name, released, tv_maze_id, genre, summary, poster, views, rank, lw_rank, status) VALUES('$show_name', '$released', '$tv_maze_id', '$genre', '$summary', '$poster', '$views', '$rank', '$lw_rank', '$status')");
		if($add_show) {
			$get_episodes = file_get_contents("http://api.tvmaze.com/shows/".$show['id']."/episodes");
			$allepisodes = json_decode($get_episodes, true);
			foreach($allepisodes as $episode) {
				$get_show_id = mysql_query("SELECT * FROM tvshows WHERE tv_maze_id='".$tv_maze_id."'");
				$get_show_id = mysql_fetch_array($get_show_id);
				$my_show_id = $get_show_id['id'];
				$season_number = $episode['season'];
				$episode_number = $episode['number'];
				$episode_name = $episode['name'];
				$episode_name = mysql_real_escape_string($episode_name);
				$episode_summary = $episode['summary'];
				$episode_summary = strip_tags($episode_summary);
				$episode_summary = mysql_real_escape_string($episode_summary);
				$link_count = "0";
				$views = "0";
				$aired = $episode['airdate'];
				$aired = str_replace("-", "", $aired);
				$app_link = "_";
				$admin_link = "_";
				
				$add_episodes = mysql_query("INSERT INTO episodes(show_id, season, episode, episode_name, episode_summary, link_count, views, aired, app_link, admin_link) VALUES('$my_show_id', '$season_number', '$episode_number', '$episode_name', '$episode_summary', '$link_count', '$views', '$aired', '$app_link', '$admin_link')");
			}
			if($add_episodes) {
				echo "success";
			} else {
				echo "EPISODE ERROR: ".mysql_error();
			}
		} else {
			echo "SHOW ERROR: ".mysql_error();
		}
	}
}
} else {
	echo "No Data Found";
}
?>
