// jQuery(document).ready(function ($) {
//     // Handle AJAX Search
//     $('#event-search-form').on('submit', function (e) {
//         e.preventDefault();
//         const keyword = $('#event-keyword').val();

//         $.ajax({
//             url: events_plugin.rest_url + '/search',
//             method: 'GET',
//             data: { keyword },
//             success: function (response) {
//                 $('#event-results').html('');
//                 response.forEach(event => {
//                     $('#event-results').append(`<li><a href="${event.link}">${event.title}</a></li>`);
//                 });
//             },
//         });
//     });

//     // Initialize Google Maps if required
//     if (typeof google !== 'undefined') {
//         // Custom logic for initializing maps
//     }
// });
