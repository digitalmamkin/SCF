<?php
require __DIR__.'/../../../wp-config.php';
require_once(ABSPATH.'wp-admin/includes/file.php');
global $wpdb;

if(isset($_POST['username'])){
    // Make unique file name;
    $path = pathinfo($_FILES['file']['name']);
    $new_filename = $path['filename'].'_'.rand(111111, 999999).'.'.$path['extension'];

    // Move file to WP storage;
    $upload_dir = wp_upload_dir();
    move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir['path'].'/'.$new_filename);

    // Preparing data array from inserting in DB storage;
    $data = [
        'name' => $_POST['username'],
        'email' => $_POST['email'],
        'file' => $upload_dir['subdir'].'/'.$new_filename,
        'options' => isset($_POST['options']) && count($_POST['options']) > 0 ? implode(', ', $_POST['options']) : '-',
        'date' => $_POST['date'],
        'created_at' => date('Y-m-d H:i:s')
    ];

    // Save data;
	$wpdb->insert( 'scf_contacts', $data );

    // Return with success marker;
	wp_safe_redirect(add_query_arg( 'scf_success', 'true', wp_get_referer()));
}