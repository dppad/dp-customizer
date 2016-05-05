<?php

/**
 * Code originating from http://justintadlock.com/archives/2015/05/06/customizer-how-to-save-image-media-data
 */
class JT_Customize_Setting_Image_Data extends WP_Customize_Setting {
	private $image_size = false;

	/**
	 * Overwrites the `update()` method so we can save some extra data.
	 */
	protected function update( $value ) {

		if ( $value ) {

			$post_id = attachment_url_to_postid( $value );

			if ( $post_id ) {


				$image = wp_get_attachment_image_src( $post_id );

				if ( $image ) {
					$data = array(
						'id' => $post_id
					);
					$images_sizes = get_intermediate_image_sizes();
					foreach ( $images_sizes as $image_size ) {
						$image_variation = wp_get_attachment_image_src( $post_id, $image_size );
						if ( $image_variation ) {
							$data[ $image_size ] = array(
								'url'             => esc_url_raw( $image_variation[0] ),
								'width'           => absint( $image_variation[1] ),
								'height'          => absint( $image_variation[2] ),
								'is_intermediate' => $image_variation[3]
							);
						} else {
							$data[ $image_size ] = $image_variation;
						}
					}
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

	public function setSize( $new_size = 'large' ) {
		$this->image_size = $new_size;
	}
}