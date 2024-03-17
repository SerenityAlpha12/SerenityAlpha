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
    // Add your logic for handling date clicks (e.g., displaying tasks)
    console.log(`Clicked on ${day}-${currentMonth + 1}-${currentYear}`);
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

// Initial calendar generation
generateCalendar();

function handleDateClick(day) {
    // Open the modal
    openModal();

    // Display the selected date in the modal
    document.getElementById("selectedDate").textContent = `${day}-${currentMonth + 1}-${currentYear}`;
}




// Add these functions to your userscript.js
function openModal() {
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


