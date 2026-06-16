<?php
/**
 * The template for displaying content of search/archive entries as One Column.
 * @package ForeverWood
 * @since ForeverWood 1.0.0
*/
if ( has_post_format('image') ) {
get_template_part( 'content', 'archives-image' ); }
elseif ( has_post_format('audio') ) {
get_template_part( 'content', 'archives-audio' ); }
elseif ( has_post_format('video') ) {
get_template_part( 'content', 'archives-video' ); } 
elseif ( has_post_format('status') ) {
get_template_part( 'content', 'archives-status' ); }
else {
get_template_part( 'content', 'archives-standard' ); } ?>