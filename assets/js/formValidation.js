//for registration validation
let error = document.getElementsByClassName("error");
const name = document.getElementById("fname");
const dob = document.getElementById("dob");
const course = document.getElementById("course");
const enroll = document.getElementById("enroll");
const contact = document.getElementById("phone");
const personalEmail = document.getElementById("p_email");
const email = document.getElementById("email");
const id = document.getElementById("id");
const password = document.getElementById("password");
const cpass = document.getElementById("cpass");

const require = "This field is required";
const validateName = () => {
  const namePattern = /^[A-Za-z ]+$/;
  if (!name.value.match(namePattern)) {
    error[0].innerHTML = "name must be alphabetic";
    return false;
  } else if (name.value.length < 3) {
    error[0].innerHTML = "name must be atleast 3 letters";
    return false;
  } else {
    error[0].innerHTML = "";
    return true;
  }
};

const validateDob = () => {
  const currDate = new Date();
  const minAge = 16;
  const dateOfBirth = new Date(dob.value);
  if (dateOfBirth > currDate) {
    error[1].innerHTML = "invalid date";
    return false;
  } else if (currDate.getFullYear() - dateOfBirth.getFullYear() < minAge) {
    error[1].innerHTML = "age must be atleast 16 years";
    return false;
  } else {
    error[1].innerHTML = "";
    return true;
  }
};

const validateCourse = () => {
  if (course.value !== "") {
    error[2].innerHTML = "";
    return true;
  }
};
const validateYear = () => {
  enroll.value = enroll.value.replace(/\s+/g, "");
  const enrollYearPattern = /^2[0-9]{3}$/;
  if (!enroll.value.match(enrollYearPattern)) {
    error[3].innerHTML = "year must be numeric and in B.S.";
    return false;
  } else {
    error[3].innerHTML = "";
    return true;
  }
};

const validateContact = () => {
  contact.value = contact.value.replace(/\s+/g, "");
  error[4].innerHTML = "";
  if (contact.value === "") {
    return true;
  }
  if (!/^\d+$/.test(contact.value)) {
    error[4].innerHTML = "Contact must be Numeric";
    return false;
  }
  if (!contact.value.match(/^(97|98)/)) {
    error[4].innerHTML = "Contact must start with 97 or 98";
    return false;
  }
  if (contact.value.length !== 10) {
    error[4].innerHTML = "Contact must be 10 digits";
    return false;
  }
  return true;
};

const validatePersonalEmail = () => {
  personalEmail.value = personalEmail.value.replace(/\s+/g, "");
  error[5].innerHTML = "";
  if (personalEmail.value === "") {
    return true;
  }
  const emailPattern = /^[A-Za-z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{3,}$/;
  if (!personalEmail.value.match(emailPattern)) {
    error[5].innerHTML = "Enter a valid email";
    return false;
  }

  return true;
};

const validateClzEmail = () => {
  email.value = email.value.replace(/\s+/g, "");
  const emailPattern = /^[A-Za-z]+\.[0-9]{3}@kathford\.edu\.np/;
  if (!email.value.match(emailPattern)) {
    error[6].innerHTML = "enter valid email provided by college";
    return false;
  } else {
    error[6].innerHTML = "";
    return true;
  }
};

const validateIdNumber = () => {
  id.value = id.value.replace(/\s+/g, "");
  if (!/^\d+$/.test(id.value)) {
    error[7].innerHTML = "Id number must be Numeric";
    return false;
  } else {
    error[7].innerHTML = "";
    return true;
  }
};
const validatePassword = () => {
  password.value = password.value.replace(/\s+/g, "");
  if (/([a-zA-Z0-9!@#$%^&*(),.?":{}|<>])\1{1,}/.test(password.value)) {
    error[8].innerHTML = "Must not contain repeated characters sequentially";
    return false;
  } else if (
    /(012|123|234|345|456|567|678|789|abc|bcd|cde|def|efg|fgh|ghi|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz)/i.test(
      password.value
    )
  ) {
    error[8].innerHTML = "Must not contain sequential characters";
    return false;
  } else if (!/[A-Z]/.test(password.value)) {
    error[8].innerHTML = "Must contain at least one uppercase letter";
    return false;
  } else if (!/[a-z]/.test(password.value)) {
    error[8].innerHTML = "Must contain at least one lowercase letter";
    return false;
  } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password.value)) {
    error[8].innerHTML = "Must contain at least one special character";
    return false;
  } else if (!/[0-9]/.test(password.value)) {
    error[8].innerHTML = "Must contain at least one number";
    return false;
  } else if (password.value.length < 8) {
    error[8].innerHTML = "Must be 8 character long";
    return false;
  } else {
    error[8].innerHTML = "";
    return true;
  }
};

const matchPasswords = () => {
  cpass.value = cpass.value.replace(/\s+/g, "");

  if (cpass.value !== password.value) {
    error[9].innerHTML = "Passwords do not match";
    return false;
  } else {
    error[9].innerHTML = "";
    return true;
  }
};
const showError = () => {
  name.addEventListener("input", validateName);
  dob.addEventListener("input", validateDob);
  course.addEventListener("change", validateCourse);
  enroll.addEventListener("input", validateYear);
  contact.addEventListener("input", validateContact);
  personalEmail.addEventListener("input", validatePersonalEmail);
  email.addEventListener("input", validateClzEmail);
  id.addEventListener("input", validateIdNumber);
  password.addEventListener("input", validatePassword);
  cpass.addEventListener("input", matchPasswords);
};
showError();

const validStep0 = () => {
  return validateName() && validateDob() && validateCourse() && validateYear();
};

const validStep1 = () => {
  return validateContact() && validatePersonalEmail();
};

const validStep2 = () => {
  return validateClzEmail() && validateIdNumber();
};

const validStep3 = () => {
  return validatePassword() && matchPasswords();
};

const validation = () => {
  return validStep0() && validStep1() && validStep2() && validStep3();
};

$(document).ready(function () {
    $(".form").submit(function (event) {
        event.preventDefault();
        if (validation()) {
            var formData = $(this).serialize();
            $("#loader-container").show();
            $.ajax({
                type: "POST",
                url: "/backend/registration.php",
                data: formData,
                success: function (response) {
                    $("#loader-container").hide();
                    alert(response);
                    $("#password").val("");
                    $("#cpass").val("");
                    if (response.includes("Registration successful.")) {
                        window.location.href = "sign-in.htm";
                    }
                },
                error: function (xhr, status, error) {
                    $("#loader-container").hide();
                    $("#password").val("");
                    $("#cpass").val("");
                    alert("Error occurred while processing your data");
                    console.error("Status:", status, "Error:", error, "Response:", xhr.responseText);
                }
            });
        }
    });
});
