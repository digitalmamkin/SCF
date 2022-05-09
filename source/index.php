<?php
/*
    Plugin Name: WP Simple contact form
    Description: Plugin for inserting simple contact form over using short code.
    Author: Digital Fox
    Version: 1.0
 */

// Init Install;
add_action('admin_menu', 'install');
// Init Uninstall;
register_uninstall_hook(__FILE__, 'uninstall');
// Init short code;
add_shortcode( 'scf_short' , 'renderForm');

function install(){
	global $wpdb;

    // Migrate table;
    $wpdb->query('CREATE TABLE IF NOT EXISTS `scf_contacts` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        `email` varchar(50) NOT NULL,	
        `options` varchar(255) NOT NULL,
        `date` date NOT NULL,		    	
        `file` varchar(50) NOT NULL, 
        `created_at` datetime NOT NULL,	    
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

    // Add admin page in menu;
    add_menu_page( 'SCF List', 'SCF', 'manage_options', 'scf', 'pluginAdminPage', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz4KPHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGlkPSJMYXllcl8xIiBkYXRhLW5hbWU9IkxheWVyIDEiIHZpZXdCb3g9IjAgMCAyNCAyNCIgd2lkdGg9IjUxMiIgaGVpZ2h0PSI1MTIiIGZpbGw9IndoaXRlIj48cGF0aCBkPSJNMTgsMEg4QTUsNSwwLDAsMCwzLjQyNCwzSDJBMSwxLDAsMCwwLDIsNUgzVjdIMkExLDEsMCwwLDAsMiw5SDN2MkgyYTEsMSwwLDAsMCwwLDJIM3YySDJhMSwxLDAsMCwwLDAsMkgzdjJIMmExLDEsMCwwLDAsMCwySDMuNDI0QTUsNSwwLDAsMCw4LDI0SDE4YTUuMDA2LDUuMDA2LDAsMCwwLDUtNVY1QTUuMDA2LDUuMDA2LDAsMCwwLDE4LDBabTMsMTlhMywzLDAsMCwxLTMsM0g4YTMsMywwLDAsMS0zLTNWNUEzLDMsMCwwLDEsOCwySDE4YTMsMywwLDAsMSwzLDNabS04LTdhMywzLDAsMCwwLDAtNkEzLDMsMCwwLDAsMTMsMTJabTUsNmExLDEsMCwwLDEtMiwwLDMsMywwLDAsMC02LDAsMSwxLDAsMCwxLTIsMEM4LjIxMSwxMS4zOTIsMTcuNzkxLDExLjM5NCwxOCwxOFoiLz48L3N2Zz4K');
}

function renderForm(){
	// Ajax URL for template;
	$ajax_url = plugins_url('/SCF/actions.php');

    // Options list for template;
	$options_array = [
		'Option 1',
		'Option 2',
		'Option 3',
        // Add more here;
	];
	$options = [];
	foreach($options_array as $option){
		$options[] = '<div class="form-check">
						  <label class="form-check-label">
						    <input type="checkbox" class="form-check-input" value="'.$option.'" name="options[]">'.$option.'
						  </label>
						</div>';
	}

    // Get template HTML;
    $template = file_get_contents(__DIR__.'/templates/form.html');
    // Replace "vars";
	$template = str_replace(['$save_url', '$options'], [$ajax_url, implode('', $options)], $template);
	
	return $template;
}

function pluginAdminPage(){
	global $wpdb;

	// Preparing contacts table;
    $contact_list = $wpdb->get_results('SELECT * FROM `scf_contacts`');
    $upload_dir = wp_upload_dir();
	$lines = [];
    foreach($contact_list as $contact){
	    $lines[] = '<tr>
	    				<td>'.$contact->id.'</td>
	    				<td>'.$contact->name.'</td>
	    				<td>'.$contact->email.'</td>
	    				<td>'.$contact->date.'</td>
	    				<td>'.$contact->options.'</td>
	    				<td>
	    					<a href="'.$upload_dir['baseurl'].$contact->file.'" target="_blank">'.$contact->file.'</a>
	    				</td>
	    			</tr>';
    }

    // Get template HTML;
    $template = file_get_contents(__DIR__.'/templates/list.html');
    // Replace "vars";
    $template = str_replace(['$title', '$lines'], ['Contacts list', implode('', $lines)], $template);
    
    echo $template;
}

function uninstall(){
	global $wpdb;

	$upload_dir = wp_get_upload_dir();
    $contact_list = $wpdb->get_results('SELECT * FROM `scf_contacts`');
    foreach($contact_list as $contact){
	    if($contact->file != ''){
		    // Remove uploaded files;
		    wp_delete_file($upload_dir['basedir'].$contact->file);
	    }	   
    }

    // Drop storage table;
    $wpdb->query('DROP TABLE IF EXISTS scf_contacts;');
}