<?php

/*
Plugin Name: View Count
Plugin URI: https://www.ryanb.com/
Description: A plugin that keeps track of post views
Version: 1.0
Author: Ryan Bankier
Author URI: https://www.ryanb.com/
Licence: UNLICENCED
*/

// function creates and counts number of times a post is viewed
function create_view(){

    
    if (!is_single()) return null;

    global $post; 

    // retrieves data from post_meta table based on id and meta_key = cooltech_views
    $views = get_post_meta($post->ID, 'cooltech_views', true);
    if (!$views) 
        $views = 0;

    $views++;

    // creates or stores in the table wp_post_meta where post_id = ID, meta_key = cooltech_views, meta_value = $views
    update_post_meta($post->ID, 'cooltech_views', $views);

    if ($views < 2 ){
        return $views ."view";
    }
    else{
        return $views ."views"; 
    }
    
    

    
    }
// wp hook tag triggers when page is loaded in single post      
add_action('wp_head', 'create_view');  


// this function is called in the theme content.php to display number views for each post
function cooltech_views(){
    global $post;
    $views = get_post_meta($post->ID, 'cooltech_views', true);
    if (!$views)
        $views = 0;

    if ($views < 2 ){
        return $views ." view";
        }
    else{
        // if statement will round view numbers if they are over a thousand and less than 1 million
        if ($views > 999 && $views < 1000000){
            $views = round($views / 1000);
            return $views ."K views"; 
        }
        // if statement will round view numbers if they are over  1 million
        elseif($views > 999999){
            $views = round($views / 1000000);
            return $views ."M views"; 
        }
        else{
            return $views ." views"; 
        }
        
        }
    }
// function queries the db for the top 10 viewed posts
function topview_list(){
    // sql query array
    $searchParams = [
        'posts_per_page'=>10,
        'post_type'=>'post',
        'post_status'=>'publish',
        'meta_key'=>'cooltech_views',
        'orderby'=>'meta_value_num',
        'order'=>'DESC'
    ];

    // stores reponse from query in $list
    $list = new WP_Query($searchParams);

    //if list has data then while displays each item in $list
    if ($list->have_posts()){
        global $post;
        echo '<ol>';
        while($list->have_posts()){
            $list->the_post();
            echo '<li><a href="'.get_permalink($post->ID).'">';
            the_title();
            $views = get_post_meta($post->ID, 'cooltech_views', true);

                // if statement displays views based on number of views.
                if ($views < 2 ){
                    echo '</a> - '. $views. ' view</li>';
                    }
                else{
                    if ($views > 999 && $views < 1000000){
                        $views = round($views / 1000);
                        echo '</a> - '. $views. ' K views</li>'; 
                    }
                    elseif($views > 999999){
                        $views = round($views / 1000000);
                        echo '</a> - '. $views. ' M views</li>';  
                    }
                    else{
                        echo '</a> - '. $views. ' views</li>';
                    }
                    
                    }

            
            
        }
        echo '</ol>';
    }


}    

