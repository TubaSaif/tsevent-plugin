console.log('restsearch.js loaded successfully.');

jQuery(function($) {
    $('#event-search-form').on('submit', function(e) {
        e.preventDefault(); 

        var keyword = $('#event-search-input').val(); 

        if (keyword.length === 0) {
            $('#dropdown-result').html('<p>Please enter a search term.</p>');
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
                    response.forEach(function(event) {
                        resultsHTML += `<p><a href="${event.url}">${event.title}</a></p>${event.start}` ;
                    });
                } else {
                    resultsHTML = '<p>No events found.</p>';
                }

                // Update the search results container with the results
                $('#dropdown-result').html(resultsHTML);
                // $('#dropdown-result').html('<p>Test content</p>');

            },
            error: function() {
                console.error('An error occurred while fetching events.'); 
                $('#dropdown-result').html('<p>An error occurred. Please try again.</p>');
            }
        });
    });
});
