<?php

namespace Eugene\ApiPlugin;

/**
 * Handles the admin page for the plugin.
 *
 * @since 1.0.0
 */
class AdminPageHandler {
	/**
	 * Register the admin page and the necessary hooks.
	 *
	 * @since 1.0.0
	 */
	public static function register() {
		add_action( 'admin_menu', [ self::class, 'add_admin_page' ] );
		add_action( 'admin_enqueue_scripts', [ self::class, 'enqueue_scripts' ] );
		add_action( 'admin_post_eugene_refresh_data', [ self::class, 'handle_data_refresh' ] );
	}

	/**
	 * Adds an admin page under the Settings menu.
	 *
	 * @since 1.0.0
	 */
	public static function add_admin_page() {
		add_menu_page(
			'Eugene API Data',
			'Eugene API',
			'manage_options',
			'eugene-api-data',
			[ self::class, 'render_admin_page' ],
			'dashicons-database',
			6
		);
	}

	/**
	 * Renders the admin page.
	 *
	 * @since 1.0.0
	 */
	public static function render_admin_page() {
		$api_data = get_transient( 'eugene_api_data' );
		if ( ! $api_data ) {
			$data = 'No data available. Please refresh.';
		} else {
			$data = json_decode( $api_data, true );
		}

		?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
                <input type="hidden" name="action" value="eugene_refresh_data">
				<?php wp_nonce_field( 'eugene_refresh_action' ); ?>
				<?php submit_button( 'Refresh Data' ); ?>
            </form>
			<?php if ( is_array( $data ) && isset( $data['data'] ) ) : ?>
                <h2><?php echo esc_html( $data['title'] ); ?></h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                    <tr>
						<?php foreach ( $data['data']['headers'] as $header ) : ?>
                            <th><?php echo esc_html( $header ); ?></th>
						<?php endforeach; ?>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ( $data['data']['rows'] as $row ) : ?>
                        <tr>
                            <td><?php echo esc_html( $row['id'] ); ?></td>
                            <td><?php echo esc_html( $row['fname'] ); ?></td>
                            <td><?php echo esc_html( $row['lname'] ); ?></td>
                            <td><?php echo esc_html( $row['email'] ); ?></td>
                            <td><?php echo date( 'Y-m-d H:i:s', $row['date'] ); ?></td>
                        </tr>
					<?php endforeach; ?>
                    </tbody>
                </table>
			<?php else : ?>
                <p><?php echo esc_html( $data ); ?></p>
			<?php endif; ?>
        </div>
		<?php
	}

	/**
	 * Handles the data refresh when the refresh button is clicked.
	 *
	 * @since 1.0.0
	 */
	public static function handle_data_refresh() {
		check_admin_referer( 'eugene_refresh_action' );

		// Fetch and cache new data immediately.
		AjaxHandler::fetch_and_cache_data();

		// Redirect back to the admin page.
		wp_safe_redirect( admin_url( 'admin.php?page=eugene-api-data' ) );
		exit;
	}


	/**
	 * Enqueues JavaScript and CSS files for the admin page.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook The current admin page.
	 */
	public static function enqueue_scripts( $hook ) {
		wp_enqueue_script(
			'eugene-api-js',
			plugins_url( 'resources/js/data.js', __FILE__ ),
			[ 'jquery' ],
			'1.0.0',
			true
		);

		wp_localize_script(
			'eugene-api-js',
			'eugeneApi',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'eugene_api_nonce' )
			]
		);
	}
}
