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
                var showViewAllButton = false;  // To control the display of the "View All" button

                if (response.length > 0) {
                    // Loop through the events and display the first 4
                    for (var i = 0; i < response.length; i++) {
                        if (i < 4) {
                            resultsHTML += `<p><a href="${response[i].url}">${response[i].title}</a> - ${response[i].start}</p>`;
                        } else {
                            showViewAllButton = true;  // Show the "View All" button after 4 events
                        }
                    }

                    // If there are more than 4 events, show the "View All" button
                    if (showViewAllButton) {
                        var viewAllUrl = myScriptData.view_all_url + '?s=' + encodeURIComponent(keyword);
                        // if (date) {
                        //     viewAllUrl += '&date=' + encodeURIComponent(date);
                        // }
                        resultsHTML += '<p><a href="' + viewAllUrl + '" class="view-all-button">View All Events</a></p>';
                    }
                } else {
                    // Display a message if no events are found
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
