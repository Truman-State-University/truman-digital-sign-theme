// Create two variable with the names of the months and days in an array
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
var dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
var currentTime;

jQuery(window).ready(function () {
    initClock();
});

function startClockInterval() {
    if (!clockInterval) {
        updateClock();
        var clockInterval = setInterval(function () {
            updateClock()
        }, 1000);
    }
}

function updateClock() {
    //var currentTime = new Date();
    currentTime = new Date(currentTime.getTime() + 1000);
    var currentHours = currentTime.getHours();
    var currentMinutes = currentTime.getMinutes();

    // Pad the minutes and seconds with leading zeros, if required
    currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;

    // Convert the hours component to 12-hour format if needed
    currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;

    // Convert an hours component of "0" to "12"
    currentHours = (currentHours == 0) ? 12 : currentHours;

    // Compose the string for display
    var currentTimeString = currentHours + ":" + currentMinutes;


    jQuery(".time").html(currentTimeString);
    jQuery('.date').html(dayNames[currentTime.getDay()] + " " + monthNames[currentTime.getMonth()] + ' ' + currentTime.getDate() + ', ' + currentTime.getFullYear());
}

function initClock() {
    if (useServerTime == 1) {
        jQuery.post(ajax_object.ajax_url + '?action=get_time', function (data, status) {
            if (status == 'success') {
                currentTime = new Date(parseInt(data));
                updateClock();
                startClockInterval();
            }
        });
    } else {
        currentTime = new Date();
        updateClock();
        startClockInterval();
    }

}

