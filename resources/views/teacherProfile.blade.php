{{-- resources/views/teachers/profile.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Profile</title>
    <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}"> -->
        <link rel="stylesheet" href="{{asset('css/table.css')}}">

    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/table.js') }}"></script>
    <style>
        .manual { background-color: #ffffffff !important; }
        .auto { background-color: #e6363650 !important; }
        .changed { background-color: #2ff0a657 !important; }
.in_class{background-color: #3945f044 !important;}
        h2 { text-align: center; color: #1e7e34; margin-bottom: 20px; }

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
        .toolbar { display: flex; align-items: center; gap: 6px; }
        .toolbar label { font-weight: 600; color: #1e7e34; }
        .filters input, .filters select, .filters button {
            padding: 5px 8px;
            border-radius: 5px;
            border: 1px solid #c8e6cc;
            outline: none;
        }
        .filters input:focus, .filters select:focus {
            border-color: #28a745;
            box-shadow: 0 0 3px #28a74580;
        }
        #clearFilter {
            background-color: #28a745;
            color: white;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: background 0.2s ease;
        }
        #clearFilter:hover { background-color: #218838; }

        /* Table styling */
        table.dataTable {
            border-collapse: collapse !important;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0, 100, 0, 0.15);
        }
        table.dataTable thead { background-color: #28a745; color: white; }
        table.dataTable thead th { padding: 10px; text-align: center; }
        table.dataTable tbody td { padding: 8px 10px; text-align: center; }
        table.dataTable tbody tr:nth-child(even) { background-color: #f0fff4; }

        
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

.hidden {
  display: none;
}
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Teacher Attendance Profile: {{ $teacher->name }}</h2>
@php
    $totalMinutes = $attendances->sum(function($t){
        if($t->check_in && $t->check_out){
            return \Carbon\Carbon::parse($t->check_in)
                   ->diffInMinutes(\Carbon\Carbon::parse($t->check_out));
        }
        return 0;
    });
    $totalHours = floor($totalMinutes / 60);
    $totalMins  = $totalMinutes % 60;
@endphp

<div style="text-align:center; margin-bottom:15px;">
    <strong>Total Class Time: {{ $totalHours }}h {{ $totalMins }}m</strong>
</div>
    <!-- Filters -->
    <div class="row mb-3 filters">
    <div class="col-md-3 toolbar">
        <label>Start Date</label>
        <input type="date" id="start_date" class="form-control">
    </div>
    <div class="col-md-3 toolbar">
        <label>End Date</label>
        <input type="date" id="end_date" class="form-control">
    </div>
    <div class="col-md-3 toolbar" id="filterRow">
        <!-- DataTables will inject checkout_type select here -->
    </div>
    <div class="col-md-3 toolbar d-flex align-items-end">
        <button id="clearFilter" class="btn btn-secondary">Clear Filter</button>
    </div>
</div>


    <table id="attendanceTable" class="display table table-striped">
        <thead>
            <tr>
                <th class='hidden'>Time</th>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
                <th>Duration</th>
                <th>Checkout Type</th>
               <th>Option</th>

            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $att)
            <tr 
               @if($att->checkout_type == 'auto') 
        style="background-color: #e6363649; color: black;" 
    @elseif($att->checkout_type == 'In Class') 
        style="background-color: #1669e67a; color: black;" 
         @elseif($att->checkout_type == 'changed by admin') 
        style="background-color: #2dc6da57; color: black;" 
    @endif 
            >
                <td class="hidden">{{ $att->check_in }}</td>

                <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('y/m/d l' ) : '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($att->check_in)->format('h:i a') ?? '-' }}</td>
                               <td>{{$att->check_out ? \Carbon\Carbon::parse($att->check_out)->format('h:i a') : '-' }}</td>
@php
    $durationText = '-';
    if($att->check_in && $att->check_out){
        $minutes = \Carbon\Carbon::parse($att->check_in)
                  ->diffInMinutes(\Carbon\Carbon::parse($att->check_out));
        $hours = floor($minutes / 60);
        $mins  = $minutes % 60;
        $durationText = ($hours > 0 ? $hours.'h ' : '') . $mins.'m';
    }
@endphp
<td>{{ $durationText }}</td>
                <td>{{ $att->checkout_type ?? '-' }}</td>
                 <td class="actions-cell">
    <div class="actions">
        <a href="{{ route('teachers.edit', $att->id) }}" data-id="{{ $att->id }}" 
        class="edit-btn" title="Edit">
            ✏️
        </a>
      
            <button type="submit" data-id="{{ $att->id }}" 
            class="delete-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this attendance?')">🗑️</button>
       
    </div>
</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    var table = $('#attendanceTable').DataTable({
        "order": [[0, "desc"]],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;

                // Only apply to Checkout Type column (index 4 here)
                if (column.index() === 5) {
                    var select = $('<select id="checkout_type" class="form-control"><option value="">All</option></select>')
                        .appendTo($('#filterRow'))   // Add to a placeholder div
                        .on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false).draw();
                        });

                    // Build dropdown options dynamically from column data
                    column.data().unique().sort().each(function (d, j) {
                        if (d) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        }
                    });
                }
            });
        },
        "rowCallback": function(row, data) {
            $(row).removeClass('manual auto changed inclass');
            let type = data[4].toLowerCase();
            if(type === 'manual') $(row).addClass('manual');
            else if(type === 'auto') $(row).addClass('auto');
            else if(type === 'changed by admin') $(row).addClass('changed');
            else if(type === 'in class') $(row).addClass('inclass');
        }
    });

    // Date range filter
    $.fn.dataTable.ext.search.push(function(settings, data) {
        let start = $('#start_date').val();
        let end = $('#end_date').val();
        let date = data[0]; // Date column

        if(start && date < start) return false;
        if(end && date > end) return false;
        return true;
    });

    $('#start_date, #end_date').on('change', function() {
        table.draw();
    });

    $('#clearFilter').on('click', function() {
        $('#start_date, #end_date').val('');
        $('#checkout_type').val('');
        table.search('').columns().search('').draw();
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
