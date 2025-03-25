<?php
function mon_theme_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    register_nav_menus(array(
        'main-menu' => __('Menu Principal', 'mon-theme'),
    ));
}
add_action('after_setup_theme', 'mon_theme_setup');

function mon_theme_enqueue_scripts() {
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'mon_theme_enqueue_scripts');