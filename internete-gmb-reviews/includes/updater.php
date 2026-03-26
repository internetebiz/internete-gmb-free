<?php
/**
 * GitHub Auto-Updater for Internete GMB Reviews (Free)
 *
 * Checks the GitHub Releases API for new versions and surfaces them
 * in the standard WordPress Plugins > Updates screen.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Internete_GMB_Updater {

	private $plugin_slug;
	private $plugin_file;
	private $github_user  = 'internetebiz';
	private $github_repo  = 'internete-gmb-free';
	private $plugin_data  = array();
	private $github_data  = null;

	public function __construct( $plugin_file ) {
		$this->plugin_file = $plugin_file;
		$this->plugin_slug = plugin_basename( $plugin_file );

		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
		add_filter( 'plugins_api', array( $this, 'plugin_info' ), 20, 3 );
		add_filter( 'upgrader_post_install', array( $this, 'after_install' ), 10, 3 );
	}

	/**
	 * Fetch release data from GitHub, cached for 12 hours.
	 */
	private function get_github_release() {
		if ( $this->github_data !== null ) {
			return $this->github_data;
		}

		$cache_key = 'internete_gmb_updater_release';
		$cached    = get_transient( $cache_key );

		if ( $cached !== false ) {
			$this->github_data = $cached;
			return $this->github_data;
		}

		$url      = "https://api.github.com/repos/{$this->github_user}/{$this->github_repo}/releases/latest";
		$response = wp_remote_get(
			$url,
			array(
				'headers' => array(
					'Accept'     => 'application/vnd.github+json',
					'User-Agent' => 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' ),
				),
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return null;
		}

		$data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( empty( $data->tag_name ) ) {
			return null;
		}

		set_transient( $cache_key, $data, 12 * HOUR_IN_SECONDS );
		$this->github_data = $data;

		return $this->github_data;
	}

	/**
	 * Get the zip download URL from the release assets.
	 */
	private function get_download_url() {
		$release = $this->get_github_release();

		if ( ! $release ) {
			return null;
		}

		// Prefer the built zip from GitHub Actions
		if ( ! empty( $release->assets ) ) {
			foreach ( $release->assets as $asset ) {
				if ( substr( $asset->name, -4 ) === '.zip' ) {
					return $asset->browser_download_url;
				}
			}
		}

		// Fall back to the auto-generated source zip
		return $release->zipball_url;
	}

	/**
	 * Load plugin header data.
	 */
	private function get_plugin_data() {
		if ( empty( $this->plugin_data ) ) {
			$this->plugin_data = get_plugin_data( $this->plugin_file );
		}
		return $this->plugin_data;
	}

	/**
	 * Inject update info into the WordPress transient.
	 */
	public function check_update( $transient ) {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$release      = $this->get_github_release();
		$plugin_data  = $this->get_plugin_data();

		if ( ! $release || empty( $release->tag_name ) ) {
			return $transient;
		}

		$latest_version  = ltrim( $release->tag_name, 'v' );
		$current_version = $plugin_data['Version'];

		if ( version_compare( $latest_version, $current_version, '>' ) ) {
			$download_url = $this->get_download_url();

			if ( $download_url ) {
				$update                  = new stdClass();
				$update->slug            = dirname( $this->plugin_slug );
				$update->plugin          = $this->plugin_slug;
				$update->new_version     = $latest_version;
				$update->url             = 'https://internete.net/gmb-reviews';
				$update->package         = $download_url;
				$update->tested          = '6.7';
				$update->requires_php    = '7.4';

				$transient->response[ $this->plugin_slug ] = $update;
			}
		}

		return $transient;
	}

	/**
	 * Populate the "View version details" popup in WP admin.
	 */
	public function plugin_info( $result, $action, $args ) {
		if ( $action !== 'plugin_information' ) {
			return $result;
		}

		if ( ! isset( $args->slug ) || $args->slug !== dirname( $this->plugin_slug ) ) {
			return $result;
		}

		$release     = $this->get_github_release();
		$plugin_data = $this->get_plugin_data();

		if ( ! $release ) {
			return $result;
		}

		$info                = new stdClass();
		$info->name          = $plugin_data['Name'];
		$info->slug          = dirname( $this->plugin_slug );
		$info->version       = ltrim( $release->tag_name, 'v' );
		$info->author        = '<a href="https://internete.net">Internete</a>';
		$info->homepage      = 'https://internete.net/gmb-reviews';
		$info->requires      = '5.8';
		$info->tested        = '6.7';
		$info->requires_php  = '7.4';
		$info->download_link = $this->get_download_url();
		$info->sections      = array(
			'description' => $plugin_data['Description'],
			'changelog'   => nl2br( esc_html( $release->body ) ),
		);

		return $info;
	}

	/**
	 * Rename the extracted folder to match the plugin slug after install.
	 * GitHub zips extract as "repo-name-hash/" which breaks activation.
	 */
	public function after_install( $response, $hook_extra, $result ) {
		global $wp_filesystem;

		if ( ! isset( $hook_extra['plugin'] ) || $hook_extra['plugin'] !== $this->plugin_slug ) {
			return $response;
		}

		$plugin_folder = WP_PLUGIN_DIR . '/' . dirname( $this->plugin_slug );
		$wp_filesystem->move( $result['destination'], $plugin_folder );
		$result['destination'] = $plugin_folder;

		activate_plugin( $this->plugin_slug );

		return $result;
	}
}
