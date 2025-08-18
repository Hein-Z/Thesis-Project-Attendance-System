let lastActivityKey = null;

function fetchAttendance() {
    fetch('/latest-teacher-attendance')
        .then(res => res.json())
        .then(data => {
            if (data && data.teacher_id) {
                // Create a unique key based on latest activity
                const key = `${data.teacher_id}_${data.check_in}_${data.check_out}`;
console.log(key);
console.log(lastActivityKey);

                if (key !== lastActivityKey) {
                    lastActivityKey = key;

                    // Determine type
                    const isCheckIn = data.check_out === null;
                    const time = isCheckIn ? data.check_in : data.check_out;

                    Swal.fire({
                        icon: 'success',
                        title: isCheckIn
                            ? `Teacher ${data.name} checked in!`
                            : `Teacher ${data.name} checked out!`,
                        text: `Time: ${time}`,
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false,
                        background: isCheckIn ? '#2ecc70ff' : '#7a6506ff', // green for in, red for out
                        color: '#fff'
                    });
                }
            }
        })
        .catch(err => console.error('Error fetching attendance:', err));
}

// Poll every 5 seconds
setInterval(fetchAttendance, 5000);
