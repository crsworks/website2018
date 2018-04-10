<?php

class BenjaminHeroContent
{

    public $post_id;
    public $template;

    public $hasFeaturedVideo = false;
    public $hasFeaturedImage = false;
    public $hasFeaturedPost = false;

    public function __construct($post_id, $template, $currentpage)
    {

        $this->post_id = $post_id;
        $this->template = $template;
        $this->currentpage = $currentpage;

    }

    public function __toString()
    {

        $func = $this->currentpage.'Content';
        $func = array($this, $func);

        return call_user_func($func);
    }

    /**
     * The Feed title will either show the author tagline, category / tag tag line
     * The date (month / year), search results, or the post type's featured post
     * @return [type] [description]
     */
    public function getContent()
    {

        $func = $this->currentpage.'Content';
        $func = array($this, $func);

        return call_user_func($func);

    }


    // the fallback
    public function defaultContent()
    {
        $post = get_queried_object();
        if( $post->post_title)
            $title = $post->post_title;
        elseif($post->name)
            $title = $post->name;

        return '<h1 class="hero__title">' . $title .'</h1>';
    }


    // the 404 page has special powers
    public function _404Content()
    {
        /**
         * the 404 settings
         *
         * returns:
         * $content
         * $pid
         * $header_page
         *
         */
        extract(benjamin_get_404_settings());

        if($header_page) {
            $page = get_page($header_page);
            return apply_filters('the_content', $page->post_content);
        } else {

            $output = '';
            $output .= '<span class="hero__pre-title">';
                $output .= '<i class="fa fa-question-circle" aria-hidden="true"></i>';
                $output .= '404';
            $output .= '</span>';

            $output .= '<h1 class="hero__title">Page not found</h1>';

            return $output;
        }

    }

    /**
     * If we are on a single post, CPT or page
     *
     * We need to get its title and meta, or it's featured video if its a
     * post format of video
     * @return [type] [description]
     */
    public function singularContent()
    {
        $output = '';

        $format = get_post_format();

        if( $this->getPostFormatContent($format) )
            $output .= $this->getPostFormatContent($format);
        else
            $output .= $this->getSingularTitle();


        return $output;
    }


    public function getPostFormatContent($format)
    {
        $func = 'get'.ucfirst($format);

        if( method_exists($this, $func) && call_user_func( array($this, $func)) )
            return call_user_func( array($this, $func));
        else
            return false;

    }


    /**
     * If we are on a single post or a page...
     * then we grab its title and meta data (if a post)
     * @return [type] [description]
     */
    public function getSingularTitle()
    {

        $output = '';

        $output .= '<h1 class="hero__title">'.get_the_title().'</h1>';

        if ( 'page' !== get_post_type() ) :
            $output .= '<div class="post-meta">';
                $output .= benjamin_get_hero_meta();
            $output .= '</div>';
        endif;

        return $output;
    }


    /**
     * If the current post is of the post format "video" then lets get that video
     * @return [type] [description]
     */
    public function getAudio()
    {
        global $post;
        $src = get_post_meta($post->ID, '_post_format_audio', true);


        if(!$src)
            return null;


        $output = '';

        $output .= '<div class="hero__pre-title ">';
            $output .= '<span class="usa-label">';
                $output .= '<i class="fa fa-volume-up" aria-hidden="true"></i>';
                $output .= 'Play Audio';
            $output .= '</span>';
        $output .= '</div>';


        $output .= benjamin_get_the_audio_markup($src);
        // $output .= '<h1 class="hero__title">'.get_the_title().'</h1>';

        return $output;
    }


    /**
     * If the current post is of the post format "video" then lets get that video
     * @return [type] [description]
     */
    public function getVideo()
    {
        global $post;
        $url = get_post_meta($post->ID, '_post_format_video', true);

        if(!$url)
            return null;

        $output = '';
        $output .= benjamin_get_the_video_markup($url);

        return $output;
    }


    /**
     * If the post format is a gallery and there are set images display the gallery
     * @return [type] [description]
     */
    public function getGallery()
    {
        global $post;
        $output = '';

        $gallery = get_post_meta($post->ID, '_post_format_gallery', true);

        if(!$gallery)
            return null;

        $output .= benjamin_get_carousel_markup($gallery, 'large');

        return $output;
    }


    /**
     * If the post format is an Image type and a featured image has been uploaded
     * @return [type] [description]
     */
    public function getImage()
    {
        global $post;
        $output = '';

        $src = get_post_meta($post->ID, '_post_format_image', true) ;

        if(!$src)
            return null;

        // if( !has_post_thumbnail() )
        //     return null;


        // $src = get_the_post_thumbnail_url();
        // $image = get_post( get_post_thumbnail_id() );
        // $title = $image->post_title;
        // $caption = $image->post_excerpt;

        // $output .= '<div >';
            $output .= '<img class="hero-post-format-image" src="'.$src.'">';
            if( isset($caption))
                $output .= '<div class="hero-post-format-image__caption">'. $caption . '</div>';

        // $output .= '</div>';

        return $output;
    }


    /**
     * Gets the post format quote
     * @return [type] [description]
     */
    public function getQuote()
    {
        global $post;

        $output = '';

        $quote = get_post_meta($post->ID, '_post_format_quote', true);

        if(!$quote)
            return null;

        $output .= benjamin_get_quote_markup($quote);

        return $output;
    }


    /**
     * Gets the post format chat
     * @return [type] [description]
     */
    public function getChat()
    {
        global $post;

        $output = '';

        $chat = get_post_meta($post->ID, '_post_format_chat', true);

        if(!$chat || $chat['location'] !== 'header')
            return false;

        $output .= '<h1 class="hero__title">'.get_the_title().'</h1>';

        $output .= '<div class="well">';
            $output .= benjamin_get_chat_log($chat);
        $output .= '</div>';

        return $output;
    }


    /**
     * Gets the post format quote
     * @return [type] [description]
     */
    public function getLink()
    {
        global $post;

        $output = '';

        $link = get_post_meta($post->ID, '_post_format_link', true);

        if(!$link)
            return null;

        $output .= '<span class="hero__pre-title">';
            $output .= '<i class="fa fa-link" aria-hidden="true"></i>';
            $output .= 'Visit Link';
        $output .= '</span>';

        $output .= '<h1 class="hero__title">';
            $output .= '<a href="'.$link['url'].'" target="_blank" follow="no-follow">'.$link['text'].'</a>';
        $output .= '</h1>';

        return $output;
    }




    // author feed title
    public function authorContent()
    {
        $auth = get_user_by('slug', get_query_var('author_name'));

        $output = '';
        $output .= '<span class="hero__pre-title">';
            $output .= '<i class="fa fa-user" aria-hidden="true"></i>';
            $output .= 'Posted by';
        $output .= '</span>';

        $output .= '<h1 class="hero__title">' . $auth->display_name . '</h1>';

        return $output;
    }


    /**
     * The Date title
     *
     * Grabs the markup for either the month date, or the year depending on where we are
     * @return [type] [description]
     */
    public function dateContent()
    {

        $output = '';

        if( is_month()){

            $output .= '<span class="hero__pre-title">';
                $output .= '<i class="fa fa-calendar" aria-hidden="true"></i>';
                $output .= 'Posted in ';
            $output .= '</span>';

            $output .= '<h1 class="hero__title">' . get_the_date('F') .'</h1>';
            $output .= '<span class="hero__sub-title">' . get_the_date('Y') .'</span>';

        } else{

            $output .= '<span class="hero__pre-title">';
                $output .= '<i class="fa fa-calendar" aria-hidden="true"></i>';
                $output .= 'Posted in ';
            $output .= '</span>';

            $output .= '<h1 class="hero__title">' . get_the_date('Y') .'</h1>';

        }

        return $output;
    }


    // Tags
    public function tagContent()
    {
        ob_start();
            single_tag_title();
            $buffered_cat = ob_get_contents();
        ob_end_clean();

        $output = '';
        $output .= '<span class="hero__pre-title">';
            $output .= '<i class="fa fa-tags" aria-hidden="true"></i>';
            $output .= 'Tagged as';
        $output .= '</span>';

        $output .= '<h1 class="hero__title">' . $buffered_cat . '</h1>';

        return $output;

    }

    // category feed title
    public function categoryContent()
    {
        ob_start();
            single_cat_title();
            $buffered_cat = ob_get_contents();
        ob_end_clean();

        $output = '';
        $output .= '<span class="hero__pre-title">';
            $output .= '<i class="fa fa-folder-o" aria-hidden="true"></i>';
            $output .= 'Posted in';
        $output .= '</span>';

        $output .= '<h1 class="hero__title">' . $buffered_cat . '</h1>';

        return $output;

    }


    // search title
    public function searchContent()
    {
        global $wp_query;
        $total_results = $wp_query->found_posts;
        // $title = $total_results ? 'Search Results for: '.get_search_query() : 'No results found' ;

        $output = '';
        $output .= '<span class="hero__pre-title">';
            $output .= '<i class="fa fa-search" aria-hidden="true"></i>';
            $output .= 'Search results for';
        $output .= '</span>';

        $output .= '<h1 class="hero__title">' . get_search_query() . '</h1>';

        return $output;

    }


    // The feed either shows the featured post content, the feed type, or the name of the page
    public function homeContent()
    {
        $output = '';

        $post = get_queried_object();
        $post_type = is_a($post, 'WP_Post_Type') ? $post->name : 'post';

        $hasFeaturedPost = get_option('featured-post--'.$post_type, false);

        if($hasFeaturedPost) {
            $FeaturedPost = new FeaturedPost($hasFeaturedPost, $post_type);
            $output = $this->featuredContent($FeaturedPost);
        } elseif( $post->post_title )  {
            $output = '<h1 class="hero__title">' . $post->post_title . '</h1>';
        } elseif($post->name) {
            $output = '<h1 class="hero__title">' . $post->name . '</h1>';
        } else {
            $output = '<h1 class="hero__title"> Home </h1>';
        }

        return $output;
    }


    public function featuredContent($post)
    {
        $label = get_post_type_object($post->post_type)->labels->singular_name;
        $output = '';
        $output .= '<span class="hero__pre-title">';
            $output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
            $output .= 'Featured '.ucfirst($label);
        $output .= '</span>';

        $output .= '<h1 class="hero__title">';
            $output .= '<a href="'.$post->url.'">' . $post->title . '</a>';
        $output .= '</h1>';

        $output .= '<div class="post-meta">';
            $output .= benjamin_get_hero_meta($post->id);
        $output .= '</div>';

        return $output;
    }


    // The feed either shows the featured post content, the feed type, or the name of the page
    public function archiveContent()
    {
        $output = '';

        $post = get_queried_object();
        $post_type = is_a($post, 'WP_Post_Type') ? $post->name : 'post';

        if( is_home() )
            $this->isFeaturedPost = get_option('featured-post--'.$post_type, false);

        if( $post->post_title )  {
            $output = '<h1 class="hero__title">' . $post->post_title . '</h1>';
        } elseif($post->name) {
            $output = '<h1 class="hero__title">' . $post->name . '</h1>';
        } else {
            $output = '<h1 class="hero__title"> Home </h1>';
        }

        return $output;
    }


    public function frontpageContent()
    {

        $output = '';
        $content = get_theme_mod('frontpage_hero_content_setting', 'callout');

        if($content == 'page') {
            $page = get_theme_mod('frontpage_hero_page_setting', 0);
            if( !is_null($page) && $page != 0 ) {
                $page = get_page($page);
                $output .= apply_filters('the_content', $page->post_content);
            }else {
                $output = '<h1 class="hero__title">' . get_bloginfo( 'name' ) . '</h1>';
            }
        } elseif($content == 'callout') {
            $output .= $this->heroCallout();
        } else {
            $output = '<h1 class="hero__title">' . get_bloginfo( 'name' ) . '</h1>';
        }

        return $output;
    }


    /**
     * The front page displays a "callout", here is the markup
     * @return [type] [description]
     */
    public function heroCallout(){
        $id = get_theme_mod('frontpage_hero_callout_setting', 0);

        $description = get_bloginfo( 'description', 'display' );
        $title = get_bloginfo( 'name', 'display' );

        if(!$title || !$description)
            return '<h1 class="hero__title">' . $title .'</h1>';


        $output = '';

        $output .= '<div class="usa-hero-callout usa-section-dark">';
            $output .= '<h1 class="hero__title">'.$title.'</h1>';

                if ( $description || is_customize_preview() )
                    $output .= '<p class="hero__sub-title">'.$description.'</p>';

                if( !is_null($id) && $id != 0 )
                    $output .= '<a class="usa-button usa-button-big usa-button-secondary"
                        href="'.get_the_permalink($id).'">'. __('Learn More', 'benjamin') .'</a>';

        $output .= '</div>';

        return $output;
    }
}