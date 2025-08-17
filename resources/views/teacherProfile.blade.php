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
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Teacher Attendance Profile: {{ $teacher->name }}</h2>

    <!-- Filters -->
    <div class="mb-3 filters">
        <div class="toolbar">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date">
        </div>
        <div class="toolbar">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date">
        </div>
        <div class="toolbar">
            <label for="checkout_type">Checkout Type:</label>
            <select id="checkout_type">
                <option value="">All</option>
                <option value="manual">Manual</option>
                <option value="auto">Auto</option>
                <option value="changed by admin">Changed by Admin</option>
            </select>
        </div>
        <button id="clearFilter" class="btn btn-secondary">Clear</button>
    </div>

    <table id="attendanceTable" class="display table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Check In</th>
                <th>Check Out</th>
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
                <td>{{ $att->check_in ? \Carbon\Carbon::parse($att->check_in)->format('y/m/d l' ) : '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($att->check_in)->format('h:i a') ?? '-' }}</td>
                               <td>{{ \Carbon\Carbon::parse($att->check_out)->format('h:i a') ?? '-' }}</td>

                <td>{{ $att->checkout_type ?? '-' }}</td>
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
</div>

<script>
$(document).ready(function() {
    var table = $('#attendanceTable').DataTable({
        "rowCallback": function(row, data) {
          
        },
        "order": [[1, "desc"]] // default sort by Date descending
    });

    // Custom date range & checkout type filter
    $.fn.dataTable.ext.search.push(
        function(settings, data) {
            let start = $('#start_date').val();
            let end = $('#end_date').val();
            let type = $('#checkout_type').val().toLowerCase();

            let date = data[0];      // Date column
            let checkout = data[3].toLowerCase(); // Checkout type column

            if(start && date < start) return false;
            if(end && date > end) return false;
            if(type && checkout !== type) return false;

            return true;
        }
    );

    $('#start_date, #end_date, #checkout_type').on('change', function() {
        table.order([[0, "desc"]]).draw();
    });

    $('#clearFilter').on('click', function() {
        $('#start_date, #end_date').val('');
        $('#checkout_type').val('');
        table.order([[0, "desc"]]).draw();
    });
});


</script>
</body>
</html>
