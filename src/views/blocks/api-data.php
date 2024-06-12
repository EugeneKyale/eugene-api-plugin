<?php
/**
 * Block template for API Data Block.
 *
 * @since 1.0.0
 *
 * @param array $attributes Block attributes.
 * @param array $data       Data fetched from the API.
 *
 * @return string HTML content for the block.
 */
function render_api_data_block_template( $attributes, $data ) {
	if ( ! isset( $data['data'] ) ) {
		return '<p>' . __( 'No data available.', 'eugene-api-plugin' ) . '</p>';
	}

	// Mapping headers to row keys
	$header_to_key_map = [
		'ID'         => 'id',
		'First Name' => 'fname',
		'Last Name'  => 'lname',
		'Email'      => 'email',
		'Date'       => 'date',
	];

	ob_start();
	?>
    <div class="wp-block-eugene-api-data-block">
        <h4><?php echo esc_html( $data['title'] ); ?></h4>
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
				<?php foreach ( $data['data']['headers'] as $header ) :
					$key = $header_to_key_map[ $header ] ?? null;
					$keyFormatted = ucfirst( $key );
					if ( $key && isset( $attributes["show$keyFormatted"] ) && $attributes["show$keyFormatted"] ) : ?>
                        <th><?php echo esc_html( $header ); ?></th>
					<?php endif;
				endforeach; ?>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $data['data']['rows'] as $row ) : ?>
                <tr>
					<?php foreach ( $data['data']['headers'] as $header ) :
						$key = $header_to_key_map[ $header ] ?? null;
						$keyFormatted = ucfirst( $key );
						if ( $key && isset( $attributes["show$keyFormatted"] ) && $attributes["show$keyFormatted"] ) : ?>
                            <td><?php echo esc_html( $key === 'date' ? date( 'Y-m-d H:i:s', $row[ $key ] ) : $row[ $key ] ); ?></td>
						<?php endif;
					endforeach; ?>
                </tr>
			<?php endforeach; ?>
            </tbody>
        </table>
    </div>
	<?php
	return ob_get_clean();
}
