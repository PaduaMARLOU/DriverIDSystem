<?php
date_default_timezone_set('Asia/Manila');

function build_calendar($month, $year) {
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

        if ($date < date('Y-m-d') || $dayname == 'saturday' || $dayname == 'sunday') {
            $calendar .= "<td class='not-available'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>N/A</button>";
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
    <title>Barangay Estefania Online Appointment for ID Registration</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" style="background:#2ecc71; border:none; color:#fff; text-align:center;">
                    <h1 style="margin: 0;">Barangay Estefania Online Appointment Booking for ID Registration</h1>
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
