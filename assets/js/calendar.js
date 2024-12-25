const currDate = document.querySelector('.current-date'),
    daysTag = document.querySelector('.days'),
    prevNextIcon = document.querySelectorAll('.icons span');

// Getting the current date, year, and month
let currentDate = new Date(); // This keeps the original current date
let date = new Date(), // This is the navigable date
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

// Function to fetch attendance data using AJAX
const fetchAttendanceData = (month, year, callback) => {
    $.ajax({
        url: 'attendance_data.php', // Backend endpoint to fetch attendance
        type: 'GET',
        data: {
            action: 'calendar',
            month: month + 1, // Adjust month (0-indexed in JavaScript)
            year: year
        },
        dataType: 'json',
        success: function (response) {
            callback(response); // Pass the data to the callback function
        },
        error: function (xhr, status, error) {
            console.error("Error fetching attendance data:", error);
            callback([]); // Return an empty array on error
        }
    });
};

// Function to render the calendar with attendance data
const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // Getting the first day of the month
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // Getting the last date of the month
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // Getting the last day of the month
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // Getting the last date of the previous month

    // Fetch attendance data via AJAX
    fetchAttendanceData(currMonth, currYear, function (attendanceData) {
        let liTag = "";

        // Adding previous month's last days
        for (let i = firstDayofMonth; i > 0; i--) {
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        // Adding current month's days with attendance marking
        for (let i = 1; i <= lastDateofMonth; i++) {
            // Check if the current date matches today's date
            let isToday =
                i === currentDate.getDate() &&
                    currMonth === currentDate.getMonth() &&
                    currYear === currentDate.getFullYear()
                    ? "active"
                    : "";

            // Find the attendance status for the current day
            const attendance = attendanceData.find(item => item.day === i);
            let statusClass = "";
            let statusText = "No Status"; // Default if no status is available
            if (attendance) {
                statusClass =
                    attendance.status === "Present" ? "present" :
                        attendance.status === "Late" ? "late" :
                            attendance.status === "Absent" ? "absent" : "no-status";

                statusText = attendance.status === "Present" ? "Present" :
                    attendance.status === "Late" ? "Late" :
                        attendance.status === "Absent" ? "Absent" : "";
            }

            // Add the day with hover effect to display status
            liTag += `<li class="${isToday} ${statusClass}" title="${statusText}">${i}</li>`;
        }

        // Adding next month's first days
        for (let i = lastDayofMonth; i < 6; i++) {
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }

        currDate.innerHTML = `${months[currMonth]} ${currYear}`;
        daysTag.innerHTML = liTag;
    });
};

// Attach event listeners to navigation icons
prevNextIcon.forEach((icon) => {
    icon.addEventListener('click', () => {
        // If clicked on the previous icon, decrease the current month by 1; otherwise, increase it
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

        // Adjust the year if the month goes out of bounds
        if (currMonth < 0 || currMonth > 11) {
            date = new Date(currYear, currMonth);
            currYear = date.getFullYear(); // Update the current year with the new year
            currMonth = date.getMonth(); // Update the current month with the new month
        }

        renderCalendar(); // Re-render the calendar for the updated month
    });
});

// Initial render
renderCalendar();
