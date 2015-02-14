<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 */

if ( get_post_format($post->ID) == 'link' ){
	$external_link = get_post_meta($post->ID,'external_link',true);
	if ($external_link){
		wp_redirect($external_link); 
		exit;
	}	
}

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<div class="col-lg-7 col-md-8 col-sm-8 white ">
				<div class="row">
					<div class='breadcrumbs'>
						<?php if(function_exists('bcn_display') && !is_front_page()) {
							bcn_display();
							}?>
					</div>
				</div>
				<?php 
				$video=null;
				//check if a video thumbnail exists, if so we won't use it to display as a headline image
				if (function_exists('get_video_thumbnail')){
					$video = get_video_thumbnail(); 
				}

				if (!$video){
					$ts = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'newshead' ); 
					$tt = get_the_title();
					$tn = "<img src='".$ts[0]."' width='".$ts[1]."' height='".$ts[2]."' class='img img-responsive' alt='".$tt."' />";
					if ($ts){
						echo $tn;
						echo wpautop( "<p class='news_date'>".get_post_thumbnail_caption()."</p>" );
					}
				}
				?>

				<h1><?php the_title(); ?></h1>
				<?php
				$article_date=get_the_date();
				$mainid=$post->ID;
				$article_date = date("j F Y",strtotime($article_date));	?>
				<?php echo the_date('j M Y', '<p class="news_date">', '</p>') ?>
				<?php 
					if ( has_post_format('video', $post->ID) ):
						echo apply_filters('the_content', get_post_meta( $post->ID, 'news_video_url', true));
					endif;
					?>
				<?php the_content(); ?>
				<?php
				$current_attachments = get_field('document_attachments');
				if ($current_attachments){
					echo "<div class='alert alert-info'>";
					echo "<h3>Downloads <i class='glyphicon glyphicon-download'></i></h3>";
					foreach ($current_attachments as $ca){
						$c = $ca['document_attachment'];
						echo "<p><a class='alert-link' href='".$c['url']."'>".$c['title']."</a></p>";
					}
					echo "</div>";
				}				
				
				if ('open' == $post->comment_status) {
					 comments_template( '', true ); 
				}
			 ?>

		</div> <!--end of first column-->
		<div class="col-lg-4  col-md-4 col-sm-4 col-lg-offset-1">	
			<?php
			$alreadydone = array();
			$related = get_post_meta($id,'related',true);

				if ($related){
					$html='';
					foreach ($related as $r){ 
						$title_context="";
						$rlink = get_post($r);
						if ($rlink->post_status == 'publish' && $rlink->ID != $id ) {
							$taskparent=$rlink->post_parent; 
							if ($taskparent){
								$tparent_guide_id = $taskparent->ID; 		
								$taskparent = get_post($tparent_guide_id);
								$title_context=" (".govintranetpress_custom_title($taskparent->post_title).")";
							}		
							$html.= "<li><a href='".get_permalink($rlink->ID)."'>".govintranetpress_custom_title($rlink->post_title).$title_context."</a></li>";
							$alreadydone[] = $r;
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
								$html.= "<li><a href='".get_permalink($o->ID)."'>".govintranetpress_custom_title($o->post_title).$title_context."</a></li>";
								$alreadydone[] = $o->ID;
						}
				}

				if ($related || $otherrelated){
					echo "<div class='widget-box list'>";
					echo "<h3 class='widget-title'>Related</h3>";
					echo "<ul>";
					echo $html;
					echo "</ul></div>";
				}
				$post_cat = get_the_terms($post->ID,'news-type');
				if ($post_cat){
					$html='';
					$catTitlePrinted=false;
					foreach($post_cat as $cat){
					if ($cat->slug != 'uncategorized'){
						if (!$catTitlePrinted){
							$catTitlePrinted = true;
						}
						$html.= "<span><a class='wptag t".$cat->term_id."' href='".site_url()."/news-type/".$cat->slug."'>".str_replace(" ","&nbsp;",$cat->name)."</a></span> ";
						}
					}	
					if ($html){
						echo "<div class='widget-box'><h3>Categories</h3>".$html."</div>";
					}
				}
				$posttags = get_the_tags();
				if ($posttags) {
					$foundtags=false;	
					$tagstr="";
				  	foreach($posttags as $tag) {
				  		if (substr($tag->name,0,9)!="carousel:"){
				  			$foundtags=true;
				  			$tagurl = $tag->slug;
					    	$tagstr=$tagstr."<span><a class='label label-default' href='".site_url()."/tag/{$tagurl}/?type=news'>" . str_replace(' ', '&nbsp' , $tag->name) . '</a></span> '; 
				    	}
				  	}
				  	if ($foundtags){
					  	echo "<div class='widget-box'><h3>Tags</h3><p> "; 
					  	echo $tagstr;
					  	echo "</p></div>";
				  	}
				}
		 	dynamic_sidebar('news-widget-area'); 
		 	wp_reset_postdata();



/*****************

AUTOMATED RELATED NEWS

Show 5 latest news stories, excluding the current post and any posts already manually entered as related 
If this post is a need to know story, show other need to know stories.
Otherwise check for recent news stories in the same categories as this post.
If still nothing found, show the latest news stories excluding need to know items
	
******************/
	
		 	// get meta to use for displaying related news

		 	$alreadydone[] = $post->ID;

			$update = has_post_format( 'status' , $post->ID ); 
			$newstype = get_the_terms( $post->ID , 'news-type' ); 
			if ($newstype):
				$terms = array();
				foreach ( $newstype as $n ){
					$terms[] = $n->slug;
				}
			endif;
			$newstags = get_the_tags( $post->ID); 
			if ($newstags):
				$ntags = array();
				$nidtags = array();
				foreach ( $newstags as $n ){
					$ntags[] = $n->slug; 
					$nidtags[] = $n->term_id;
				}
			endif;

			$recentitems = new WP_Query(); 
			
			
			// try to find other need to know stories
			$subhead = 'Other updates';
			if ($update) { 
				$recentitems = new WP_Query(array(
					'post_type'	=>	'news',
					'posts_per_page'	=>	5,
					'post__not_in'	=> $alreadydone,
					'tax_query' => array(array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-status'
						)),
					 ) );			
			}
			if ( $recentitems->found_posts == 0 && $terms && $ntags ):
			// no need to know stories so we'll look for others with the same tags AND category
				$subhead = 'Similar news';
				$recentitems = new WP_Query(array(
						'post_type'	=>	'news',
						'posts_per_page'	=>	5,
						'post__not_in'	=> $alreadydone,
						'tax_query' => array(
							'relation' => 'AND',
							array(
							'taxonomy' => 'news-type',
							'field' => 'slug',
							'terms' => $terms,
							'operator' => 'IN'
							),
							array(
							'taxonomy' => 'post_tag',
							'field' => 'slug',
							'terms' => $ntags,
							'operator' => 'IN'
							),
							),
						 ) );			
			endif;			
			if ( $recentitems->found_posts == 0 && $ntags): 
			// no stories with same tags and cats so we'll look for others with just the same tags
				$subhead = 'Similar news';
				$recentitems = new WP_Query(array(
						'post_type'	=>	'news',
						'posts_per_page'	=>	5,
						'post__not_in'	=> $alreadydone,
						'tag__in' => $nidtags,
						 ) );			
			endif;			
			if ( $recentitems->found_posts == 0 && $terms): 
			// still nothing found, we'll look for other stories in the same news categories as this story
				$subhead = 'Other related news';
				if ($newstype):
					$recentitems = new WP_Query(array(
						'post_type'	=>	'news',
						'posts_per_page'	=>	5,
						'post__not_in'	=> $alreadydone,
						'tax_query' => array(array(
							'taxonomy' => 'news-type',
							'field' => 'slug',
							'terms' => $terms,
							)),
						 ) );			
				endif;
			endif;
			if ( $recentitems->found_posts == 0 ): 
			// still nothing found, we'll load the latest 5 stories excluding any need to know
				$subhead = 'Recent news';
				$recentitems = new WP_Query(array(
					'post_type'	=>	'news',
					'posts_per_page'	=>	5,
					'post__not_in'	=> $alreadydone,
					'tax_query' => array(array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => 'post-format-status',
						'operator' => 'NOT IN',
						)),
					 ) );			
			endif;

			if ( $recentitems->have_posts() ):
				echo "<div class='widget-box nobottom'>";
				echo "<h3>".$subhead."</h3>";
				while ( $recentitems->have_posts() ) : $recentitems->the_post(); 
					if ($mainid!=$post->ID) {
						$thistitle = get_the_title($id);
						$thisURL=get_permalink($id);
						echo "<div class='widgetnewsitem'>";
						$image_url = get_the_post_thumbnail($id, 'thumbnail', array('class' => 'alignright'));
						echo "<h3><a href='{$thisURL}'>".$thistitle."</a></h3>";
						$thisdate= $post->post_date;
						$thisdate=date("j M Y",strtotime($thisdate));
						echo "<span class='news_date'>".$thisdate;
						echo "</span><br>".get_the_excerpt()."<br><span class='news_date'><a class='more' href='{$thisURL}' title='{$thistitle}' >Read more</a></span></div><div class='clearfix'></div><hr class='light' />";
					}
				endwhile; 
				echo "</div>";
			endif;
			wp_reset_query();
				?>
		</div> <!--end of second column-->
			
<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>