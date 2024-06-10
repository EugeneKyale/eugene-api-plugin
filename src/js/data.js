jQuery( document ).ready( function ( $ ) {
    // Handler for the refresh button click.
    $( '#refresh-data-btn' ).click( function( e ) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl, // 'ajaxurl' is automatically defined in the admin by WordPress.
            type: 'POST',
            data: {
                action: 'eugene_fetch_data', // This should match the action hook in PHP.
                nonce: eugeneApiNonce // Assuming you've localized this nonce with wp_localize_script.
            },
            success: function( response ) {
                if ( response.success ) {
                    // Handle the success case, perhaps updating the HTML of a table or a div.
                    console.log( 'Data refreshed:', response.data );
                } else {
                    // Handle the failure case.
                    console.error( 'Failed to fetch data:', response.data );
                }
            },
            error: function() {
                console.error( 'AJAX error' );
            }
        });
    });
});
