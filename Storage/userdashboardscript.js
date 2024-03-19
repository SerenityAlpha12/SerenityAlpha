function sendMessage(person, inputId, messagesId) {
    var messageInput = document.getElementById(inputId);
    var chatMessages = document.getElementById(messagesId);

    if (messageInput.value.trim() !== '') {
        var message = document.createElement('div');
        message.textContent = person + ': ' + messageInput.value;
        message.className = 'sent-message';
        chatMessages.appendChild(message);
        messageInput.value = '';
    }

    return false; // Prevent form submission
}

// JavaScript for the calendar
const calendarDates = document.getElementById("calendar-dates");
const currentMonthElement = document.getElementById("current-month");
const appointmentButton = document.querySelector(".calendar-button");
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();

function generateCalendar() {
    calendarDates.innerHTML = "";

    // Set the current month name
    currentMonthElement.textContent = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(currentDate);

    // Get the first day of the month
    const firstDay = new Date(currentYear, currentMonth, 1);

    // Get the last day of the month
    const lastDay = new Date(currentYear, currentMonth + 1, 0);

    // Get the number of days in the month
    const totalDays = lastDay.getDate();

    // Get the day of the week for the first day
    const startDay = firstDay.getDay();

    // Create blank cells for the days before the start day
    for (let i = 0; i < startDay; i++) {
        const blankCell = document.createElement("div");
        blankCell.classList.add("date", "blank");
        calendarDates.appendChild(blankCell);
    }

    // Create cells for each day in the month
    for (let day = 1; day <= totalDays; day++) {
        const dateCell = document.createElement("div");
        dateCell.classList.add("date");
        dateCell.textContent = day;

        // Highlight the current date
        if (currentDate.getDate() === day && currentDate.getMonth() === currentMonth && currentDate.getFullYear() === currentYear) {
            dateCell.classList.add("current-date");
        }

        dateCell.addEventListener("click", () => handleDateClick(day));

        calendarDates.appendChild(dateCell);
    }
}

function handleDateClick(day) {
    // Open the modal
    openModal();

    // Display the selected date in the modal
    document.getElementById("selectedDate").textContent = `${day}-${currentMonth + 1}-${currentYear}`;
}

function handleAppointmentBooking() {
    // Your logic to add an appointment to the current date
    console.log("Appointment booked for:", currentDate);
}

function prevMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    updateCurrentDate();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    updateCurrentDate();
}

function updateCurrentDate() {
    currentDate = new Date(currentYear, currentMonth, currentDate.getDate());
    generateCalendar();
}

// Add event listener to the appointment button
appointmentButton.addEventListener("click", handleAppointmentBooking);

// Initial calendar generation
generateCalendar();




// Add these functions to your userscript.js
function openModal() {
    // Update selectedDate with the current date
    const selectedDateElement = document.getElementById("selectedDate");
    selectedDateElement.textContent = `${currentDate.getDate()}-${currentMonth + 1}-${currentYear}`;

    // Display the modal
    document.getElementById("modal").style.display = "block";
}


function closeModal() {
    document.getElementById("modal").style.display = "none";
}



function openProfileModal() {
    document.getElementById("modal-profile").style.display = "block";
}

function closeProfileModal() {
    document.getElementById("modal-profile").style.display = "none";
}

function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}