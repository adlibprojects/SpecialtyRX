<?php
/*
Plugin Name: Many Sidebars
Plugin URI: http://wordpress.org/extend/plugins/many-sidebars/
Description: An interface addition for Widgets to let you increase the number of your Sidebars without lose your mind.
Version: 1.0.1
Author: Edir Pedro
Author URI: http://edirpedro.com.br
License: GPL2
*/

/*  Copyright YEAR  Edir Pedro  (email : edirps@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


add_action('admin_init', 'many_sidebars_script');

function many_sidebars_script() {
	wp_enqueue_script('many_sidebars', plugins_url('many-sidebars.js', __FILE__), array('jquery'));
}
