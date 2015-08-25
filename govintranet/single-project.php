<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 */

get_header(); ?>

<?php 

if ( have_posts() ) while ( have_posts() ) : the_post(); 
	$chapter_header = false;
	$singletask = false;
	$pagetype = "";
	$taskpod = get_post($id);
	$current_task = $id;
	if ($post->post_parent != 0){
		$parent_guide = get_post($post->post_parent); 
		if ($parent_guide){
			$parent_guide_id = $parent_guide->ID; 
			if (!$parent_guide_id){
				$parent_guide_id = $post->ID;
			}
		}
	} else {
		$parent_guide_id = $post->ID;
	}
	$children_chapters = get_posts ("post_type=project&posts_per_page=-1&post_status=publish&post_parent=".$parent_guide_id."&orderby=menu_order&order=ASC");
	
	if (!$parent_guide && !$children_chapters){
		$singletask=true;
		$pagetype = "task";
		$icon = "chart-bar";
	} else {
		$pagetype = "guide";
		$icon = "chart-bar";
	};

	if ($children_chapters && !$parent_guide){
		$chapter_header=true;
	}

	if ($parent_guide){
		$parent_slug=$parent_guide->post_name;
		$parent_name=govintranetpress_custom_title($parent_guide->post_title); 
		$guidetitle =$parent_name;	
	}

	if (!$parent_guide){
		$guidetitle = govintranetpress_custom_title($post->post_title);
	}	
	?>

	<div class="col-lg-8 col-md-8 col-sm-8">
		<div class="row">
			<div class='breadcrumbs'>
				<?php if(function_exists('bcn_display') && !is_front_page()) {
					bcn_display();
					}?>
			</div>
		</div>
		<?php 

		if ($pagetype=="guide"):

		?>
		<div>
			<h1><?php echo $guidetitle; ?> <small><span class="dashicons dashicons-<?php echo $icon; ?>"></span> Project</small></h1>
			<?php 
			$podchap = get_post($parent_guide_id); 
			$alreadydone[]=$parent_guide_id;
			$children_chapters = get_posts("post_type=project&posts_per_page=-1&post_status=publish&post_parent=".$parent_guide_id."&orderby=menu_order&order=ASC");
			$totalchapters = count($children_chapters) + 1;
			$halfchapters = round($totalchapters/2,0,PHP_ROUND_HALF_UP); 
			?>
			<div class="chapters-container">
				<div class="col-lg-6 col-md-6">
					<div class="chapters">
						<nav role="navigation" class="page-navigation">
							<ol>
								<?php
									if ($chapter_header){
										echo "<li class='active'>";
										echo "<span class='part-label part-title'>".$guidetitle."</span>";
									} else {
										$chapname = $parent_name;
										$chapslug = $parent_slug;
										echo "<li><a href='".get_permalink($parent_guide_id)."'><span class='part-title'>{$chapname}</span></a>";
									}
									echo "</li>";
									$carray = array();
									$k=1; 
									foreach ($children_chapters as $chapt){
										if ($chapt->post_status=='publish'){
										$k++;
									if (($k == $halfchapters + 1) && ($totalchapters > 3) ):?>
							</ol>
						</nav>
					</div>
				</div>
				<div class="col-lg-6 col-md-6">
					<div class="chapters">
						<nav role="navigation" class="page-navigation">
							<ol start='<?php echo $k;?>'>
									
								<?php
								endif;
				
								echo "<li ";
								if ($id == $chapt->ID){
									 echo "class='active'";
									 $current_chapter=$k;
								}
								echo ">";
								$chapname = govintranetpress_custom_title($chapt->post_title);
								$chapslug = $chapt->post_name; 
								$carray[$k]['chapter_number']=$k;
								$carray[$k]['slug']=$chapslug;
								$carray[$k]['name']=$chapname;
								$carray[$k]['id']=$chapt->ID;
								$alreadydone[]=$chapt->ID;
								if ($chapt->ID==$current_task){
									echo "<span class='part-label part-title'>{$chapname}</span>";
								} else {
									echo "<a href='".get_permalink($chapt->ID)."'><span class='part-label part-title'>{$chapname}</span></a>";
								}
								echo "</li>";
								}
							}
?>
						</ol>
					</nav>
				</div>
			</div>
		</div>
	</div>
	
	<?php
				
	endif;
	
	if ($pagetype=="guide"){
		echo "<div>";

		echo "<div class='content-wrapper-notop'>";
			if ($current_chapter>1){
				echo "<h2>".$current_chapter.". ".get_the_title()."</h2>";
			} else {
				echo "<h2>Overview</h2>";
			}

			the_content(); 		

			if( have_rows('document_attachments') ) : 
				echo "<div class='alert alert-info'>";
				echo "<h3>Downloads <span class='dashicons dashicons-download'></span></h3>";
				    while ( have_rows('document_attachments') ) : the_row(); 
						$doc = get_sub_field('document_attachment'); //print_r($doc);
						echo "<p><a class='alert-link' href='".$doc['url']."'>".$doc['title']."</a></p>";
					endwhile;
				echo "</div>";
			endif;
			if ('open' == $post->comment_status) {
				 comments_template( '', true ); 
			}
			echo "</div>";
			
	        echo '<div class="row">';
	
	        if ($chapter_header){ // if on chapter 1
				
				echo '<div class="col-lg-12 chapterr"><a href="'.get_permalink($carray[2]["id"]).'">'.$carray[2]["name"].'&nbsp;<span class="dashicons dashicons-arrow-right-alt2"></span></a>';
				echo "</div>";
	        } elseif ($current_chapter==2) { // if on chapter 2
				echo '<div class="col-lg-6 col-md-6 chapterl"><a href="'.get_permalink($parent_guide_id).'" title="Navigate to previous part"><span class="dashicons dashicons-arrow-left-alt2"></span>&nbsp;Overview</a></div>';
	            if ($carray[3]['slug']){
					echo '<div class="col-lg-6 col-md-6 chapterr"><a href="'.get_permalink($carray[3]["id"]).'">'.$carray[3]["name"].'&nbsp;<span class="dashicons dashicons-arrow-right-alt2"></span></a></div>';
		        }
	        }   else { // we're deep in the middle somewhere
	        	$previous_chapter = $current_chapter-1; 
				$next_chapter = $current_chapter+1;

				echo '<div class="col-lg-6 col-md-6 chapterl"><a href="'.get_permalink($carray[$previous_chapter]["id"]).'" title="Navigate to previous part"><span class="dashicons dashicons-arrow-left-alt2"></span>&nbsp;'.govintranetpress_custom_title($carray[$previous_chapter]["name"]).'</a></div>';
	            if ($carray[$next_chapter]['slug']){
					echo '<div class="col-lg-6 col-md-6 chapterr"><a href="'.get_permalink($carray[$next_chapter]["id"]).'">'.govintranetpress_custom_title($carray[$next_chapter]["name"]).'&nbsp;<span class="dashicons dashicons-arrow-right-alt2"></span></a></div>';
				}
			}
			echo "</div>";
			echo "</div>";

			} else { ?>
				<h1><?php echo $guidetitle; ?> <small><span class="dashicons dashicons-<?php echo $icon; ?>"></span> <?php echo ucwords($pagetype); ?></small></h1>
				<?php
				the_content(); 

				$current_attachments = get_field('document_attachments');
				if ($current_attachments){
					echo "<div class='alert alert-info'>";
					echo "<h3>Downloads <span class='dashicons dashicons-download'></span></h3>";
					foreach ($current_attachments as $ca){
						$c = $ca['document_attachment'];
						echo "<p><a class='alert-link' href='".$c['url']."'>".$c['title']."</a></p>";
					}
					echo "</div>";
				}	

			if ('open' == $post->comment_status) {
				 comments_template( '', true ); 
			}
		}
		 ?>
		</div> <!--end of first column-->

		<div class="col-lg-4 col-md-4 col-sm-4">	
		
			<?php 
				$html='';
				$related = get_post_meta($id,'related',true);

				if ($related){
					foreach ($related as $r){ 
						$title_context="";
						$rlink = get_post($r);
						if ($rlink->post_status == 'publish' && $rlink->ID != $id ) {
							$taskparent=$rlink->post_parent; 
							if ($taskparent && in_array($rlink->post_type, array('task','project','team') ) ){
								$tparent_guide_id = $taskparent->ID; 		
								if ( $tparent_guide_id ) $taskparent = get_post($tparent_guide_id);
								if ( $taskparent ) $title_context=" (".govintranetpress_custom_title($taskparent->post_title).")";
							}		
							$html.= "<li><a href='".get_permalink($rlink->ID)."'>".govintranetpress_custom_title($rlink->post_title).$title_context."</a></li>";
						}
					}
				}
				
				//get anything related to this post
				$otherrelated = get_posts(array('post_type'=>array('task','news','project','vacancy','blog','team','event'),'posts_per_page'=>-1,'exclude'=>$related,'meta_query'=>array(array('key'=>'related','compare'=>'LIKE','value'=>'"'.$id.'"')))); 
				foreach ($otherrelated as $o){
					if ($o->post_status == 'publish' && $o->ID != $id ) {
								$taskparent=$o->post_parent; 
								$title_context='';
								if ($taskparent){
									$taskparent = get_post($taskparent);
									$title_context=" (".govintranetpress_custom_title($taskparent->post_title).")";
								}		
								$html.= "<li><a href='".get_permalink($rlink->ID)."'>".govintranetpress_custom_title($rlink->post_title).$title_context."</a></li>";
						}
				}

				if ($related || $otherrelated){
					echo "<div class='widget-box list'>";
					echo "<h3 class='widget-title'>Related</h3>";
					echo "<ul>";
					echo $html;
					echo "</ul></div>";
				}		
			$post_cat = get_the_category();
		
			$posttags = get_the_tags($parent_guide_id);
			if ($posttags) {
				$foundtags=false;	
				$tagstr="";
			  	foreach($posttags as $tag) {
			  		if (substr($tag->name,0,9)!="carousel:"){
			  			$foundtags=true;
			  			$tagurl = $tag->slug;
				    	$tagstr=$tagstr."<span><a class='label label-default' href='".site_url()."/tag/{$tagurl}/?type=project'>" . str_replace(' ', '&nbsp' , $tag->name) . '</a></span> '; 
			    	}
			  	}
			  	if ($foundtags){
				  	echo "<div class='widget-box'><h3>Tags</h3><p> "; 
				  	echo $tagstr;
				  	echo "</p></div>";
			  	}
			}
		?>
		</div>	
			
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
