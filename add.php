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
		$show_name = ucwords($show_name);
		$show_name = mysql_real_escape_string($show_name);
		$tv_maze_id = $info['id'];
		$viewtot = "0";
		
		$get_episodes = file_get_contents("http://api.tvmaze.com/shows/".$show['id']."/episodes");
		$allepisodes = json_decode($get_episodes, true);
		$new_ep_arr = array();
		foreach($allepisodes as $episode) {
			$e_arr = array();
			$e_arr['season'] = $episode['season'];
			$e_arr['episode'] = $episode['number'];
			$e_arr['link_count'] = "0";
			$e_arr['views'] = "0";
			$e_arr['aired'] = $episode['airdate'];
			$e_arr['app_link'] = "_";
			$e_arr['admin_link'] = "_";
			$e_arr['episode_id'] = uniqid(); 
			$new_ep_arr[] = $e_arr;
		}
		$episodes = serialize($new_ep_arr);
		$add_show = mysql_query("INSERT INTO tvshows(show_name, tv_maze_id, views, episodes) VALUES('$show_name', '$tv_maze_id', '$viewtot', '$episodes')");
	}
if($add_show) {
	echo "success<br>";
} else {
	echo "<br>Failed. ".mysql_error();
}
}
} else {
	echo "No Data Found";
}
?>
