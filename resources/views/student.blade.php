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
<!-- <script src="{{ asset('js/student-noti.js') }}"></script> -->
<script>
      let studentLastActivityKey = null;
let lastStudentID=null;

function convertToAMPM(time) {
    let [hours, minutes] = time.split(':').map(Number);
    let period = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // handle midnight (0 => 12)
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2,'0')} ${period}`;
}

function fetchStudentAttendance(init = false) {
    fetch('/latest-student-attendance')
        .then(res => res.json())
        .then(data => {
            console.log(data)
            if (data && data.student.student_id) {
                const ID_key=`${data.student.student_id}_${data.student.created_at}`;
                const key_s = `${data.student.student_id}_${data.student.check_in}`;

                // First run → just set the key, skip notification
                if (init) {
                    studentLastActivityKey = key_s;
                   lastStudentID= ID_key;
                    return;
                }

if(lastStudentID==ID_key){
    var message=`Student ${data.student.student_info.name} checked in updated!`;
}else{
    var message=`Student ${data.student.student_info.name} checked in!`;

}

                if (key_s !== studentLastActivityKey) {
                    console.log(lastStudentID);
                     console.log(ID_key);
                    studentLastActivityKey = key_s;
                    const time =  data.student.check_in ;
// Student Alert
Swal.fire({
    icon: 'success',
  toast: true,
  position: 'top-end',
  timer: 7000,
  showConfirmButton: false,
  background: lastStudentID==ID_key ? '#ffd000ff' : '#5187acff',
  color: lastStudentID==ID_key ? '#000000ff' : '#ffffffff',
  html: `
    <div style="display: flex; align-items: center; gap: 4px;">
      <img src="/images/students/${data.student.student_id}.jpg"
           alt="Student Photo"
           style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
      <div style="text-align:left;">
        <h4 style="margin:0; font-size:16px; font-weight:bold;">${message}</h4>
        <p style="margin:2px 0 0; font-size:14px;">${data.student.student_id}</p>
        <small style="opacity:0.8;">Time: ${convertToAMPM(time)}</small>
      </div>
    </div>
  `
});

                   // updateStudentRow(data);
                }
                   lastStudentID= ID_key;

            }
        })
        .catch(err => console.error('Error fetching attendance:', err));
}

// ✅ First fetch: only initialize, no notification
fetchStudentAttendance(true);

// ✅ Polling: normal mode
setInterval(fetchStudentAttendance, 5000);


function updateStudentRow({ student }) {
    let rowId = `#row-${student.student_id}`;
    let $row = $(rowId);

    // If row exists → update
    if ($row.length) {
        table.row($row).data([
            `<a href="/students/${student.student_id}">${student.student_id}</a>`,
            `<a href="/students/${student.student_id}">${student.name}</a>`,
            `${student.teacher_name} - ${student.teacher_subject}`,
            student.check_in ? convertToAMPM(student.check_in) : '-',
            `<span class="${student.status}">${student.status}</span>`,
            student.date
        ]).draw(false);

        // Highlight animation
        $row.removeClass('highlight-present highlight-absent highlight-late');
        setTimeout(() => $row.addClass('highlight-' + student.status.toLowerCase()), 50);
    } 
    // Else → add new row
    else {
        let newRow = table.row.add([
            `<a href="/students/${student.student_id}">${student.student_id}</a>`,
            `<a href="/students/${student.student_id}">${student.name}</a>`,
            `${student.teacher_name} - ${student.teacher_subject}`,
            student.check_in ? convertToAMPM(student.check_in) : '-',
            `<span class="${student.status}">${student.status}</span>`,
            student.date
        ]).draw(false).node();

        $(newRow).attr('id', `row-${student.student_id}`);
        $(newRow).addClass('highlight-' + student.status.toLowerCase());
    }
}
</script>
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

        
table.dataTable tbody tr:hover {
    background-color: #e6f5ea;
}

.bg_blue{
background-color: #3aa6d1a6;
}

.bg_red{
background-color: #e6363691;

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
         .Absent { color:  #b00020; font-weight: 600; }
        .Present { color:  #28a745; font-weight: 600; }
        .Late { color:  #ff7300ff; font-weight: 600; }
        #studentsTable tbody tr {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

#studentsTable tbody tr:hover {
  transform: scale(1.02); /* pop out slightly */
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
  z-index: 5;
  position: relative; /* ensures shadow overlays */
}

.fade-in {
  animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; background-color: #fffae6; } /* light yellow flash */
  to   { opacity: 1; }
}

/* Glow animations */
.highlight-present { animation: glow-green 1s ease-out; }
.highlight-absent { animation: glow-red 1s ease-out; }
.highlight-late { animation: glow-orange 1s ease-out; }

@keyframes glow-green {
  0% { background-color: #d4f8d4; }
  50% { background-color: #b0f2b0; }
  100% { background-color: transparent; }
}
@keyframes glow-red {
  0% { background-color: #ffd6d6; }
  50% { background-color: #ffb3b3; }
  100% { background-color: transparent; }
}
@keyframes glow-orange {
  0% { background-color: #ffeacc; }
  50% { background-color: #ffd699; }
  100% { background-color: transparent; }
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
                <th>Check In</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
             @foreach ($students as $student)
    
                    <tr id="row-{{ $student->id }}">
                        <td>
                            <a href="{{ route('students.profile', $student->student_id) }}">
                                {{ $student->student_id }}
                            </a>
                        </td>
                        <td>
                           
                                {{ $student->student_info->name }}
                   
                        </td>
                        <td>
                            {{ $student->teacher_info->name }} - {{ $student->teacher_info->subject }}
                        </td>
                        <td>
                            {{ $student->status == 'Present'? \Carbon\Carbon::parse($student->check_in)->format('h:i A') : '-' }}
                        </td>
                        <td class="{{ $student->status }}">
                            {{ $student->status }}
                        </td>
                        <td data-order="{{ $student->date }}{{$student->check_in}}">
                            {{ $student->date }}
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
