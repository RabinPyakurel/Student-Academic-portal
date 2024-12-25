$(document).ready(function () {
    // Load monthly summary data
    $.ajax({
        url: 'attendance_data.php',
        method: 'GET',
        data: {
            action: 'monthly'
        },
        dataType: 'json',
        success: function (data) {
            const tableBody = $('#table-body');
            tableBody.empty();
            data.forEach((item) => {
                tableBody.append(`<tr>
                    <td>${item.month_year}</td>
                    <td>${item.attendance_percentage}%</td>
                    <td><a href="#" class="view-details" data-date="${item.month_year}">View</a></td>
                </tr>`);
            });
        }
    });

    // Load overall attendance data
    $.ajax({
        url: 'attendance_data.php',
        method: 'GET',
        data: {
            action: 'overall'
        },
        dataType: 'json',
        success: function (data) {
            const percentage = data[0].attendance_percentage;
            $('.percentage').text(`${percentage}% Attendance`);
            $('.circle').css('background', `conic-gradient(#3498db ${percentage}%, #ddd ${percentage}% 100%)`);
        }
    });

    // Open modal on "View Details"
    $(document).on('click', '[data-date]', function (e) {
        e.preventDefault();
        const monthYear = $(this).data('date');

        // Fetch attendance details for the selected month
        $.ajax({
            url: 'attendance_data.php',
            method: 'GET',
            data: {
                action: 'details',
                month_year: monthYear
            },
            dataType: 'json',
            success: function (data) {
                const modalDetails = $('#modal-details');
                modalDetails.empty();

                if (data.length > 0) {
                    const table = $('<table>');
                    table.append('<thead><tr><th>Date</th><th>Status</th><th>Remarks</th></tr></thead>');
                    const tbody = $('<tbody>');

                    data.forEach((item) => {
                        tbody.append(`<tr>
                            <td>${item.date}</td>
                            <td>${item.status}</td>
                            <td>${item.remarks || 'N/A'}</td>
                        </tr>`);
                    });

                    table.append(tbody);
                    modalDetails.append(table);
                } else {
                    modalDetails.append('<p>No attendance details available for this month.</p>');
                }

                $('#modal').fadeIn();
                $('#modal-title').text(`Attendance Details for ${monthYear}`);
            },
            error: function () {
                alert('Failed to fetch attendance details.');
            }
        });
    });

    // Close the modal
    $('.close').on('click', function () {
        $('#modal').fadeOut();
    });

    // Close the modal when clicking outside
    $(window).on('click', function (e) {
        if ($(e.target).is('#modal')) {
            $('#modal').fadeOut();
        }
    });
});