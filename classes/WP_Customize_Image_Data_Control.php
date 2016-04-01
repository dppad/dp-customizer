<?php

/**
 * Code originating from http://justintadlock.com/archives/2015/05/06/customizer-how-to-save-image-media-data
 */
class JT_Customize_Setting_Image_Data extends WP_Customize_Setting {
	private $image_size='large';

	/**
	 * Overwrites the `update()` method so we can save some extra data.
	 */
	protected function update( $value ) {

		if ( $value ) {

			$post_id = attachment_url_to_postid( $value );

			if ( $post_id ) {

				$image = wp_get_attachment_image_src( $post_id, $this->image_size);

				if ( $image ) {

					var_dump_log($image, 'image:');

					/* Set up a custom array of data to save. */
					$data = array(
						'url'    => esc_url_raw( $image[0] ),
						'width'  => absint( $image[1] ),
						'height' => absint( $image[2] ),
						'id'     => absint( $post_id )
					);

					set_theme_mod( "{$this->id_data[ 'base' ]}_data", $data );
				}
			}
		}

		/* No media? Remove the data mod. */
		if ( empty( $value ) || empty( $post_id ) || empty( $image ) ) {
			remove_theme_mod( "{$this->id_data[ 'base' ]}_data" );
		}

		/* Let's send this back up and let the parent class do its thing. */
		return parent::update( $value );
	}

	public function setSize($new_size='large'){
		$this->image_size = $new_size;
	}
}