jQuery(function($) {
    $('#event-search-form').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting the traditional way

        var keyword = $('#event-search-input').val(); // Get the search term from the input field

        if (keyword.length === 0) {
            $('#event-search-results').html('<p>Please enter a search term.</p>');
            return;
        }

        // Make an AJAX request to the REST API
        $.ajax({
            url: myScriptData.rest_url,
            method: 'GET',
            data: {
                keyword: keyword, // Pass the keyword to the API
            },
            success: function(response) {
                console.log('Response:', response); 
                console.log(myScriptData.rest_url);

                var resultsHTML = '';

                if (response.length > 0) {
                    // Loop through the search results and display them
                    response.forEach(function(event) {
                        resultsHTML += '<p><a href="' + event.link + '">' + event.title + '</a></p>';
                    });
                } else {
                    resultsHTML = '<p>No events found.</p>';
                }

                // Update the search results container with the results
                $('#event-search-results').html(resultsHTML);
            },
            error: function() {
                console.error('An error occurred while fetching events.'); 
                $('#event-search-results').html('<p>An error occurred. Please try again.</p>');
            }
        });
    });
});
