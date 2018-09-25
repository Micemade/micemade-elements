<?php
/**
 *  MICEMADE GITHUB PLUGIN UPDATER
 *  - class for automatic plugin updates via Github
 *  - used code from https://code.tutsplus.com/tutorials/distributing-your-plugins-in-github-with-automatic-updates--wp-34817
 */
if ( ! class_exists( 'Micemade_GitHub_Plugin_Updater' ) ) {

	class Micemade_GitHub_Plugin_Updater {

		private $slug;

		private $plugin_data;

		private $username;

		private $repo;

		private $plugin_file;

		private $github_api_result;

		private $access_token;

		private $plugin_activated;

		/**
		 * Class constructor.
		 *
		 * @param  string $plugin_file
		 * @param  string $github_username
		 * @param  string $github_project_name
		 * @param  string $access_token
		 * @return null
		 */
		function __construct( $plugin_file, $github_username, $github_project_name, $access_token = '' ) {
			add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'set_transient' ) );
			add_filter( 'plugins_api', array( $this, 'set_plugin_info' ), 10, 3 );
			add_filter( 'upgrader_pre_install', array( $this, 'pre_install' ), 10, 3 );
			add_filter( 'upgrader_post_install', array( $this, 'post_install' ), 10, 3 );

			$this->plugin_file  = $plugin_file;
			$this->username     = $github_username;
			$this->repo         = $github_project_name;
			$this->access_token = $access_token;
		}

		/**
		 * Get information regarding our plugin from WordPress
		 *
		 * @return void
		 */
		private function init_plugin_data() {
			$this->slug = plugin_basename( $this->plugin_file );

			$this->plugin_data = get_plugin_data( $this->plugin_file );
		}

		/**
		 * Get information regarding our plugin from GitHub
		 *
		 * @return null
		 */
		private function get_repo_release_info() {
			if ( ! empty( $this->github_api_result ) ) {
				return;
			}

			// Query the GitHub API.
			$url = "https://api.github.com/repos/{$this->username}/{$this->repo}/releases";

			if ( ! empty( $this->access_token ) ) {
				$url = add_query_arg( array( 'access_token' => $this->access_token ), $url );
			}

			// Get the results.
			$this->github_api_result = wp_remote_retrieve_body( wp_remote_get( $url ) );

			if ( ! empty( $this->github_api_result ) ) {
				$this->github_api_result = @json_decode( $this->github_api_result );
			}

			// Use only the latest release.
			if ( is_array( $this->github_api_result ) ) {
				$this->github_api_result = $this->github_api_result[0];
			}
		}

		/**
		 * Push in plugin version information to get the update notification
		 *
		 * @param  object $transient
		 * @return object
		 */
		public function set_transient( $transient ) {
			if ( empty( $transient->checked ) ) {
				return $transient;
			}

			// Get plugin & GitHub release information.
			$this->init_plugin_data();
			$this->get_repo_release_info();

			$doUpdate = version_compare( $this->github_api_result->tag_name, $transient->checked[ $this->slug ], '>' );

			if ( $doUpdate ) {
				$package = $this->github_api_result->zipball_url;

				if ( ! empty( $this->access_token ) ) {
					$package = add_query_arg( array( 'access_token' => $this->access_token ), $package );
				}

				// Plugin object.
				$obj              = new stdClass();
				$obj->slug        = $this->slug;
				$obj->new_version = $this->github_api_result->tag_name;
				$obj->url         = $this->plugin_data['PluginURI'];
				$obj->package     = $package;

				$transient->response[ $this->slug ] = $obj;
			}

			return $transient;
		}

		/**
		 * Push in plugin version information to display in the details lightbox
		 *
		 * @param  boolean $false
		 * @param  string $action
		 * @param  object $response
		 * @return object
		 */
		public function set_plugin_info( $false, $action, $response ) {
			$this->init_plugin_data();
			$this->get_repo_release_info();

			if ( ! isset( $response->slug ) || ( $response->slug != $this->slug ) ) {
				return $false;
			}

			// Add our plugin information.
			$response->last_updated = $this->github_api_result->published_at;
			$response->slug         = $this->slug;
			$response->name         = $this->plugin_data['Name'];
			$response->version      = $this->github_api_result->tag_name;
			$response->author       = $this->plugin_data['AuthorName'];
			$response->homepage     = $this->plugin_data['PluginURI'];

			// This is our release download zip file.
			$download_link = $this->github_api_result->zipball_url;

			if ( ! empty( $this->access_token ) ) {
				$download_link = add_query_arg(
					array( 'access_token' => $this->access_token ),
					$download_link
				);
			}

			$response->download_link = $download_link;

			// Load Parsedown.
			require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes/Parsedown.php';

			// Create tabs in the lightbox.
			$response->sections = array(
				'Description' => $this->plugin_data['Description'],
				'changelog'   => class_exists( 'Parsedown' )
					? Parsedown::instance()->parse( $this->github_api_result->body )
					: $this->github_api_result->body,
			);

			// Gets the required version of WP if available.
			$matches = null;
			preg_match( '/requires:\s([\d\.]+)/i', $this->github_api_result->body, $matches );
			if ( ! empty( $matches ) ) {
				if ( is_array( $matches ) ) {
					if ( count( $matches ) > 1 ) {
						$response->requires = $matches[1];
					}
				}
			}

			// Gets the tested version of WP if available.
			$matches = null;
			preg_match( '/tested:\s([\d\.]+)/i', $this->github_api_result->body, $matches );
			if ( ! empty( $matches ) ) {
				if ( is_array( $matches ) ) {
					if ( count( $matches ) > 1 ) {
						$response->tested = $matches[1];
					}
				}
			}

			return $response;
		}

		/**
		 * Perform check before installation starts.
		 *
		 * @param  boolean $true
		 * @param  array   $args
		 * @return null
		 */
		public function pre_install( $true, $args ) {
			// Get plugin information
			$this->init_plugin_data();

			// Check if the plugin was installed before.
			$this->plugin_activated = is_plugin_active( $this->slug );
		}

		/**
		 * Perform additional actions to successfully install our plugin
		 *
		 * @param  boolean $true
		 * @param  string $hook_extra
		 * @param  object $result
		 * @return object
		 */
		public function post_install( $true, $hook_extra, $result ) {
			global $wp_filesystem;

			// Since we are hosted in GitHub, our plugin folder would have a dirname of
			// reponame-tagname change it to our original one.
			$plugin_folder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
			$wp_filesystem->move( $result['destination'], $plugin_folder );
			$result['destination'] = $plugin_folder;

			// Re-activate plugin if needed.
			if ( $this->plugin_activated ) {
				$activate = activate_plugin( $this->slug );
			}

			return $result;
		}
	}
}
