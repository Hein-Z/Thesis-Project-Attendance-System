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
    </style>
</head>
<body>

    <h2>Teacher Attendance</h2>

    <!-- Filter Bar -->
    <div class="filters">
        <div class="toolbar">
            <label for="dateFilter">Date:</label>
            <input type="date" id="dateFilter" />
        </div>

        

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
    <div class="toolbar">
            <label for="dayPicker">Day:</label>
            <select id="dayPicker"></select>
        </div>
        <div class="toolbar">
            <label for="checkoutTypeFilter">Check Out Type:</label>
            <select id="checkoutTypeFilter"></select>
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
                  @if($teacher->checkout_type == 'auto') 
        style="background-color: #e636366b; color: black;" 
    @elseif($teacher->checkout_type == 'In Class') 
        style="background-color: #3aa6d167; color: black;" 
    @endif 
                >
                    <td>{{ $teacher->teacher_id }}</td>
                    <td>{{ $teacher->teacher_info?->name }}</td>
                    <td>{{ $teacher->teacher_info?->subject }}</td>
                    <td>{{  \Carbon\Carbon::parse($teacher->time)->format('l')  }}</td>
                    <td>{{  \Carbon\Carbon::parse($teacher->check_in)->format('h.i')  }}</td>
                    <td>
                        @if ($teacher->check_out)
                            {{ \Carbon\Carbon::parse($teacher->check_out)->format('h:i ') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        {{ $teacher->checkout_type }}
                    </td>
                    <td data-order="{{ \Carbon\Carbon::parse($teacher->time)->format('Y-m-d') }}">
                        {{ \Carbon\Carbon::parse($teacher->time)->format('Y-m-d')}}
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
            order: [[6, 'desc']],
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
    

        $('#dateFilter').on('change', function () {
            var selectedDate = $(this).val();
            table.column(7).search(selectedDate ? '^' + selectedDate + '$' : '', true, false).draw();
        });



        $('#clearFilters').on('click', function () {
            $('#dateFilter,  #subjectFilter , #statusFilter').val('');
            table.columns().search('').draw();
        });

        $('#subjectFilter').on('change', function () {
        var searchValue = $(this).val();
        if (searchValue) {
            table.columns(1).search(searchValue.split(' - ')[0]); // Name column
            table.columns(2).search(searchValue.split(' - ')[1]); // Subject column
        } else {
            table.columns(1).search('');
            table.columns(2).search('');
        }
        table.draw();
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
