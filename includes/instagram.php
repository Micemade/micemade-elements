<?php
/**
 * Instagram function
 *
 * @since 0.6.4
 * @package WordPress
 * @subpackage Micemade Elements
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Micemade Elements Instagram
 *
 * @param [string] $username
 * @return void
 *
 * based on https://gist.github.com/cosmocatalano/4544576.
 */
function micemade_elements_instagram( $username ) {

	$username = trim( strtolower( $username ) );

	$instagram = get_transient( 'instagram-a9-' . sanitize_title_with_dashes( $username ) );

	if ( false === $instagram ) {

		switch ( substr( $username, 0, 1 ) ) {
			case '#':
				$url = 'https://instagram.com/explore/tags/' . str_replace( '#', '', $username );
				break;

			default:
				$url = 'https://instagram.com/' . str_replace( '@', '', $username );
				break;
		}

		$remote = wp_remote_get( $url );

		if ( is_wp_error( $remote ) ) {
			return new WP_Error( 'site_down', esc_html__( 'Unable to communicate with Instagram.', 'micemade-elements' ) );
		}

		if ( 200 !== wp_remote_retrieve_response_code( $remote ) ) {
			return new WP_Error( 'invalid_response', esc_html__( 'Instagram did not return a 200.', 'micemade-elements' ) );
		}

		$shards      = explode( 'window._sharedData = ', $remote['body'] );
		$insta_json  = explode( ';</script>', $shards[1] );
		$insta_array = json_decode( $insta_json[0], true );

		if ( ! $insta_array ) {
			return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'micemade-elements' ) );
		}

		if ( isset( $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'] ) ) {
			$images = $insta_array['entry_data']['ProfilePage'][0]['user']['media']['nodes'];
		} elseif ( isset( $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'] ) ) {
			$images = $insta_array['entry_data']['TagPage'][0]['graphql']['hashtag']['edge_hashtag_to_media']['edges'];
		} else {
			return new WP_Error( 'bad_json_2', esc_html__( 'Instagram has returned invalid data.', 'micemade-elements' ) );
		}

		if ( ! is_array( $images ) ) {
			return new WP_Error( 'bad_array', esc_html__( 'Instagram has returned invalid data.', 'micemade-elements' ) );
		}

		$instagram = array();

		foreach ( $images as $image ) {
			// Note: keep hashtag support different until these JSON changes stabalise.
			// these are mostly the same again now.
			switch ( substr( $username, 0, 1 ) ) {
				case '#':
					if ( true === $image['node']['is_video'] ) {
						$type = 'video';
					} else {
						$type = 'image';
					}

					$caption = __( 'Instagram Image', 'micemade-elements' );
					if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
						$caption = $image['node']['edge_media_to_caption']['edges'][0]['node']['text'];
					}

					$instagram[] = array(
						'description' => $caption,
						'link'        => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
						'time'        => $image['node']['taken_at_timestamp'],
						'comments'    => $image['node']['edge_media_to_comment']['count'],
						'likes'       => $image['node']['edge_liked_by']['count'],
						'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
						'small'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
						'large'       => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
						'original'    => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
						'type'        => $type,
					);
					break;
				default:
					if ( true === $image['is_video'] ) {
						$type = 'video';
					} else {
						$type = 'image';
					}

					$caption = __( 'Instagram Image', 'micemade-elements' );
					if ( ! empty( $image['caption'] ) ) {
						$caption = $image['caption'];
					}

					$instagram[] = array(
						'description' => $caption,
						'link'        => trailingslashit( '//instagram.com/p/' . $image['code'] ),
						'time'        => $image['date'],
						'comments'    => $image['comments']['count'],
						'likes'       => $image['likes']['count'],
						'thumbnail'   => preg_replace( '/^https?\:/i', '', $image['thumbnail_resources'][0]['src'] ),
						'small'       => preg_replace( '/^https?\:/i', '', $image['thumbnail_resources'][2]['src'] ),
						'large'       => preg_replace( '/^https?\:/i', '', $image['thumbnail_resources'][4]['src'] ),
						'original'    => preg_replace( '/^https?\:/i', '', $image['display_src'] ),
						'type'        => $type,
					);

					break;
			}
		} // End foreach().

		// do not set an empty transient - should help catch private or empty accounts.
		if ( ! empty( $instagram ) ) {
			$instagram = serialize( $instagram );
			set_transient( 'instagram-a9-' . sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'null_instagram_cache_time', HOUR_IN_SECONDS * 2 ) );
		}
	}

	if ( ! empty( $instagram ) ) {

		return unserialize( $instagram );

	} else {

		return new WP_Error( 'no_images', esc_html__( 'Instagram did not return any images.', 'micemade-elements' ) );

	}
}
