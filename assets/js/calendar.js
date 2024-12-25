const currDate = document.querySelector('.current-date'),
    daysTag = document.querySelector('.days'),
    prevNextIcon = document.querySelectorAll('.icons span');

// Getting the current date, year, and month
let currentDate = new Date(); // This keeps the original current date
let date = new Date(), // This is the navigable date
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), // Getting the first day of the month
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), // Getting the last date of the month
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), // Getting the last day of the month
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); // Getting the last date of the previous month

    let liTag = "";

    // Adding previous month's last days
    for (let i = firstDayofMonth; i > 0; i--) {
        liTag += `<li class="inactive">${lastDateofLastMonth - i + 1}</li>`;
    }

    // Adding current month's days
    for (let i = 1; i <= lastDateofMonth; i++) {
        // Check if the current date matches today's date
        let isToday =
            i === currentDate.getDate() &&
            currMonth === currentDate.getMonth() &&
            currYear === currentDate.getFullYear()
                ? "active"
                : "";
        liTag += `<li class="${isToday}">${i}</li>`;
    }

    // Adding next month's first days
    for (let i = lastDayofMonth; i < 6; i++) {
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }

    currDate.innerHTML = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;
};

renderCalendar();

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

        renderCalendar();
    });
});
