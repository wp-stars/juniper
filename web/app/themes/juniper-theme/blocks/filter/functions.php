<?php

add_action(
    'wp_enqueue_scripts', function () {
        if (has_block('acf/filter')) {
            $time = time();
            $theme_path = get_template_directory_uri();

            wp_enqueue_style('filter-css', $theme_path . '/blocks/filter/style.css', array(), $time, 'all');
            wp_enqueue_script('filter-js', $theme_path . '/blocks/filter/script.js', array(), $time, true);

            $attributes = [];
            wp_enqueue_script( 'dashboardBlockFrontendScript', $theme_path . '/blocks/filter/build/frontend.js', array(
				'wp-blocks',
				'wp-element',
				'wp-editor',
				'wp-api',
				'wp-element',
				'wp-i18n',
				'wp-polyfill',
				'wp-api-fetch'
			), $time, true );
			wp_localize_script( 'dashboardBlockFrontendScript', 'filterData', $attributes );
        }
    }
);

add_filter(
    'timber/acf-gutenberg-blocks-data/filter',
    function ( $context ) {
        $post_type = $context['fields']['post_type'];
        $taxonomy = $context['fields']['taxonomy'];
        $data_arr = wps_get_filter_posts( $post_type, $taxonomy, [], 1);

        $post_type = get_post_type_object( $context['fields']['post_type'] );
        $data_arr['postName'] = $post_type->labels->name;
        $data_arr['postType'] = $context['fields']['post_type'];
        $data_arr['taxonomy'] = $taxonomy;
        $data_arr['style'] = $context['fields']['style'];
        $data_arr['restUrl'] = get_rest_url();

        // add lottie file paths to the data

        $context['data'] = json_encode($data_arr);
        return $context;
    }
);

function juniper_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'juniper_excerpt_length', 999 );

function acf_load_post_type_field_choices( $field ) {
    // Reset choices
    $field['choices'] = get_post_types();
    
    return $field;
    
}
add_filter('acf/load_field/name=post_type', 'acf_load_post_type_field_choices');


function acf_load_taxonomy_field_choices( $field ) {
    // Reset choices
    $field['choices'] = get_taxonomies();
    
    return $field;
}
add_filter('acf/load_field/name=taxonomy', 'acf_load_taxonomy_field_choices');

add_action( 'rest_api_init', function () {
    register_rest_route( 'wps/v1', '/data', array(
        'methods' => 'GET',
        'callback' => 'wps_filter_callback',
        'permission_callback' => '__return_true',
    ) );
  } 
);

function wps_filter_callback() {
    $terms = !empty($_GET['terms']) ? explode(',', $_GET['terms']) : [];
    $taxonomy = !empty($_GET['taxonomy']) ? $_GET['taxonomy'] : '';
    $page = !empty($_GET['page']) ? intval($_GET['page']) : 1;
    $post_type = !empty($_GET['post_type']) ? $_GET['post_type'] : '';

    return wps_get_filter_posts( $post_type, $taxonomy, $terms, $page );
}

function wps_get_filter_posts( $post_type, $taxonomy, $terms, $page ) {
    $data_arr = array();
    $tax_query = array();
    if($taxonomy && count($terms)) {
        
        $tax_query[] = array(
            'taxonomy'  => $taxonomy,
            'field'     => 'term_id',
            'terms'     => array_map(function ($val) { return intval($val); }, $terms),
            'operator'  => 'IN'
        );
    }

    $args =  array(
        'post_type' => $post_type,
        'paged' => $page
    );

    if(count($tax_query)) {
        $args['tax_query'] = $tax_query;
    }
    
    $initial_posts = new WP_Query($args);

    $post_arr = array();
    foreach ($initial_posts->posts as $post) {
        $post_obj = new stdClass();
        $post_obj->ID = $post->ID;
        $post_obj->fields = get_fields($post);
        $post_obj->excerpt = wp_trim_excerpt('', $post);
        $post_obj->terms = get_the_terms($post, $taxonomy) ? get_the_terms($post, $taxonomy) : [];
        $post_obj->post_title = $post->post_title;
        $post_obj->post_name = $post->post_name;
        $post_obj->post_author = get_the_author_meta('display_name', $post->post_author);
        $post_obj->post_date = get_the_date('d.m.Y', $post);
        $post_obj->featured_image = get_the_post_thumbnail_url($post);
        $post_obj->link = get_permalink($post);
        $post_arr[] = $post_obj;
    }

    $data_arr['posts'] = $post_arr;
    $data_arr['maxNumPages'] = $initial_posts->max_num_pages;

    $terms = get_terms( 
        array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        )
    );

    foreach ($terms as $term) {
        $term->fields = get_fields($term);
    }

    $data_arr['terms'] = $terms;

    return $data_arr;
}


