<?php

add_action( 'wp_enqueue_scripts', 'kryss_24h_enqueue_styles' );

function kryss_24h_enqueue_styles() {
    wp_enqueue_style(
        'kryss-24h-style',
        get_stylesheet_uri()
    );
}

add_filter('wp_feed_cache_transient_lifetime',create_function('$a', 'return 1200;')); // Speed upp retrieval of rss feed to widget 20 mins.


function create_kryss_race_tax() {
    register_taxonomy(
        'kryss_organizer_tax',
        array( 'post', 'page' ),
        array(
            'labels' =>
              array(
        'name'               => _x( 'Arrangörer', 'taxonomy general name' ),
        'singular_name'          => _x( 'Arrangör', 'taxonomy singular name' ),
        'search_items'           => __( 'Sök arrangörer' ),
        'popular_items'          => __( 'Mest aktiva arrangörer' ),
        'all_items'          => __( 'Alla arrangörer' ),
        'parent_item'        => null,
        'parent_item_colon'      => null,
        'edit_item'          => __( 'Redigera arrangör' ),
        'update_item'        => __( 'Redigera arrangör' ),
        'add_new_item'           => __( 'Lägg till en ny arrangör' ),
        'new_item_name'          => __( 'Namet på en ny arrangör' ),
        'separate_items_with_commas' => __( 'Räkna upp arrangörer med kommatecken emellan' ),
        'add_or_remove_items'    => __( 'Lägg till eller ta bort arrangörer' ),
        'choose_from_most_used'      => __( 'Välj från de aktivaste arrangörerna' ),
        'not_found'          => __( 'Hittar inga arrangörer.' ),
        'menu_name'          => __( 'Arrangörer' ),
        ),
            'rewrite' => array( 'slug' => 'arrangorer' ),
            'show_in_rest' => true,
            'hierarchical' => true,
        )
    );
}

function no_self_ping( &$links ) {
    $home = get_option( ‘home’ );
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, $home ) )
            unset($links[$l]);
}

add_action( ‘pre_ping’, ‘no_self_ping’ );

function disable_self_trackback( &$links ) {
    foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( ‘home’ ) ) )
            unset($links[$l]);
}

add_action( ‘pre_ping’, ‘disable_self_trackback’ );

function namespace_enqueue_block_variations() {
	wp_enqueue_script( 'namespace-enqueue-block-variations', get_theme_file_uri( '/register-block-variations.js' ), array( 'wp-blocks', 'wp-dom', 'wp-edit-post' ), wp_get_theme()->get( 'Version' ), true );
}


add_action( 'init', 'create_kryss_race_tax' );
add_action( 'enqueue_block_editor_assets', 'namespace_enqueue_block_variations' );


/**
 * Register the Article Categories variation for the Post Terms block.
 */
wp.domReady( () => {
    wp.blocks.registerBlockVariation(
        'core/post-terms',
        {
            name: 'article-category',
    		title: 'Article Categories',
    		icon: 'category',
    		isDefault: false,
    		attributes: { term: 'article-category' },
        },
    );
} );
