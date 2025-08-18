let lastCheckedKey = null;

function fetchAttendance() {
    fetch('/latest-teacher-attendance')
        .then(res => res.json())
        .then(data => {
            console.log(`${data.teacher_id}_${data.created_at}`);
            console.log(lastCheckedKey);
            console.log(`${data.teacher_id}_${data.created_at}` !== lastCheckedKey);
            if (data && data.teacher_id && data.created_at) {
                const key = `${data.teacher_id}_${data.created_at}`;

                if (key !== lastCheckedKey) {
                    lastCheckedKey = key;

                    Swal.fire({
                        icon: 'success',
                        title: `Teacher ${data.teacher_id} recorded!`,
                        text: `Time: ${data.created_at}`,
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            }
        })
        .catch(err => console.error('Error fetching attendance:', err));
}

// Poll every 5 seconds
setInterval(fetchAttendance, 5000);