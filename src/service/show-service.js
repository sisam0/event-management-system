function closeService(){
    window.href = "/service/service.php";
}

document.addEventListener('DOMContentLoaded', function() {
    // Current date state
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    
    // Month names
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                        'July', 'August', 'September', 'October', 'November', 'December'];
    
    // Get calendar elements
    const calendarGrid = document.querySelector('.calendar-grid');
    const monthDisplay = document.querySelector('.calendar-header span');
    const prevBtn = document.querySelector('.cal-nav:first-child');
    const nextBtn = document.querySelector('.cal-nav:last-child');
    
    // Get unavailable dates from PHP (passed via JSON)
    // This will be populated by PHP
    let unavailableDates = [];
    
    // Function to check if a date is unavailable
    function isDateUnavailable(year, month, day) {
        const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
        return unavailableDates.includes(dateStr);
    }
    
    // Function to render calendar
    function renderCalendar() {
        // Clear existing calendar grid (keep day names)
        const dayNames = document.querySelectorAll('.day-name');
        calendarGrid.innerHTML = '';
        
        // Add day names back
        dayNames.forEach(name => {
            calendarGrid.appendChild(name.cloneNode(true));
        });
        
        // Get first day of month and number of days
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();
        
        // Adjust for Monday as first day
        let startOffset = startingDay === 0 ? 6 : startingDay - 1;
        
        // Get days from previous month
        const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
        
        // Get today's date
        const today = new Date();
        const todayDate = today.getDate();
        const todayMonth = today.getMonth();
        const todayYear = today.getFullYear();
        
        // Generate calendar days
        const totalCells = Math.ceil((startOffset + daysInMonth) / 7) * 7;
        
        for (let i = 0; i < totalCells; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'day';
            
            let dayNumber;
            let monthYearForDate;
            let isOtherMonth = false;
            let isPastDate = false;
            let isUnavailable = false;
            
            if (i < startOffset) {
                // Previous month days
                const prevMonth = currentMonth === 0 ? 11 : currentMonth - 1;
                const prevYear = currentMonth === 0 ? currentYear - 1 : currentYear;
                dayNumber = prevMonthLastDay - startOffset + i + 1;
                isOtherMonth = true;
                isPastDate = true;
                monthYearForDate = { year: prevYear, month: prevMonth };
            } else if (i >= startOffset + daysInMonth) {
                // Next month days
                const nextMonth = currentMonth === 11 ? 0 : currentMonth + 1;
                const nextYear = currentMonth === 11 ? currentYear + 1 : currentYear;
                dayNumber = i - (startOffset + daysInMonth) + 1;
                isOtherMonth = true;
                monthYearForDate = { year: nextYear, month: nextMonth };
            } else {
                // Current month days
                dayNumber = i - startOffset + 1;
                monthYearForDate = { year: currentYear, month: currentMonth };
                
                // Check if this date is before today
                if (currentYear < todayYear || 
                    (currentYear === todayYear && currentMonth < todayMonth) ||
                    (currentYear === todayYear && currentMonth === todayMonth && dayNumber < todayDate)) {
                    isPastDate = true;
                }
            }
            
            // Check if date is unavailable (admin blocked)
            if (!isOtherMonth) {
                const dateYear = monthYearForDate.year;
                const dateMonth = monthYearForDate.month;
                if (isDateUnavailable(dateYear, dateMonth, dayNumber)) {
                    isUnavailable = true;
                }
            }
            
            dayDiv.textContent = dayNumber;
            
            // Add classes
            if (isOtherMonth) {
                dayDiv.classList.add('other-month');
            }
            if (isPastDate) {
                dayDiv.classList.add('past-date');
            }
            if (isUnavailable) {
                dayDiv.classList.add('unavailable');
            }
            
            // Mark weekend days
            const dayOfWeek = (i + 1) % 7;
            if (dayOfWeek === 6 || dayOfWeek === 0) {
                dayDiv.classList.add('weekend');
            }
            
            // Mark today
            if (currentYear === todayYear && 
                currentMonth === todayMonth && 
                dayNumber === todayDate && 
                !isOtherMonth) {
                dayDiv.classList.add('today');
            }
            
            calendarGrid.appendChild(dayDiv);
        }
        
        // Update month display
        monthDisplay.textContent = monthNames[currentMonth] + ' ' + currentYear;
        
        // Re-apply click functionality
        updateDayInteractivity();
    }
    
    // Function to update day interactivity
    function updateDayInteractivity() {
        const allDays = document.querySelectorAll('.calendar-grid .day');
        
        allDays.forEach(day => {
            const isOtherMonth = day.classList.contains('other-month');
            const isPastDate = day.classList.contains('past-date');
            const isUnavailable = day.classList.contains('unavailable');
            
            // Make days unclickable if: other month, past date, or unavailable
            if (isOtherMonth || isPastDate || isUnavailable) {
                day.style.cursor = 'default';
                day.style.opacity = '0.4';
                day.style.pointerEvents = 'none';
                
                // Different colors for different states
                if (isUnavailable) {
                    day.style.backgroundColor = '#e8d8d0';
                    day.style.color = '#b08878';
                    // Add a small icon to indicate unavailable
                    day.title = 'This date is unavailable';
                } else if (isOtherMonth || isPastDate) {
                    day.style.backgroundColor = '#f0ece6';
                    day.style.color = '#a89888';
                }
            } else {
                // Make available days clickable
                day.style.cursor = 'pointer';
                day.style.opacity = '1';
                day.style.pointerEvents = 'auto';
                day.style.backgroundColor = '';
                day.style.color = '';
                
                day.addEventListener('click', function(e) {
                    // Remove selected class from all clickable days
                    document.querySelectorAll('.calendar-grid .day:not(.other-month):not(.past-date):not(.unavailable).selected').forEach(d => {
                        d.classList.remove('selected');
                    });
                    
                    // Add selected class to clicked day
                    this.classList.add('selected');
                    
                    // Log selected date
                    const dayNumber = this.textContent.trim();
                    console.log('Selected date: ' + monthNames[currentMonth] + ' ' + dayNumber + ', ' + currentYear);
                });
            }
        });
    }
    
    // Navigation functions
    function goToPreviousMonth() {
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        renderCalendar();
    }
    
    function goToNextMonth() {
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        renderCalendar();
    }
    
    // Add event listeners to navigation buttons
    prevBtn.addEventListener('click', goToPreviousMonth);
    nextBtn.addEventListener('click', goToNextMonth);
    
    // Initial render
    renderCalendar();
});