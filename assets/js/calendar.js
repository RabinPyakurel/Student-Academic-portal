const currDate = document.querySelector('.current-date'),
    daysTag = document.querySelector('.days'),
    prevNextIcon = document.querySelectorAll('.icons span');


let currentDate = new Date();
let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];


const fetchAttendanceData = (month, year, callback) => {
    $.ajax({
        url: 'attendance_data.php',
        type: 'GET',
        data: {
            action: 'calendar',
            month: month + 1,
            year: year
        },
        dataType: 'json',
        success: function (response) {
            callback(response);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching attendance data:", error);
            callback([]);
        }
    });
};


const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(),
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(),
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(),
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate();

    fetchAttendanceData(currMonth, currYear, function (attendanceData) {
        let liTag = "";


        for (let i = firstDayofMonth; i > 0; i--) {
            liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
        }

        for (let i = 1; i <= lastDateofMonth; i++) {

            let isToday =
                i === currentDate.getDate() &&
                    currMonth === currentDate.getMonth() &&
                    currYear === currentDate.getFullYear()
                    ? "active"
                    : "";


            const attendance = attendanceData.find(item => item.day === i);
            let statusClass = "";
            let statusText = "No Status";
            if (attendance) {
                statusClass =
                    attendance.status === "Present" ? "present" :
                        attendance.status === "Late" ? "late" :
                            attendance.status === "Absent" ? "absent" : "no-status";

                statusText = attendance.status === "Present" ? "Present" :
                    attendance.status === "Late" ? "Late" :
                        attendance.status === "Absent" ? "Absent" : "";
            }


            liTag += `<li class="${isToday} ${statusClass}" title="${statusText}">${i}</li>`;
        }


        for (let i = lastDayofMonth; i < 6; i++) {
            liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
        }

        currDate.innerHTML = `${months[currMonth]} ${currYear}`;
        daysTag.innerHTML = liTag;
    });
};


prevNextIcon.forEach((icon) => {
    icon.addEventListener('click', () => {

        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;


        if (currMonth < 0 || currMonth > 11) {
            date = new Date(currYear, currMonth);
            currYear = date.getFullYear();
            currMonth = date.getMonth();
        }

        renderCalendar();
    });
});


renderCalendar();
