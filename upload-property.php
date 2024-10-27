<?php
/**
* ----------------------------------------------------------------------------------------------------------------------
* Include WP files
* ----------------------------------------------------------------------------------------------------------------------
*/

	require_once('../../../wp-load.php');
	require_once(ABSPATH . 'wp-admin/includes/admin.php');
	
	
/**
* ----------------------------------------------------------------------------------------------------------------------
* Process Image File Upload
* ----------------------------------------------------------------------------------------------------------------------
*/

	$id = ae_media_handle_upload('async-upload', 0); //post id of Client Files page
	
	unset($_FILES);
		if ( is_wp_error($id) ) {
		$errors['upload_error'] = $id;
		$id = false;
	}
	
	if ($errors) {
		echo "<p>There was an error uploading your file.</p>".print_r($errors);
	} else {
		echo "$id";
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* This handles the file upload POST itself, creating the attachment post.
* ----------------------------------------------------------------------------------------------------------------------
* @since 2.5.0
* @param string $file_id Index into the {@link $_FILES} array of the upload
* @param int $post_id The post ID the media is associated with
* @param array $post_data allows you to overwrite some of the attachment
* @param array $overrides allows you to override the {@link wp_handle_upload()} behavior
* @return int the ID of the attachment
* ----------------------------------------------------------------------------------------------------------------------
*/

	function ae_media_handle_upload($file_id, $post_id, $post_data = array(), $overrides = array( 'test_form' => false )) {
	
		$time = current_time('mysql');
		if ( $post = get_post($post_id) ) {
			if ( substr( $post->post_date, 0, 4 ) > 0 )
				$time = $post->post_date;
		}
	
		$name = $_FILES['file']['name'];
	
		$file = wp_handle_upload($_FILES['file'], $overrides, $time);
	
		if ( isset($file['error']) )
			return new WP_Error( 'upload_error', $file['error'] );
	
		$name_parts = pathinfo($name);
		$name = trim( substr( $name, 0, -(1 + strlen($name_parts['extension'])) ) );
	
		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$title = $name;
		$content = '';
	
		// use image exif/iptc data for title and caption defaults if possible
		if ( $image_meta = @wp_read_image_metadata($file) ) {
			if ( trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) )
				$title = $image_meta['title'];
			if ( trim( $image_meta['caption'] ) )
				$content = $image_meta['caption'];
		}
	
		// Construct the attachment array
		$attachment = array_merge( array(
			'post_mime_type' => $type,
			'guid' => $url,
			'post_parent' => $post_id,
			'post_title' => $title,
			'post_content' => $content,
		), $post_data );
	
		// Save the data
		$id = wp_insert_attachment($attachment, $file, $post_id);
		if ( !is_wp_error($id) ) {
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
		}
	
		$data = array('id'=>$id,'post_id'=>$post_id);
		
		return json_encode($data);
		
	}


/**
* ----------------------------------------------------------------------------------------------------------------------
* Process Image File Upload
* ----------------------------------------------------------------------------------------------------------------------
*/

	function ae_getProperty_details($propertyID) {
	
		$details = array();

		return $details;

	}

	
?>