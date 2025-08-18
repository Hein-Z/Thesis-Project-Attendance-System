{{-- resources/views/students/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Student Attendance</title>

    <!-- DataTables + jQuery -->
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
    <script src="{{ asset('js/jquery.js') }}"></script>

    <script src="{{ asset('js/table.js') }}"></script>
<!-- Load SweetAlert2 -->
<script src="{{ asset('js/sweetalert.js') }}"></script>

<!-- Load your notification script -->
<script src="{{ asset('js/noti.js') }}"></script>

    <!-- <script src="{{ asset('js/echo.js') }}"></script> -->

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f5fdf6;
            color: #222;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #1e7e34;
            margin-bottom: 20px;
        }

        /* Filter bar styling */
        .filters {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            background-color: #e6f5ea;
            border: 1px solid #c8e6cc;
            border-radius: 10px;
            padding: 12px;
            box-shadow: 0 2px 5px rgba(0, 100, 0, 0.1);
            margin-bottom: 20px;
        }
        .toolbar {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .toolbar label {
            font-weight: 600;
            color: #1e7e34;
        }
        .filters input,
        .filters select,
        .filters button {
            padding: 5px 8px;
            border-radius: 5px;
            border: 1px solid #c8e6cc;
            outline: none;
        }
        .filters input:focus,
        .filters select:focus {
            border-color: #28a745;
            box-shadow: 0 0 3px #28a74580;
        }
        #clearFilters {
            background-color: #28a745;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.2s ease;
        }
        #clearFilters:hover {
            background-color: #218838;
        }

        /* Table styling */
        table.dataTable {
            border-collapse: collapse !important;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0, 100, 0, 0.15);
        }
        table.dataTable thead {
            background-color: #28a745;
            color: white;
        }
        table.dataTable thead th {
            padding: 10px;
            text-align: center;
        }
        table.dataTable tbody td {
            padding: 8px 10px;
            text-align: center;
        }
        table.dataTable tbody tr:nth-child(even) {
            background-color: #f0fff4;
        }

        /* Status colors */
        .In {
            color: #1e7e34;
            font-weight: 600;
        }

        .Out{
            color: blue;
            font-weight: 600;
        }
        .Absent {
            color: #b00020;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <h2>Students' Attendance History</h2>

    <!-- Filter Bar -->
    <div class="filters">
        <div class="toolbar">
            <label for="dateFilter">Date:</label>
            <input type="date" id="dateFilter" />
        </div>

        <div class="toolbar">
            <label for="nameFilter">Name:</label>
            <select id="nameFilter"></select>
        </div>

        <div class="toolbar">
            <label for="classFilter">Class:</label>
            <select id="classFilter"></select>
        </div>
    
        <div class="toolbar">
            <label for="statusFilter">Status:</label>
            <select id="statusFilter"></select>
        </div>

        <button id="clearFilters" type="button">Clear Filters</button>
    </div>

    <table id="studentsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Name</th>
                <th>Class</th>
                <th>Time In</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td><a href="{{ route('students.profile', $student->student_id) }}">{{ $student->student_id }}</a></td>
                    <td><a href="{{ route('students.profile', $student->student_id) }}">{{ $student->student_info->name }}</a></td>
                    
                    <td>{{ $student->teacher_info->name }}-{{ $student->teacher_info->subject }}</td>
                    
                    <td>{{ \Carbon\Carbon::parse($student->check_in)->format('h:i A')?? '-' }}</td>
                    
                    
                    <td class="{{ $student->status }}">
                        {{ $student->status }}
                    </td>
                    <td data-order="{{ $student->date }}">
                        {{ $student->date  }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
    $(function () {
        var table = $('#studentsTable').DataTable({
            order: [[5, 'desc']],
            pageLength: 10
        });
     $('#dateFilter').on('change', function () {
            var selectedDate = $(this).val();
            table.column(5).search(selectedDate ? '^' + selectedDate + '$' : '', true, false).draw();
        });
       
        function buildSelect(columnIndex, $select) {
            $select.empty().append('<option value="">All</option>');
            var data = table.column(columnIndex).data().unique().sort();
            data.each(function (d) {
                var text = $('<div>').html(d).text().trim();
                if (text) $select.append('<option value="' + text + '">' + text + '</option>');
            });
            $select.on('change', function () {
                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                table.column(columnIndex).search(val ? '^' + val + '$' : '', true, false).draw();
            });
        }

        buildSelect(1, $('#nameFilter'));
        buildSelect(2, $('#classFilter'));
      
        buildSelect(4, $('#statusFilter'));
    

       

        $('#clearFilters').on('click', function () {
            $('#dateFilter,  #statusFilter, #nameFilter, #classFilter').val('');
            table.columns().search('').draw();
        });

     
    });
    </script>

</body>
</html>
