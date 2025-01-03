let isEditing = false;
const editableFields = ['personal-email', 'phone-number', 'emg-contact-name', 'emg-contact-rel', 'emg-contact-num', 'emg-grd-contact', 'id-number', 'university-reg'];
const originalValues = {};

function showTab(tabId) {
    document.querySelectorAll('.form-section').forEach(section => {
        section.classList.remove('active');
    });
    document.querySelectorAll('.profile-tabs button').forEach(button => {
        button.classList.remove('active');
    });
    document.getElementById(tabId).classList.add('active');
    event.target.classList.add('active');
}

function toggleEditMode() {
    isEditing = !isEditing;

    document.getElementById('edit-btn').style.display = isEditing ? 'none' : 'block';
    document.getElementById('save-btn').style.display = isEditing ? 'inline' : 'none';
    document.getElementById('cancel-btn').style.display = isEditing ? 'inline' : 'none';

    editableFields.forEach(field => {
        const input = document.getElementById(field);
        if (isEditing) {
            originalValues[field] = input.value; // Save original value
            input.readOnly = false;
        } else {
            input.readOnly = true;
        }
    });
}


function cancelChanges() {
    editableFields.forEach(field => {
        document.getElementById(field).value = originalValues[field];
    });
    toggleEditMode();
}

//handle uploaded photo and store to database
document.getElementById('photoUpload').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();

        reader.onload = function (event) {
            document.getElementById('userImage').setAttribute('src', event.target.result);
        }

        reader.readAsDataURL(file);

        const formData = new FormData();
        formData.append('photo', file)

        $.ajax({
            url: 'photo_upload.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            }
        })
    }
});

//update changes and reflect
function saveChanges() {
    const updatedData = new FormData();
    updatedData.append('personalEmail', $('#personal-email').val());
    updatedData.append('phoneNumber', $('#phone-number').val());
    updatedData.append('emgContactName', $('#emg-contact-name').val());
    updatedData.append('emgContactRel', $('#emg-contact-rel').val());
    updatedData.append('emgContactNum', $('#emg-contact-num').val());
    updatedData.append('emgGrdContact', $('#emg-grd-contact').val());

    $.ajax({
        url: 'updateData.php',
        method: 'POST',
        data: updatedData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                alert("Changes saved successfully");
                toggleEditMode();
            } else {
                alert("Failed to save changes: " + response.message);
                toggleEditMode();
            }
        },
        error: function () {
            alert("Error in saving changes");
        }
    });
}


$(document).ready(function () {
    $('.skeleton-container').show();
    $('main').hide();
    // ✅ Fetch User Data on Page Load
    function fetchUserProfile() {
        $.ajax({
            url: 'fetchData.php',
            dataType: 'json',
            success: function (response) {
                $('.skeleton-container').hide();
                $('main').show();
                if (response.success) {
                    let info = response.data[0];
                    //personal info 
                    $('#name').html(info.name);
                    $('#full-name').val(info.name);

                    $('#clz-email').html(info.email);
                    $('#primary-email').val(info.email);

                    $('#personal-email').val(info.personal_email);
                    $('#phone-number').val(info.contact_number);
                    $('#dob').val(info.dob);

                    //contact section
                    $('#emg-contact-name').val(info.emergency_name);
                    $('#emg-contact-rel').val(info.relation);
                    $('#emg-contact-num').val(info.contact);
                    $('#emg-grd-contact').val(info.guardian_contact);

                    //academic-info
                    $('#program-name').html("Program: " + info.program_name.toUpperCase());
                    $('#program').val(info.program_name.toUpperCase());

                    $('#batch-date').html("Batch: " + info.enrollment_year);
                    $('#batch').val(info.enrollment_year);

                    $('#department').val(info.dept_name.toUpperCase());
                    $('#id-number').val(info.std_id);

                    $('#semester').val(info.semester);

                } else {
                    alert('Error fetching profile: ' + response.message);
                }
            },
        });
    }

    fetchUserProfile(); // Call on page load
});
