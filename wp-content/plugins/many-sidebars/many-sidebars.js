/*
Plugin Name: Many Sidebars
Plugin URI: http://wordpress.org/extend/plugins/many-sidebars/
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

jQuery(document).ready(function($) {

	// Writing the Panel
	
	var html  = '<div class="widgets-holder-wrap">'
		html += '<div class="sidebar-name"><div class="sidebar-name-arrow"><br></div><h3>Available Sidebars</h3></div>';
		html += '<div id="sidebar-many-sidebars" class="widgets-sortables ui-sortable" style="min-height: 50px; ">';
		html += '<div class="sidebar-description">';
		html += '<p class="description">Select one to edit:</p>';
		html += '<p><select id="many-sidebars"><option></option></select></p>';
		html += '<p><label><input id="many-sidebars-show-all" type="checkbox" value="1" /> Show All</label></p>';
		html += '</div></div></div>';

	$('#widgets-right').prepend(html);
	
	// Getting the Sidebars
	
	$('#widgets-right .widgets-holder-wrap').each(function(index) {
		var id = 'many-sidebars-panel-' + index;
		var name = $('.sidebar-name h3', this).text();
		
		if(index > 0) {
			$('select#many-sidebars').append(
				$('<option></option>').
				attr('value', id).
				text(name)
			);
			
			$(this).attr('id', id).hide();
		}
	});
	
	// Action
	
	$('select#many-sidebars').change(function() {
		$('input#many-sidebars-show-all').attr('checked', false);
		many_sidebars_show($('select#many-sidebars option:selected').attr('value'));
	});
	
	$('input#many-sidebars-show-all').click(function() {
		if($(this).attr('checked'))
			many_sidebars_show('all');
		else
			many_sidebars_show($('select#many-sidebars option:selected').attr('value'));
	});
	
	// Functions
	
	many_sidebars_show = function(id) {
		many_sidebars_hide();
		if(id == 'all') {
			$('#widgets-right .widgets-holder-wrap').each(function(index) {
				if(index > 0)
					$(this).show();
			});
		} else {
			$('#'+id).show().children('.sidebar-name').click();
		}
	}
	
	many_sidebars_hide = function() {
		$('#widgets-right .widgets-holder-wrap').each(function(index) {
			if(index > 0)
				$(this).addClass('closed').hide();
		});
	}
	
});
