function updateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = now.toLocaleDateString('en-US', options);
    document.getElementById('currentDateTime').textContent = formattedDate;
}

// Update the time every second
setInterval(updateTime, 1000);