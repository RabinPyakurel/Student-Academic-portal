const currDate = document.querySelector('.current-date'),
    daysTag = document.querySelector('.days'),
    prevNextIcon = document.querySelectorAll('.icons span');


//getting new date, current year and month
let date = new Date(),
    currYear = date.getFullYear(),
    currMonth = date.getMonth();

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

const renderCalendar = () => {
    let firstDayofMonth = new Date(currYear, currMonth, 1).getDay(), //getting first day of the month
        lastDateofMonth = new Date(currYear, currMonth + 1, 0).getDate(), //getting last date of the month
        lastDayofMonth = new Date(currYear, currMonth, lastDateofMonth).getDay(), //getting last day of the month
        lastDateofLastMonth = new Date(currYear, currMonth, 0).getDate(); //getting last date of the last month
    let liTag = "";

    for (let i = firstDayofMonth; i > 0; i--) { //creating list of previous month last days
        liTag += `<li class="inactive"> ${lastDateofLastMonth - i + 1}</li>`;
    }

    for (let i = 1; i <= lastDateofMonth; i++) { //creating list of current month days
        let isToday = i === date.getDate() && currMonth === date.getMonth() && currYear === date.getFullYear() ? "active" : ""; //checking if the day is today
        liTag += `<li class="${isToday}">${i}</li>`;
    }

    for (let i = lastDayofMonth; i < 6; i++) { //creating list of next month first days
        liTag += `<li class="inactive">${i - lastDayofMonth + 1}</li>`;
    }

    currDate.innerHTML = `${months[currMonth]} ${currYear}`;
    daysTag.innerHTML = liTag;
}
renderCalendar();

prevNextIcon.forEach(icon => {
    icon.addEventListener('click', () => {//adding click event to the icons
        //if clicked on previous icon, decrease the current month by 1 else increase the current month by 1
        currMonth = icon.id === "prev" ? currMonth - 1 : currMonth + 1;

        if (currMonth < 0 || currMonth > 11) {//if the current month is less than 0 or greater than 11, change the current year
            date = new Date(currYear, currMonth);
            currYear = date.getFullYear(); //updating the current year with the new year
            currMonth = date.getMonth(); //updating the current month with the new month

        } else { //else pass new Date as date value
            date = new Date();
        }
        renderCalendar();
    });
});