<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance with Time</title>
    <!-- In your Blade layout or view -->
    <link rel="stylesheet" href="{{asset('css/table.css')}}">

<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/table.js') }}"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .btn-box{
            text-align: center;
        }
        #warm-text{
            color: red;
        }
        #teacher-btn,#student-btn{
            height: 25px;
            cursor: pointer;
        }
        
        .date-selector {
            margin: 20px 0;
            text-align: center;
        }
        #bg-img{
            width: 500px;
            height: 500px;
            margin: auto;
        }
        img{
            width: 100%;
            height: 100%;;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color:white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .present {
            color: green;
        }
        .absent {
            color: red;
        }
        .late {
            color: orange;
        }
        .date-selector{
            visibility: hidden;
        }

        .flex{
            display: flex;
  flex-wrap: nowrap;
        }
    </style>
</head>
<body>
    <h1>Class Attendance (Subject)</h1>
    <div class="btn-box">
        <p id="warm-text">Please select one of the options below.</p>
        <button id="teacher-btn"
                onclick="teacher_table()">Teacher</button>
        <button id="student-btn"
                onclick="student_table()">Student</button>
    </div>
    <div class="date-selector" >
        <label for="attendance-date">Select Date: </label>
        <input type="date" id="attendance-date">
        <button onclick="showAttendance()">Show Attendance</button>
    </div>
    <div id="bg-img">
        <img src="{{ asset('images/calendar.jpg') }}"  alt="calendar">
    </div>
    <table id="attendance-table">
        
    </table>

    <script>
        document.getElementById('attendance-date').valueAsDate = new Date();
        const tech_btn = document.getElementById('teacher-btn');
        const stu_btn = document.getElementById('student-btn');
        const table = document.getElementById('attendance-table');
        const image = document.getElementById('bg-img');
        const warm_text = document.getElementById('warm-text');
        function teacher_table(){
            image.remove();
            warm_text.remove();
            tech_btn.style.color = "white";
            tech_btn.style.backgroundColor = "black";
            stu_btn.style.color = "black";
            stu_btn.style.backgroundColor = "white";
            tech_btn.style.border = "1px solid gray";
            tech_btn.style.borderRadius = "2px";
            stu_btn.style.border = "1px solid gray";
            stu_btn.style.borderRadius = "2px";
            table.innerHTML = `

 <div class="filters flex">
        <div class="toolbar">
            <label for="dateFilter">Date:</label>
            <input type="date" id="dateFilter" />
        </div>

        <div class="toolbar">
            <label for="nameFilter">Name:</label>
            <select id="nameFilter">
                <option value="">All</option>
                <!-- filled dynamically -->
            </select>
        </div>

        <div class="toolbar">
            <label for="subjectFilter">Subject:</label>
            <select id="subjectFilter">
                <option value="">All</option>
                <!-- filled dynamically -->
            </select>
        </div>

        <div class="toolbar">
            <label for="dayFilter">Day:</label>
            <select id="dayFilter">
                <option value="">All</option>
                <!-- filled dynamically -->
            </select>
        </div>

        <button id="clearFilters" type="button">Clear Filters</button>
    </div>

    <table id="teachersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>Name</th>
                <th>Subject</th>
                <th>Day</th>
                <th>Time In</th>
                <th>Status</th>
                <th>Created At</th> {{-- format Y-m-d for easy filtering --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->teacher_id }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->subject }}</td>
                    <td>{{ $teacher->day }}</td>
                    <td>
                        @if ($teacher->time_in)
                            {{ \Carbon\Carbon::parse($teacher->time_in)->format('h:i A') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="{{ $teacher->time_in ? 'present' : 'absent' }}">
                        {{ $teacher->time_in ? 'Present' : 'Absent' }}
                    </td>
                    <td data-order="{{ $teacher->created_at->format('Y-m-d') }}">
                        {{ $teacher->created_at->format('Y-m-d') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>


            `;
        }
        function student_table(){
            image.remove();
            warm_text.remove();
            stu_btn.style.color = "white";
            stu_btn.style.backgroundColor = "black";
            tech_btn.style.color = "black";
            tech_btn.style.backgroundColor = "white";
            stu_btn.style.border = "1px solid gray";
            stu_btn.style.borderRadius = "2px";
            tech_btn.style.border = "1px solid gray";
            tech_btn.style.borderRadius = "2px";
            table.innerHTML = `
            <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Time In</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>S001</td>
                <td>John Doe</td>
                <td>08:05 AM</td>
                <td class="present">Present</td>
            </tr>
            <tr>
                <td>S002</td>
                <td>Jane Smith</td>
                <td>08:15 AM</td>
                <td class="present">Present</td>
            </tr>
            <tr>
                <td>S003</td>
                <td>Mike Johnson</td>
                <td>-</td>
                <td class="absent">Absent</td>
            </tr>
            <tr>
                <td>S004</td>
                <td>Sarah Williams</td>
                <td>-</td>
                <td class="absent">Absent</td>
            </tr>
            <tr>
                <td>S005</td>
                <td>Alex Brown</td>
                <td>08:10 AM</td>
                <td class="present">Present</td>
            </tr>
        </tbody>
            `;
        }
        function showAttendance() {
            const date = document.getElementById('attendance-date').value;
            alert("Showing attendance records for: " + formatDate(date));
        }
        
        function formatDate(dateString) {
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }
    </script>
    <script>
$(function () {
        // Initialize DataTable
        var table = $('#teachersTable').DataTable({
            order: [[6, 'desc']], // sort by Created At desc by default
            pageLength: 10
        });

        // Helper: build a select from unique column values
        function buildSelect(columnIndex, $select) {
            $select.empty().append('<option value="">All</option>');

            // get unique values, strip HTML if any
            var data = table
                .column(columnIndex)
                .data()
                .unique()
                .sort();

            data.each(function (d) {
                // strip HTML tags just in case
                var text = $('<div>').html(d).text().trim();
                if (text) {
                    $select.append('<option value="' + text + '">' + text + '</option>');
                }
            });

            // exact-match filter on change
            $select.on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table.column(columnIndex).search(val ? '^' + val + '$' : '', true, false).draw();
            });
        }

        // Build dropdowns for Name (1), Subject (2), Day (3)
        buildSelect(1, $('#nameFilter'));
        buildSelect(2, $('#subjectFilter'));
        buildSelect(3, $('#dayFilter'));

        // Date filter (column 6 = Created At in YYYY-MM-DD)
        $('#dateFilter').on('change', function () {
            var selectedDate = $(this).val(); // "YYYY-MM-DD"
            // exact match on that date
            table.column(6).search(selectedDate ? '^' + selectedDate + '$' : '', true, false).draw();
        });

        // Clear all filters
        $('#clearFilters').on('click', function () {
            $('#dateFilter').val('');
            $('#nameFilter').val('');
            $('#subjectFilter').val('');
            $('#dayFilter').val('');
            table.columns().search('').draw();
        });
    });
</script>
</body>
</html>