<?php
/**
 * The template for displaying content of post entries as Grid - Masonry.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/
if ( has_post_format('image') ) {
get_template_part( 'content', 'grid-image' ); }
elseif ( has_post_format('audio') ) {
get_template_part( 'content', 'grid-audio' ); } 
elseif ( has_post_format('video') ) {
get_template_part( 'content', 'grid-video' ); } 
elseif ( has_post_format('status') ) {
get_template_part( 'content', 'grid-status' ); }
else {
get_template_part( 'content', 'grid-standard' ); } ?>