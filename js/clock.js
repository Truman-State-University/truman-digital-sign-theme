function updateClock() {
    var currentTime = new Date();
    var currentHours = currentTime.getHours ( );
    var currentMinutes = currentTime.getMinutes ( );

    // Pad the minutes and seconds with leading zeros, if required
    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;

    // Convert the hours component to 12-hour format if needed
    currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

    // Convert an hours component of "0" to "12"
    currentHours = ( currentHours == 0 ) ? 12 : currentHours;

    // Compose the string for display
    var currentTimeString = currentHours + ":" + currentMinutes;


    jQuery("#time").html(currentTimeString);
    jQuery('#date').html(dayNames[currentTime.getDay()] + " " + monthNames[currentTime.getMonth()] + ' ' + currentTime.getDate() +  ', ' + currentTime.getFullYear());
}
updateClock();
var currentTime = new Date();
secondsLeft = 60-currentTime.getSeconds();
console.log(secondsLeft);
setTimeout(function() {
        startClockInterval();
    },secondsLeft*1000);

function startClockInterval() {
    console.log('running startClockInterval');
    if (!clockInterval) {
        updateClock();
        var clockInterval = setInterval(function () {
            updateClock()
        }, 1000);
    }
}