<?php
$hg_stream_title = '';
global $post;
extract( shortcode_atts( array(
    'hg_stream_title' => '',
), $atts ) );

?>

<div class="block mcomments">
    <div class="title-wrapper"><h3 class="widget-title"><i class="far fa-comments"></i>&nbsp;<?php if(!empty($hg_stream_title)) echo esc_attr($hg_stream_title); ?></h3></div>
    <div class="wcontainer">
    	<div id="hg-stream-app">
    		<p>{{message}}</p>
    	</div>
    </div>
</div>