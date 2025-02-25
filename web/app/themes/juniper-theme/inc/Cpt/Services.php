<?php

namespace Juniper\Cpt;

class Services {
	public string $cpt_slug;
	public string $cpt_name;

	public function __construct() {
		$this->cpt_slug = substr( 'services', 0, 20 );
		$this->cpt_name = substr( 'Services', 0, 20 );

		add_action( 'init', array( $this, 'register_custom_cpt' ) );
	}

	public function register_custom_cpt() {
		register_post_type(
			$this->cpt_slug,
			array(
				'labels'      => array(
					'name'          => $this->cpt_name,
					'singular_name' => $this->cpt_name,
				),
				'public'      => true,
				'has_archive' => false,
				'show_in_rest' => true,
				'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
				'rewrite'     => array( 
					'slug' => 'leistungen', 
					'with_front' => false
				),
				'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'author', 'comments', 'trackbacks', 'page-attributes', 'post-formats', 'custom-fields'),
			)
		);
	}

}
