<?php

/**
 * User Profile
 *
 * @package bbPress
 * @subpackage Theme
 */

$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
global $wpdb;

?>
	<?php do_action( 'bbp_template_before_user_profile' ); ?>

	<div id="bbp-user-profile" class="bbp-user-profile">
		<h2 class="entry-title"><?php _e( 'Profile', 'bbpress' ); ?></h2>
		<div class="bbp-user-section">

			<?php if ( bbp_get_displayed_user_field( 'description' ) ) : ?>

				<p class="bbp-user-description"><?php bbp_displayed_user_field( 'description' ); ?></p>

			<?php endif; ?>

			<p class="bbp-user-forum-role"><?php  printf( __( 'Forum Role: %s',      'bbpress' ), bbp_get_user_display_role()    ); ?></p>
			<p class="bbp-user-topic-count"><?php printf( __( 'Topics Started: %s',  'bbpress' ), bbp_get_user_topic_count_raw() ); ?></p>
			<p class="bbp-user-reply-count"><?php printf( __( 'Replies Created: %s', 'bbpress' ), bbp_get_user_reply_count_raw() ); ?></p>
		</div>
	</div><!-- #bbp-author-topics-started -->

	<?php do_action( 'bbp_template_after_user_profile' ); ?>

</div></div></div> <!-- close bbPress divs so that we can hide the element -->

<div class="row" id='staff-profile'>

	<div class="col-lg-2 col-md-2 col-sm-3">

		<?php
		$user_id = bbp_get_displayed_user_field( 'id' ); 
		$poduser = get_userdata($user_id);		
	
	 	if (function_exists('get_wp_user_avatar')){	
				$imgsrc = get_wp_user_avatar_src($user_id,'thumbnail');				
				if ($directorystyle==1){
					echo "<img class='img img-circle' ";
				}else{
					echo "<img class='img' ";
				}
				echo "src='".$imgsrc."' width='145'  height='145' alt='";
				bbp_displayed_user_field( 'first_name' );
				echo " ";
				bbp_displayed_user_field( 'last_name' );
				echo "' />";
		} else {
			$imgsrc = get_avatar($user_id,'thumbnail');				
			echo $imgsrc;
		}

		if (current_user_can('edit_themes')) echo "<br><br><p><a href='".site_url()."/wp-admin/user-edit.php?user_id=".$user_id."'>Edit profile</a></p>";
		elseif ( is_user_logged_in() && get_current_user_id() == $user_id ) echo "<br><p><br><a href='".site_url()."/wp-admin/profile.php'>Edit profile</a></p>";
	?>
	</div>
	<div class="col-lg-5 col-md-5 col-sm-9">
		<?php
		  $teams = get_user_meta($user_id,'user_team',true);
			if ($teams) {
			echo '<h3 class="contacthead">Team</h3>';

				$teamlist = array(); //build array of hierarchical teams
			  		foreach ((array)$teams as $t ) { 
			  			$team = get_post($t);
			  		    $teamid = $team->ID;
			  		    $teamurl= $team->post_name;
			  		    $teamparent = $team->post_parent; 
			  		    
			  		    while ($teamparent!=0){
			  		    	$parentteam = get_post($teamparent); 
			  		    	$parentURL = $parentteam->post_name;
			  		    	$parentname =govintranetpress_custom_title($parentteam->post_title); 
				  			$teamlist[]= " <a href='".site_url()."/team/{$parentURL}'>".$parentname."</a>";   
				  			$teamparent = $parentteam->post_parent; 
			  		    }
			  		    
			  			$teamlist[]= " <a href='".site_url()."/team/{$teamurl}'>".govintranetpress_custom_title($team->post_title)."</a>";
			  			echo "<strong>Team:</strong> ".implode(" &raquo; ", $teamlist)."<br>";
					$teamlist=array();

					}
			}  
			$jt = get_user_meta($user_id, 'user_job_title',true );
			if ($jt) echo "<strong>Job title: </strong>".$jt."<br>";
			$jt = get_user_meta( $user_id, 'user_grade',true ); 
			
			if ($jt) {
				$jt = get_term($jt, 'grade', ARRAY_A);
				if ($jt['name']) echo "<strong>Grade: </strong>".$jt['name']."";
			}
			?>
		<h3 class="contacthead" >Contact</h3>

		<?php if ( bbp_get_displayed_user_field( 'user_telephone' ) ) : ?>

			<p class="bbp-user-description"><i class="dashicons dashicons-phone"></i> <a href="tel:<?php echo str_replace(" ", "",  get_user_meta($user_id, 'user_telephone',true ) ); ?>"><?php bbp_displayed_user_field( 'user_telephone' ); ?></a></p>

		<?php endif; ?>
		<?php if ( bbp_get_displayed_user_field( 'user_mobile' ) ) : ?>

			<p class="bbp-user-description"><i class="dashicons dashicons-smartphone"></i> <a href="tel:<?php echo str_replace(" ", "", get_user_meta($user_id, 'user_mobile',true ) ); ?>"><?php bbp_displayed_user_field( 'user_mobile' ); ?></a></p>

		<?php endif; ?>
		<?php if ( bbp_get_displayed_user_field( 'user_email' ) ) : ?>

			<p class="bbp-user-description"><a href="mailto:<?php bbp_displayed_user_field( 'user_email' ); ?>">Email <?php bbp_displayed_user_field( 'first_name' ); echo " "; bbp_displayed_user_field( 'last_name' ); ?></a></p>

		<?php endif; ?>
		
		<?php if ( bbp_get_displayed_user_field( 'user_twitter_handle' ) ) : ?>
			<p class="bbp-user-description"><a href="https://twitter.com/<?php bbp_displayed_user_field( 'user_twitter_handle' ); ?>">Twitter</a></p>

		<?php endif; ?>
		<?php if ( bbp_get_displayed_user_field( 'user_working_pattern' ) ) : ?>

			<h3 class="contacthead" >Working pattern</h3>
			<?php bbp_displayed_user_field( 'user_working_pattern' ); ?>

		<?php endif; ?>
		<?php if ( bbp_get_displayed_user_field( 'description' ) ) : ?>

			<h3 class="contacthead" >Roles and responsibilities</h3>
			<?php bbp_displayed_user_field( 'description' ); ?>
		<?php endif; ?>
		<?php
		  $skills = get_user_meta($user_id,'user_key_skills',true);
		  if ($skills){
			  echo "<h3 class='contacthead'>Key skills and experience</h3>";
			  echo wpautop($skills);
		  }
		  $poduser = get_user_meta($user_id,'user_team',true);
		  $uqblog = $wpdb->get_results("select ID from $wpdb->posts where post_author = ".$author." and post_type='blog' and post_status='publish' order by post_date DESC limit 3;",ARRAY_A);
		  $uqforum = $wpdb->get_results("select ID from $wpdb->posts where post_author = ".$author." and (post_type='topic' or post_type='forum' or post_type='reply') and post_status='publish' order by post_date DESC limit 3;",ARRAY_A);
if (count($uqblog)>0 || count($uqforum) > 0):
?>
			<h3 class="contacthead" >On the intranet</h3>
<?php if (count($uqblog)>0):?>
			<h4><?php _e( 'Blog posts', 'bbpress' ); ?></h4>
			<ul>
			<?php foreach ($uqblog as $u){ //print_r($u);
				$utitle = get_the_title($u['ID']); 
				$ulink = get_permalink($u['ID']); 
				echo "<li><a href='".$ulink."'>".$utitle."</a></li>";
			}
			?>
			</ul>
<?php endif; ?>
<?php if (count($uqforum)>0):?>
<h4><?php _e( 'Forum posts', 'bbpress' ); ?></h4>
			<ul>
			<?php foreach ($uqforum as $u){ //print_r($u);
				$utitle = get_the_title($u['ID']); 
				$ulink = get_permalink($u['ID']); 
				echo "<li><a href='".$ulink."'>".$utitle."</a></li>";
			}
			?>
			</ul>
<?php endif; ?>
<?php endif; ?>
		
	</div>

		<div class="clearfix col-lg-5 col-md-5 col-sm-12">

<script>
jQuery('.tlink').tooltip();
</script>

		<?php 
			
				$poduserparent = get_user_meta( $user_id , 'user_line_manager', true); //print_r($poduserparent);
				$poduserparent = get_userdata($poduserparent);

				echo "<div class='panel panel-default'>

				<div class='panel-heading oc'>Organisation tree</div>
				<div class='panel-body'>
				<div class='oc'>";
				if ($poduserparent){

					if (function_exists('get_wp_user_avatar_src')){
						$image_url_src = get_wp_user_avatar_src($poduserparent->ID, 'thumbnail'); 
						$avatarhtml = "<img src=".$image_url_src." width='60' height='60' alt='".$poduserparent->display_name."' class='img";
						$directorystyle = get_option('options_staff_directory_style'); // 0 = squares, 1 = circles
						if ($directorystyle==1){
							$avatarhtml.= ' img-circle';
						} 
						$avatarhtml.="' />";
					} else {
						$avatarhtml = get_avatar($poduserparent->user_id,60,'',$poduserparent->display_name);
					}
						echo "<a title='".$poduserparent->display_name."' href='".site_url()."/staff/".$poduserparent->user_nicename."/'>".$avatarhtml."</a>";						
					echo "<p><a href='".site_url()."/staff/".$poduserparent->user_nicename."/'>".$poduserparent->display_name."</a><br>";
					echo get_user_meta($poduserparent->ID,'user_job_title',true);
					echo "</p>";
					echo "<p><i class='dashicons dashicons-arrow-up-alt2'></i></p>";
				}

				echo "<p><strong>";
				bbp_displayed_user_field( 'display_name' );
				echo "<br>".get_user_meta($user_id,'user_job_title',true);
				echo "</strong></p>";
				$q = "select meta_value as ID, user_id, display_name from $wpdb->users join $wpdb->usermeta on $wpdb->users.ID = $wpdb->usermeta.user_id where $wpdb->usermeta.meta_key='user_line_manager' and $wpdb->usermeta.meta_value = ".$user_id;
								
				$poduserreports = $wpdb->get_results($q,ARRAY_A);
				if (count($poduserreports)>0){
				
					echo "<p><i class='dashicons dashicons-arrow-down-alt2'></i></p>";
						echo "<p id='directreports'>";

					foreach ($poduserreports as $p){ 
						$pid = $p['user_id'];
	                    $u = get_userdata($pid);//print_r($u);
	                    $jobtitle = get_user_meta($pid, 'user_job_title', true);
	                    if ($jobtitle) $jobtitle = " - ".$jobtitle;
						$imgstyle='';
						if (function_exists('get_wp_user_avatar')){						
							$imgsrc = get_wp_user_avatar_src($pid,'thumbnail','left');		
							$imgstyle.=" width='50' height='50'";
							if ($directorystyle==1){
								 $imgstyle.=" class='img img-circle'";
							}else{
								 $imgstyle.=" class='img'";
							}
							
							echo "<a class='tlink' data-placement='right' data-original-title = '".$u->user_nicename."' title='".$u->display_name.$jobtitle."'  href='".site_url()."/staff/".$u->user_nicename."'><img src='".$imgsrc."' ".$imgstyle." alt='".$u->display_name."'/></a>";
						} else { 
						$imgsrc = get_avatar($user_id,'thumbnail','',$u->display_name);				
						$imgsrc = str_replace('height=\'96\'', 'height="50"', $imgsrc);
						$imgsrc = str_replace('width=\'96\'', 'width="50"', $imgsrc);
						echo "<a title='".$u->display_name."' href='".site_url()."/staff/".$u->user_nicename."'>".$imgsrc."</a>";
						}
					}
					echo "</p>";
				}

				echo "</div></div>";
	
		?></div>

	</div>

	<?php do_action( 'bbp_template_after_user_profile' ); ?>
		</div>

		</div>
			
	</div>	
</div>