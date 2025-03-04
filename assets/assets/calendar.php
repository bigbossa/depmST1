<?php
$conn = new mysqli("localhost", "root", "", "dormitory_management");

function getEvents($month, $year)
{
    global $conn;
    $sql = "SELECT event_date, title FROM events WHERE MONTH(event_date) = $month AND YEAR(event_date) = $year";
    $result = $conn->query($sql);
    $events = [];

    while ($row = $result->fetch_assoc()) {
        $events[$row['event_date']][] = $row['title'];
    }
    return $events;
}

$month = date("n");
$year = date("Y");
$events = getEvents($month, $year);

function generateCalendar($month, $year, $events)
{
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date("t", $firstDay);
    $dayOfWeek = date("w", $firstDay);
    $today = date("Y-m-d");

    $monthNames = [
        "",
        "มกราคม",
        "กุมภาพันธ์",
        "มีนาคม",
        "เมษายน",
        "พฤษภาคม",
        "มิถุนายน",
        "กรกฎาคม",
        "สิงหาคม",
        "กันยายน",
        "ตุลาคม",
        "พฤศจิกายน",
        "ธันวาคม"
    ];

    echo "<div class='calendar-container'>";

    // Add navigation buttons and month/year display
    echo "<div class='calendar-nav'>";
    echo "<button onclick='changeMonth(-1)' class='nav-btn'>&lt; เดือนก่อนหน้า</button>";
    echo "<h2 class='calendar-title'>{$monthNames[$month]} $year</h2>";
    echo "<button onclick='changeMonth(1)' class='nav-btn'>เดือนถัดไป &gt;</button>";
    echo "</div>";

    echo "<table class='calendar'>";
    echo "<tr class='calendar-header'>";
    echo "<th>อา</th><th>จ</th><th>อ</th><th>พ</th><th>พฤ</th><th>ศ</th><th>ส</th>";
    echo "</tr><tr>";

    if ($dayOfWeek > 0) {
        echo str_repeat("<td class='empty-day'></td>", $dayOfWeek);
    }

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $date = "$year-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-" . str_pad($day, 2, "0", STR_PAD_LEFT);
        $class = ($date == $today) ? "today" : "calendar-day";
        echo "<td class='$class'>$day";

        if (isset($events[$date])) {
            echo "<div class='event-container'>";
            foreach ($events[$date] as $event) {
                echo "<div class='event-title' title='$event'>" .
                    mb_strimwidth($event, 0, 15, '...', 'UTF-8') .
                    "</div>";
            }
            echo "<div class='event-popup'>";
            foreach ($events[$date] as $event) {
                echo "<div class='event'>$event</div>";
            }
            echo "</div>";
            echo "</div>";
        }

        echo "</td>";
        if (($day + $dayOfWeek) % 7 == 0) {
            echo "</tr><tr>";
        }
    }

    echo "</tr></table>";
    echo "</div>";

    // Add CSS styles
    echo "
    <style>
    .calendar-container {
        max-width: 100%;
        margin: 20px auto;
        overflow-x: auto; /* Allow horizontal scrolling on small screens */
    }
    
    .calendar-title {
        text-align: center;
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
    }
    
    .calendar {
        width: 100%;
        min-width: 768px; /* Ensure minimum width for readability */
        border-collapse: collapse;
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .calendar th, .calendar td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
        vertical-align: top;
        width: 14.28%;
        min-width: 100px; /* Ensure minimum width for content */
    }
    
    .calendar-header th {
        background: #4a90e2;
        color: white;
        font-weight: 500;
        padding: 10px;
    }
    
    .calendar-day {
        position: relative;
        height: 80px;
        transition: background-color 0.3s;
    }
    
    .calendar-day:hover {
        background-color: #f5f5f5;
    }
    
    .empty-day {
        background-color: #f9f9f9;
    }
    
    .today {
        background-color: #e8f4ff;
        font-weight: bold;
        color: #4a90e2;
        height: 80px;
    }
    
    .calendar td {
        position: relative;
        cursor: pointer;
    }
    
    .event-container {
        margin-top: 5px;
        position: relative;
    }
    
    .event-title {
        font-size: 12px;
        color: #e74c3c;
        margin: 2px 0;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    
    .event-popup {
        display: none;
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: white;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        min-width: 250px;
        max-width: 300px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        z-index: 1000;
    }
    
    .event {
        font-size: 14px;
        color: #333;
        text-align: left;
        margin: 5px 0;
        padding: 5px;
        word-wrap: break-word;
    }
    
    .calendar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .nav-btn {
        padding: 8px 16px;
        background-color: #4a90e2;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .nav-btn:hover {
        background-color: #357abd;
    }
    
    /* Add media queries for responsive design */
    @media screen and (max-width: 768px) {
        .calendar-title {
            font-size: 20px;
        }
        
        .nav-btn {
            padding: 6px 12px;
            font-size: 14px;
        }
        
        .event-title {
            font-size: 11px;
        }
        
        .event {
            font-size: 13px;
        }
        
        .event-popup {
            min-width: 200px;
            max-width: 250px;
        }
    }
    </style>
    
    <script>
    function changeMonth(offset) {
        const urlParams = new URLSearchParams(window.location.search);
        let currentMonth = " . $month . ";
        let currentYear = " . $year . ";
        
        currentMonth += offset;
        
        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        } else if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }
        
        window.location.href = '?month=' + currentMonth + '&year=' + currentYear;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const eventContainers = document.querySelectorAll('.event-container');
        
        eventContainers.forEach(container => {
            container.addEventListener('click', function(e) {
                e.stopPropagation();
                const popup = this.querySelector('.event-popup');
                
                // ปิด popup อื่นๆ ก่อน
                document.querySelectorAll('.event-popup').forEach(p => {
                    if (p !== popup) {
                        p.style.display = 'none';
                    }
                });
                
                // สลับการแสดง popup
                popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
            });
        });
        
        // ปิด popup เมื่อคลิกที่อื่น
        document.addEventListener('click', function() {
            document.querySelectorAll('.event-popup').forEach(popup => {
                popup.style.display = 'none';
            });
        });
    });
    </script>";
}

// Update month and year from URL parameters
$month = isset($_GET['month']) ? (int)$_GET['month'] : date("n");
$year = isset($_GET['year']) ? (int)$_GET['year'] : date("Y");

// Validate month and year
if ($month < 1 || $month > 12) $month = date("n");
if ($year < 1970 || $year > 2100) $year = date("Y");

$events = getEvents($month, $year);
generateCalendar($month, $year, $events);
$conn->close();