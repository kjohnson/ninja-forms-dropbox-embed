<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
Plugin Name: Ninja Forms - Dropbox Embed
Plugin URI: http://kylebjohnson.me
Description: Embeds a File uploaded by Ninja Forms to Dropbox
Version: 0.0.1

Author: Kyle B. Johnson
Author URI: http://kylebjohnson.me

Copyright 2015 The WP Ninjas.

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/


/**
 * Class NF_DropboxEmbed
 */
class NF_DropboxEmbed
{
    const VERSION = '0.0.1';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action( 'ninja_forms_post_process', array( $this, 'ninja_forms_post_process' ),  9001);
        add_action( 'ninja_forms_create_post', array( $this, 'ninja_forms_create_post' ), 10 );
        add_shortcode( 'nf_dropbox_embed', array( $this, 'dropbox_embed' ) );
    }

    /*
    * Public Methods
    */

    //Add public methods here

    public function ninja_forms_post_process()
    {
        global $ninja_forms_processing;

        $sub_id = $ninja_forms_processing->data['form']['sub_id'];

        $post_id = $ninja_forms_processing->post_id;

        $post = get_post( $post_id, ARRAY_A );

        // [nf_dropbox_embed sub={sub_id} field=1]
        $post['post_content'] = str_replace( '{sub_id}', $sub_id, $post['post_content'] );

        wp_update_post( $post );
    }

    public function ninja_forms_create_post( $post_id )
    {
        global $ninja_forms_processing;

        $ninja_forms_processing->post_id = $post_id;
    }

    public function dropbox_embed( $atts )
    {
        $atts['method'] = ( isset( $atts['method'] ) ) ? $atts['method'] : 'embed';

        $files = Ninja_Forms()->sub( $atts['sub'] )->get_field( $atts['field'] );

        if( ! is_array( $files ) ) return;

        $return = '';

        foreach( $files as $file ){
            if( ! isset( $file['upload_id'] ) ) continue;

            switch( $atts['method'] ) {
                case 'url':
                    $return .= 'http://php52.dev/wp/wp-admin/?nf-upload=' . $file['upload_id'];
                    break;
                case 'link':
                    $return .= '<a href="http://php52.dev/wp/wp-admin/?nf-upload=' . $file['upload_id'] . '">' . $file['user_file_name'] . '</a>';
                    break;
                default:
                    $return .= '<img src="http://php52.dev/wp/wp-admin/?nf-upload=' . $file['upload_id'] . '" alt="' . $file['user_file_name'] . '" />';
            }

        }

        return $return;
    }


    /*
     * Private Methods
     */

    //Add private methods here
}

new NF_DropboxEmbed();
