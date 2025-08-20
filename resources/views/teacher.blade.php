{{-- resources/views/teachers/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Teacher Attendance</title>

    <!-- DataTables + jQuery -->
    <link rel="stylesheet" href="{{asset('css/table.css')}}">
   
    <script src="{{ asset('js/jquery.js') }}"></script>

    <script src="{{ asset('js/table.js') }}"></script>
    <script src="{{ asset('js/student-noti.js') }}"></script>

<!-- Load your notification script -->
<!-- <script src="{{ asset('js/noti.js') }}"></script> -->
    <style>
        /* Add this to your CSS */
.fade-in {
  animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; background-color: #fffae6; } /* light yellow flash */
  to   { opacity: 1; }
}

#teachersTable tbody tr {
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

#teachersTable tbody tr:hover {
  transform: scale(1.02); /* pop out slightly */
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
  z-index: 5;
  position: relative; /* ensures shadow overlays */
}
    </style>
      <!-- <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script> -->
<!-- Load SweetAlert2 -->
<script src="{{ asset('js/sweetalert.js') }}"></script>
<!-- <script src="{{ asset('js/student-noti.js') }}"></script> -->

<!-- Load your notification script -->
<script>
  let lastActivityKey = null;
 function convertToAMPM(time) {
    let [hours, minutes] = time.split(':').map(Number);
    let period = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // handle midnight (0 => 12)
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2,'0')} ${period}`;
}

function fetchAttendance(init = false) {
    fetch('/latest-teacher-attendance')
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data && data.teacher_id) {
                const key = `${data.teacher_id}_${data.check_in}_${data.check_out}`;

                // First run → just set the key, skip notification
                if (init) {
                    lastActivityKey = key;
                    return;
                }

                if (key !== lastActivityKey) {
                    lastActivityKey = key;

                    const isCheckIn = data.check_out === null;
                    const time = isCheckIn ? data.check_in : data.check_out ;

                 // Teacher Alert
Swal.fire({
    icon: 'success',
  toast: true,
  position: 'top-end',
  timer: 7000,
  showConfirmButton: false,
  background: isCheckIn ? '#2ecc70ff' : '#7a6506ff',
  color: '#fff',
  html: `
    <div style="display: flex; align-items: center; gap: 5px;">
      <img src="/images/teachers/${data.teacher_id}.jpg"
           alt="Teacher Photo"
           style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid #fff;">
      <div style="text-align:left;">
        <h4 style="margin:0; font-size:16px; font-weight:bold;">
          ${isCheckIn ? '✅ Checked In' : '🚪 Checked Out'}
        </h4>
        <p style="margin:2px 0 0; font-size:14px;">${data.name}</p>
        <small style="opacity:0.8;">Time: ${convertToAMPM(time)}</small>
      </div>
    </div>
  `
});
                    updateTeacherRow(data);
                }
            }
        })
        .catch(err => console.error('Error fetching attendance:', err));
}

// ✅ First fetch: only initialize, no notification
fetchAttendance(true);

// ✅ Polling: normal mode
setInterval(fetchAttendance, 5000);
function formatDay(dateStr) {
    if (!dateStr) return "-";
    let d = new Date(dateStr);
    return d.toLocaleDateString("en-US", { weekday: 'long' });
}

function updateTeacherRow(data) {
    let tbody = document.querySelector("#teachersTable tbody");

    
    let rowStyle = "";
    if (data.checkout_type === "auto") {
        rowStyle = "background-color: #e6363649; color: black;";
    } else if (data.checkout_type === "In Class") {
        rowStyle = "background-color: #1669e67a; color: black;";
    } else if (data.checkout_type === "changed by admin") {
        rowStyle = "background-color: #2dc6da57; color: black;";
    }

    // Build row HTML
    let newRowHtml = `
        <td><a href="/teachers/${data.teacher_id}/profile">${data.teacher_id}</a></td>
        <td><a href="/teachers/${data.teacher_id}/profile">${data.name || "-"}</a></td>
        <td>${data.subject || "-"}</td>
       <td>${formatDay(data.created_at)}</td>
        <td>${data.check_in?convertToAMPM(data.check_in) : "-"}</td>
        <td>${data.check_out?convertToAMPM(data.check_out) : "-"}</td>
        <td>${data.checkout_type}</td>
        <td>${new Date().toISOString().split("T")[0]}</td>
        <td class="actions-cell">
            <div class="actions">
                <a href="/teachers/${data.id}/edit" class="edit-btn" title="Edit">✏️</a>
                <button type="button" class="delete-btn" data-id="${data.id}" title="Delete">🗑️</button>
            </div>
        </td>
    `;

    // Get the first row
    let firstRow = tbody.rows[0];

    if (firstRow && firstRow.cells[0].innerText.trim() == data.teacher_id) {
        // Update existing row
        firstRow.innerHTML = newRowHtml;
        firstRow.setAttribute("style", rowStyle);

        // Trigger fade animation
        firstRow.classList.remove("fade-in");
        void firstRow.offsetWidth; // reflow hack to restart animation
        firstRow.classList.add("fade-in");
    } else {
        // Insert new row at top
        let newRow = tbody.insertRow(0);
        newRow.innerHTML = newRowHtml;
        newRow.setAttribute("style", rowStyle);

        // Trigger fade animation
        newRow.classList.add("fade-in");
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

        .actions-cell {
    position: relative;
    width: 100px;
}

.actions {
    opacity: 0;
    display: flex;
    gap: 10px;
    justify-content: center;
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: translateY(-10px);
}

tr:hover .actions {
    opacity: 1;
    transform: translateY(0);
}

.edit-btn, .delete-btn {
    padding: 5px 8px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s, color 0.3s, transform 0.2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.edit-btn {
    background-color: #1e7e34;
    color: white;
}

.edit-btn:hover {
    background-color: #145c28;
    transform: scale(1.1);
}

.delete-btn {
    background-color: #b00020;
    color: white;
}

.delete-btn:hover {
    background-color: #7a0015;
    transform: scale(1.1);
}
table.dataTable tbody tr {
    transition: background 0.3s ease;
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

.bold {
  font-weight: bold;
}

    </style>
</head>
<body>

    <h2>Teachers' Attendance History</h2>

    <!-- Filter Bar -->
    <div class="filters">
        <div class="toolbar">
        <label for="subjectFilter"><b>Subject:</b></label>
    <select id="subjectFilter" class="form-control" style="width: 250px; display: inline-block;">
        <option value="">All</option>
        @foreach($teachers->unique(fn($t) => $t->teacher_info->name . '-' . $t->teacher_info->subject) as $teacher)
        <option value="{{ $teacher->teacher_info?->name }} - {{ $teacher->teacher_info?->subject }}">
    {{ $teacher->teacher_info?->name }} - {{ $teacher->teacher_info?->subject }}
</option>
        @endforeach

    </select>
    
        </div>
    <!-- Replace your current dateFilter input with a date range filter -->
<div class="toolbar">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date">
</div>
<div class="toolbar">
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date">
</div>

        <div class="toolbar">
            <label for="checkoutTypeFilter">Check Out Type:</label>
            <select id="checkoutTypeFilter"></select>
        </div>


        <button id="clearFilters" type="button">Clear Filters</button>
      <a  href="{{ url()->previous() }}"class="btn btn-secondary">Back</a>

    </div>

    <table id="teachersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Teacher ID</th>
                <th>Name</th>
                <th>Subject</th>
                <th>Day</th>
                <th>Check In Time</th>
                <th>Check Out Time</th>
                <th>Check Out Status</th>
                <th>Date</th>
                <th>Options</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr 
                     @if($teacher->checkout_type == 'In Class') 
                     style="background-color: #1669e67a; color: black;"
    @endif 
                >
                    <td><a href="{{ route('teachers.profile', $teacher->teacher_id) }}">{{ $teacher->teacher_id }}</a></td>
                    <td><a href="{{ route('teachers.profile', $teacher->teacher_id) }}">{{ $teacher->teacher_info?->name }}</a></td>
                    <td>{{ $teacher->teacher_info?->subject }}</td>
                    <td>{{  \Carbon\Carbon::parse($teacher->check_in)->format('l')  }}</td>
                    <td>{{  \Carbon\Carbon::parse($teacher->check_in)->format('H:i A')  }}</td>
                    <td>
                        @if ($teacher->check_out)
                            {{ \Carbon\Carbon::parse($teacher->check_out)->format('H:i A') }}
                        @else
                            -
                        @endif
                    </td>
                    <td 
                        @if($teacher->checkout_type == 'auto') 
        style="color: #c01e1eff;"  class="bold"
         @elseif($teacher->checkout_type == 'manual') 
        style="color: #087e8dff;"  class="bold"
        @endif
                    >
                        {{ $teacher->checkout_type }}
                    </td>
                    <td data-order="{{ \Carbon\Carbon::parse($teacher->check_in)->format('Y-m-d') }}">
                        {{ \Carbon\Carbon::parse($teacher->check_in)->format('Y-m-d')}}
                    </td>
                    <td class="actions-cell">
    <div class="actions">
        <a href="{{ route('teachers.edit', $teacher->id) }}" data-id="{{ $teacher->id }}" 
        class="edit-btn" title="Edit">
            ✏️
        </a>
      
            <button type="submit" data-id="{{ $teacher->id }}" 
            class="delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this attendance?')">🗑️</button>
       
    </div>
</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>

        
    $(function () {

     

       var table = $('#teachersTable').DataTable({
        order: [[7, 'desc']],
        pageLength: 10
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

    buildSelect(3, $('#dayPicker'));
    buildSelect(5, $('#statusFilter'));
    buildSelect(6, $('#checkoutTypeFilter'));

    // Custom date range filter
    $.fn.dataTable.ext.search.push(
        function(settings, data) {
            var start = $('#start_date').val();
            var end = $('#end_date').val();
            var date = data[7]; // Date column

            if(start && date < start) return false;
            if(end && date > end) return false;
            return true;
        }
    );

    $('#start_date, #end_date').on('change', function() {
        table.draw();
    });

    // Keep your other filters
    $('#subjectFilter').on('change', function () {
        var searchValue = $(this).val();
        if (searchValue) {
            table.columns(1).search(searchValue.split(' - ')[0]); // Name
            table.columns(2).search(searchValue.split(' - ')[1]); // Subject
        } else {
            table.columns(1).search('');
            table.columns(2).search('');
        }
        table.draw();
    });

    $('#clearFilters').on('click', function () {
        $('#start_date, #end_date, #subjectFilter, #statusFilter').val('');
        table.columns().search('').draw();
    });


    $('.delete-btn').click(function() {
    var teacherId = $(this).data('id');
    var row = $(this).closest('tr');

    var password = prompt("Enter password to delete:");
    if(password !== '12345678') {
        alert("Incorrect password!");
        return;
    }

    if(confirm("Are you sure you want to delete this attendance?")) {
        $.ajax({
            url: '/teachers/' + teacherId,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                // Remove main row
                row.fadeOut(500, function() { $(this).remove(); });

             
            },
            error: function(xhr) {
                alert("Error deleting attendance.");
            }
        });
    }
});

    // EDIT (optional: open modal or prompt)
    $('.edit-btn').click(function(e) {
        e.preventDefault(); // Prevent default link

        var teacherId = $(this).data('id');
        var password = prompt("Enter password to edit:");
        if(password !== '12345678') {
            alert("Incorrect password!");
            return;
        }

        // Redirect to edit page
        window.location.href = '/teachers/' + teacherId + '/edit';
    });

    });
    </script>

</body>
</html>
