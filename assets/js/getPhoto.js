$(document).ready(function () {
    $.ajax({
        url: '/backend/getPhoto.php',
        dataType: 'json',
        success: function (response) {
            if (response && response.url) {
                $('.user-image').attr('src', response.url);
            } else {
                console.warn(response.message || "No photo URL found.");
            }
        },
        error: function () {
            console.error("Failed to fetch the profile image.");
        }
    });
});
