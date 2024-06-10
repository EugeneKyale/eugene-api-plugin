jQuery(document).ready(function($) {
    $('#refresh-data-btn').click(function(e) {
        e.preventDefault();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'eugene_fetch_data',
                nonce: eugeneApiNonce
            },
            success: function(response) {
                if (response.success) {
                    console.log('Data refreshed:', response.data);
                    updateTable(response.data);
                } else {
                    console.error('Failed to fetch data:', response.data);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', status, error);
            }
        });
    });

    function updateTable(data) {
        var $table = $('.wp-list-table tbody');
        $table.empty(); // Clear existing rows

        if (data && data.data && data.data.rows) {
            $.each(data.data.rows, function(index, row) {
                var date = new Date(row.date * 1000).toISOString();
                $table.append(`<tr>
                    <td>${row.id}</td>
                    <td>${row.fname}</td>
                    <td>${row.lname}</td>
                    <td>${row.email}</td>
                    <td>${date}</td>
                </tr>`);
            });
        } else {
            $table.append('<tr><td colspan="5">No data available</td></tr>');
        }
    }
});
