jQuery(function($) {
    $('#event-search-form').on('submit', function(e) {
        e.preventDefault();

        var keyword = $('#event-search-input').val();
        var date = $('#event-date-input').val(); // Get the selected date

        if (keyword.length === 0) {
            $('#dropdown-result').html('<p>Please enter a search term.</p>');
            return;
        }

        // Make an AJAX request to the REST API
        $.ajax({
            url: myScriptData.rest_url,
            method: 'GET',
            data: {
                keyword: keyword,
                date: date, // Pass the selected date
            },
            success: function(response) {
                console.log('Response:', response);

                var resultsHTML = '';

                if (response.length > 0) {
                    response.forEach(function(event) {
                        resultsHTML += `<p><a href="${event.url}">${event.title}</a> - ${event.start}</p>`;
                    });
                } else {
                    // Display a specific message if no events are found
                    if (date) {
                        resultsHTML = `<p>No events found on ${date}.</p>`;
                    } else {
                        resultsHTML = '<p>No events found.</p>';
                    }
                }

                // Update the search results container
                $('#dropdown-result').html(resultsHTML);
            },
            error: function() {
                console.error('An error occurred while fetching events.');
                $('#dropdown-result').html('<p>An error occurred. Please try again.</p>');
            }
        });
    });
});
