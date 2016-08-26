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
		$custom_show = "0";
		$add_show = mysql_query("INSERT INTO tvshows(show_name, tv_maze_id, views, custom_show) VALUES('$show_name', '$tv_maze_id', '$viewtot', '$custom_show')");
if($add_show) {
		$get_episodes = file_get_contents("http://api.tvmaze.com/shows/".$show['id']."/episodes");
		$allepisodes = json_decode($get_episodes, true);
		foreach($allepisodes as $episode) {
			$e_arr = array();
			$show_id = mysql_query("SELECT * FROM tvshows WHERE tv_maze_id=".$show['id']);
			$my_show_id = mysql_fetch_array($show_id);
			$e_arr['show_id'] = "'".$my_show_id['id']."'";
			$e_arr['season'] = "'".$episode['season']."'";
			$e_arr['episode'] = "'".$episode['number']."'";
			$e_arr['link_count'] = "'0'";
			$e_arr['views'] = "'0'";
			$aired = str_replace("-", "", $episode['airdate']);
			$e_arr['aired'] = "'".$aired."'";
			$e_arr['app_link'] = "''";
			$e_arr['admin_link'] = "''";
			$e_arr['custom_episode'] = "'0'";
			$episodes = implode(", ", $e_arr);
			$add_episodes = mysql_query("INSERT INTO episodes(show_id, season, episode, link_count, views, aired, app_link, admin_link, custom_episode) VALUES(".$episodes.")");
		}
		if($add_episodes) {
			echo "success";
		} else {
			echo "fail. ".mysql_error();
		}
} else {
	echo "<br>Failed. ".mysql_error();
}
}
}
} else {
	echo "No Data Found";
}
?>
