<?php
get_header();

/* Start the Loop */
while ( have_posts() ) :
	the_post();

	the_content();

	the_post_navigation(
		array(
			'next_text' => '<p class="meta-nav">' . '下篇文章&gt;&gt;' . '</p><p class="post-title">%title</p>',
			'prev_text' => '<p class="meta-nav">' . '&lt;&lt;上篇文章' . '</p><p class="post-title">%title</p>',

		)
	);
endwhile; // End of the loop.

get_footer();
