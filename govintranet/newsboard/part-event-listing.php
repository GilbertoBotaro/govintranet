<?php
	global $post;
	$thistitle = get_the_title($post->ID);
	$thisURL=get_permalink($id); 
	$output= "<div class='media newsboard-events'>";
	$output.= "<div class='media-left alignleft'>";
	$output.= "<a class='calendarlink' href='".$thisURL."'>";
	$output.= "<div class='calbox'>";
	$output.= "<div class='cal-dow'>".date('D',strtotime(get_post_meta($post->ID,'event_start_date',true)))."</div>";
	$output.= "<div class='caldate'>".date('d',strtotime(get_post_meta($post->ID,'event_start_date',true)))."</div>";
	$output.= "<div class='calmonth'>".date('M',strtotime(get_post_meta($post->ID,'event_start_date',true)))."</div>";
	$output.= "</div>";
	$output.= "</a>";
	$output.= "</div>";

	$output.= "<div class='media-body'>";
	$output.= "<h3 class='media-heading'>";
	$output.= "<a href='".$thisURL."'>";
	$output.= $thistitle;
	$output.= "</a>";
	$output.= "</h3>";
	$output.= get_the_excerpt();
	$output.= "</div></div><hr>";

	echo $output;
?>