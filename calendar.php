<?php
date_default_timezone_set('Asia/Manila');

function build_calendar($month, $year) {
    // Define holidays
    $holidays = array(
        '2024-01-01' => 'New Year\'s Day',
        '2024-02-25' => 'EDSA People Power Revolution Anniversary',
        '2024-04-09' => 'Araw ng Kagitingan',
        '2024-04-18' => 'Maundy Thursday',
        '2024-04-19' => 'Good Friday',
        '2024-05-01' => 'Labor Day',
        '2024-06-12' => 'Independence Day',
        '2024-08-21' => 'Ninoy Aquino Day',
        '2024-08-26' => 'National Heroes Day',
        '2024-11-01' => 'All Saints\' Day',
        '2024-11-02' => 'All Souls\' Day',
        '2024-11-30' => 'Bonifacio Day',
        '2024-12-08' => 'Feast of the Immaculate Conception',
        '2024-12-25' => 'Christmas Day',
        '2024-12-30' => 'Rizal Day'
        // Add other holidays as needed
    );

    // Database connection
    $mysqli = new mysqli('localhost', 'root', '', 'driver_id_system');
    // SQL query to count bookings for each day
    $stmt = $mysqli->prepare("SELECT DATE, COUNT(*) AS bookings_count FROM tbl_appointment WHERE MONTH(DATE) = ? AND YEAR(DATE) = ? GROUP BY DATE");
    $stmt->bind_param('ss', $month, $year);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Store date and bookings count
                $bookings[$row['DATE']] = $row['bookings_count'];
            }
        }
        $stmt->close();
    }
    
    // Calendar generation code...
    $daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
    $firstDayOfMonth = mktime(0,0,0,(int)$month,1,$year);
    $numberDays = date('t',$firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    $datetoday = date('Y-m-d');
    $currentTime = date('H:i');

    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";
    $calendar.= "<a class='btn btn-xs btn-success' href='?month=".date('m', mktime(0, 0, 0, (int)$month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, (int)$month-1, 1, $year))."'>Previous Month</a> ";
    $calendar.= " <a class='btn btn-xs btn-danger' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a> ";
    $calendar.= "<a class='btn btn-xs btn-primary' href='?month=".date('m', mktime(0, 0, 0, (int)$month+1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, (int)$month+1, 1, $year))."'>Next Month</a></center><br>";
    
    $calendar .= "<tr>";
    foreach($daysOfWeek as $day) {
        if ($day == 'Saturday' || $day == 'Sunday') {
            $calendar .= "<th class='header not-available'>$day</th>";
        } else {
            $calendar .= "<th class='header'>$day</th>";
        }
    } 

    $currentDay = 1;
    $calendar .= "</tr><tr>";

    if ($dayOfWeek > 0) { 
        for($k=0;$k<$dayOfWeek;$k++){
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
        $eventNum = 0;
        $today = $date == date('Y-m-d') ? "today" : "";

        if ($date < date('Y-m-d') || $dayname == 'saturday' || $dayname == 'sunday' || isset($holidays[$date]) || ($date == date('Y-m-d') && $currentTime >= '15:00')) {
            $holidayText = isset($holidays[$date]) ? "<br>".$holidays[$date] : "";
            $calendar .= "<td class='not-available'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>N/A</button>$holidayText";
        } elseif (isset($bookings[$date]) && $bookings[$date] >= 30) {
            $calendar .= "<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>Full</button>";
        } else {
            $slots_left = 30 - (isset($bookings[$date]) ? $bookings[$date] : 0);
            $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='book.php?date=".$date."' class='btn btn-success btn-xs'> <span class='glyphicon glyphicon-ok'></span> Book Now ($slots_left slots left)</a>";
        }
            
        $calendar .="</td>";
        $currentDay++;
        $dayOfWeek++;
    }

    if ($dayOfWeek != 7) { 
        $remainingDays = 7 - $dayOfWeek;
        for($l=0;$l<$remainingDays;$l++){
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
    <link rel="icon" href="img/Brgy Estefania Logo.png" type="image/png">
    <title>Barangay Estefania Online Appointment for ID Registration</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" style="background:#2ecc71; border:none; color:#fff; text-align:center; display: flex; align-items: center; justify-content: center; flex-direction: column; position: relative;">
                    <a href="index.php" style="left: 10px; top: 10px; text-decoration: none; background: #fff; color: #2ecc71; padding: 5px 10px; border-radius: 5px; margin-bottom: 10px;">Back to Driver's Portal</a>
                    <div style="display: flex; align-items: center;">
                        <img src="img/Brgy Estefania Logo.png" alt="Barangay Estefania Logo" style="height: 75px; margin-right: 10px;">
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
                    }else{
                        $month = date('m');
                        $year = date('Y');
                    }

                    build_calendar((int)$month, $year);
                ?>
            </div>
        </div>
    </div>
</body>
</html>
