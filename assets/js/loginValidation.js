const username = document.getElementById('username');
const pass = document.getElementById('pass');
const usernameMsg = document.getElementById('usernameMsg');
const passMsg = document.getElementById('passMsg');

const loginValidation = () => {
    if (username.value == '') {
        usernameMsg.innerHTML = 'username is required';
        return false;
    }
    if (pass.value == '') {
        passMsg.innerHTML = 'password is required';
        return false;
    }
    return true
}

username.addEventListener('input', () => {
    usernameMsg.innerHTML = '';
});

pass.addEventListener('input', () => {
    passMsg.innerHTML = '';
});

$(document).ready(function () {
    $(".form").submit(function (event) {
        event.preventDefault();
        if (loginValidation()) {
            $("#usernameMsg").html('');
            $("#passMsg").html('');
            $("#loader-container").show();
            var formData = $(this).serialize();
            $.ajax({
                type: "POST",
                url: "/backend/login.php",
                data: formData,
                success: function (response) {
                    $("#loader-container").hide();

                    if (response.includes("login successful")) {
                        window.location.href = "/index.htm";
                    } else {
                        $("#pass").val("");
                        $("#passMsg").html(response);
                    }
                },
                error: function (xhr, status, error) {
                    $("#loader-container").hide();
                    alert("Error occurred while processing your data");
                    console.error("Status:", status, "Error:", error, "Response:", xhr.responseText);
                }
            });
        }
    });
});