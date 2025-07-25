<?php
date_default_timezone_set('Asia/Manila');

function build_calendar($month, $year) {
    // Database connection
    include "connections.php";

    // Fetch holidays and custom calendar settings
    $holidays = array();
    $calendar_settings = array();
    $stmt = $connections->prepare("SELECT calendar_date, calendar_description, slots, end_time, calendar_control FROM tbl_calendar");
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $holidays[$row['calendar_date']] = $row['calendar_description'];
            $calendar_settings[$row['calendar_date']] = $row;
        }
        $stmt->close();
    }

    // SQL query to count bookings for each day
    $stmt = $connections->prepare("SELECT appointment_date, COUNT(*) AS bookings_count FROM tbl_appointment WHERE MONTH(appointment_date) = ? AND YEAR(appointment_date) = ? GROUP BY appointment_date");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Store date and bookings count
                $bookings[$row['appointment_date']] = $row['bookings_count'];
            }
        }
        $stmt->close();
    }

    // Calendar generation code...
    $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    $firstDayOfMonth = mktime(0, 0, 0, (int)$month, 1, $year);
    $numberDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $datetoday = date('Y-m-d');
    $currentTime = date('H:i');

    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2><br>";
    $calendar .= "<a class='btn btn-xs btn-success' style='font-size: 18px;' href='?month=" . date('m', mktime(0, 0, 0, (int)$month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, (int)$month - 1, 1, $year)) . "'>Previous Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-danger' style='font-size: 18px;' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a> ";
    $calendar .= "<a class='btn btn-xs btn-primary' style='font-size: 18px;' href='?month=" . date('m', mktime(0, 0, 0, (int)$month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, (int)$month + 1, 1, $year)) . "'>Next Month</a></center><br>";

    $calendar .= "<tr>";
    foreach ($daysOfWeek as $day) {
        $headerClass = ($day == 'Saturday' || $day == 'Sunday') ? 'header not-available' : 'header';
        $calendar .= "<th class='$headerClass'>$day</th>";
    }

    $currentDay = 1;
    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $month = str_pad((int)$month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";

        $dayname = strtolower(date('l', strtotime($date)));
        $today = $date == date('Y-m-d') ? "today" : "";

        $isHoliday = isset($holidays[$date]);
        $slots = isset($calendar_settings[$date]['slots']) ? $calendar_settings[$date]['slots'] : 30;
        $endTime = isset($calendar_settings[$date]['end_time']) ? $calendar_settings[$date]['end_time'] : '15:00';
        $calendarControl = isset($calendar_settings[$date]['calendar_control']) ? $calendar_settings[$date]['calendar_control'] : '';

        // Determine if booking should be disabled
        $isWeekend = $dayname == 'saturday' || $dayname == 'sunday';
        $isPastDate = $date < $datetoday;
        $isPastCustomTime = ($date == $datetoday && $currentTime >= $endTime);
        
        $disableBooking = $isPastDate || $isPastCustomTime ||
                           ($isWeekend && (empty($calendarControl) || $calendarControl == 'Disable')) ||
                           (!$isWeekend && $calendarControl == 'Disable');

        if ($disableBooking) {
            $holidayText = $isHoliday ? "<br>".$holidays[$date] : "";
            $calendar .= "<td class='not-available'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>N/A</button>$holidayText";
        } elseif (isset($bookings[$date]) && $bookings[$date] >= $slots) {
            $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>Full</button>";
        } else {
            $slots_left = $slots - (isset($bookings[$date]) ? $bookings[$date] : 0);
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='book.php?date=" . $date . "' class='btn btn-success btn-xs'> <span class='glyphicon glyphicon-ok'></span> Book Now ($slots_left slots left)</a>";
        }

        $calendar .= "</td>";
        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($l = 0; $l < $remainingDays; $l++) {
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    echo $calendar;
}
?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="driverportalcss/calendarstyle.css">
    <link rel="icon" href="img/Brgy. Estefania Logo (Old).png" type="image/png">
    <title>Barangay Estefania Online Appointment for ID Registration</title>
</head>
<body>
    <style>
        ion-icon {
            font-size: 32px;
            transition: .1s;
        }

        ion-icon:active {
            transform: scale(.9);
        }
    </style>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" style="background:#2ecc71; border:none; color:#fff; text-align:center; display: flex; align-items: center; justify-content: center; flex-direction: column; position: relative;">
                    <a href="index.php" style="left: 10px; top: 10px; text-decoration: none; background: #fff; color: #2ecc71; padding: 5px 10px; border-radius: 5px; margin-bottom: 10px;">Back to Driver's Portal</a>
                    <div style="display: flex; align-items: center;">
                        <img src="img/Brgy. Estefania Logo (Old).png" alt="Barangay Estefania Logo" style="height: 100px; margin-right: 20px;">
                        <h1 style="margin: 0;">Online Appointment Booking for ID Registration</h1>
                    </div>
                    <br>
                    <p><strong>Magpili sang gusto nga petsa sa kalendaryo para magparehistro kag pindoton ang "Book Now".</strong><br><i>Makita kung pila na lang ang bilin nga pwede magparehistro sa amo na nga adlaw sa may "Slots Left".</i></p>
                </div>
            </div>

            <?php
                $dateComponents = getdate();
                if(isset($_GET['month']) && isset($_GET['year'])){
                    $month = $_GET['month'];
                    $year = $_GET['year'];
                } else{
                    $month = date('m');
                    $year = date('Y');
                }

                build_calendar((int)$month, $year);
            ?>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
