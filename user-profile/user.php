<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    include 'not-found.htm';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/user_profile.css">
</head>

<body>
    <?php include '../layout/nav.htm'?>
    <?php include '../layout/skeleton.htm' ?>
    <main>
    <div class="container">
        <div class="profile-header">
            <div>
                <img id="userImage" class="user-image" src="" alt="user-image"><br>
                <label for="photoUpload">Upload Photo</label>
                <input type="file" id="photoUpload" accept="image/*" style="display: none;">
            </div>
            <div class="info-divider"></div>
            <div class="info">
                <h2 id="name"></h2>
                <p id="clz-email"></p>
                <p id="program-name"></p>
                <p id="batch-date"></p>
            </div>
        </div>

        <div class="profile-tabs">
            <button class="active" onclick="showTab('basic-info')">Basic Info</button>
            <button onclick="showTab('contact')">Contact</button>
            <button onclick="showTab('academic')">Academic</button>
            <button onclick="showTab('identification')">Identification</button>
        </div>

        <div class="edit-controls">
            <button id="edit-btn" onclick="toggleEditMode()">Edit</button>
            <button id="save-btn" style="display: none;" onclick="saveChanges()">Save</button>
            <button id="cancel-btn" style="display: none;" onclick="cancelChanges()">Cancel</button>
        </div>
        <div id="basic-info" class="form-section active">
            <form id="basic-info-form">
                <label>Full Name:</label>
                <input type="text" id="full-name" value="" readonly>

                <label>Primary Email:</label>
                <input type="email" id="primary-email" value="" readonly>

                <label>Personal Email:</label>
                <input type="email" id="personal-email" value="" readonly>

                <label>Phone Number:</label>
                <input type="tel" id="phone-number" value="" readonly>

                <label>Date of Birth:</label>
                <input type="date" id="dob" value="" readonly>
            </form>
        </div>

        <div id="contact" class="form-section">
            <form>
                <label>Emergency Contact Name:</label>
                <input type="text" id="emg-contact-name" placeholder="Emergency Contact Name" value="" readonly>

                <label>Emergency Contact Relation:</label>
                <input type="text" id="emg-contact-rel" placeholder="Relation (e.g., Father)" value="" readonly>

                <label>Emergency Contact Number:</label>
                <input type="tel" id="emg-contact-num" placeholder="Emergency Contact Number" value="" readonly>

                <label>Guardian Contact:</label>
                <input type="tel" id="emg-grd-contact" placeholder="Guardian Contact" value="" readonly>
            </form>
        </div>

        <div id="academic" class="form-section">
            <form>
                <label for="program">Program/Course:</label>
                <input type="text" id="program" value="BCA" readonly>

                <label for="batch">Batch:</label>
                <input type="text" id="batch" value="2079" readonly>

                <label for="semester">Semester:</label>
                <input type="text" id="semester" value="fourth" readonly>

                <label for="roll">Roll No.:</label>
                <input type="text" id="roll" value="45602132" readonly>

                <label for="department">Department:</label>
                <input type="text" id="department" value="DOIT" readonly>
            </form>
        </div>
        <div id="identification" class="form-section">
            <form>
                <label for="document-type">Document Type:</label>
                <input type="text" id="document-type" value="student id card" readonly>

                <label for="id-number">Identification Number:</label>
                <input type="text" id="id-number" value="5639" readonly>

                <label for="university">University:</label>
                <input type="text" id="university" value="Tribhuvan" readonly>

                <label for="university-reg">University Registration Number:</label>
                <input type="text" id="university-reg" value="6-2-35-63-2024" readonly>
            </form>
        </div>
    </div>
    </main>
    <?php include '../layout/footer.htm' ?>
    <script src="../assets/js/user_profile.js"></script>
    <script src="../assets/js/getPhoto.js"></script>
</body>

</html>