<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
Plugin Name: Ninja Forms - Dropbox Upload URL
Plugin URI: http://kylebjohnson.me
Description: A temporary fix for the Uploaded Dropbox URL
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
 * Class NF_DropboxUploadURL
 */
class NF_DropboxUploadURL
{
    const VERSION = '0.0.1';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_action( 'ninja_forms_post_process', array( $this, 'ninja_forms_post_process' ), 1 );
    }

    /*
    * Public Methods
    */

    public function ninja_forms_post_process()
    {
        global $ninja_forms_processing;

        foreach( $ninja_forms_processing->data['field_data'] as $field ){

            if( '_upload' == $field['type'] AND 'dropbox' == $field['data']['upload_location'] ){

                foreach( $ninja_forms_processing->data['fields'][ $field['id'] ] as $key => $file ){

                    $new_file_url = admin_url( '?nf-upload=' . $file['upload_id'] );

                    $ninja_forms_processing->data['fields'][ $field['id'] ][ $key ]['file_url'] = $new_file_url;

                }

            }

        }

    }

}

new NF_DropboxUploadURL();
