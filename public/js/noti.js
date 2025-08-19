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
            console.log(data)
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
                    const time = isCheckIn ? data.check_in : data.check_out;

                    Swal.fire({
                        icon: 'success',
                            toast: true,

                        title: isCheckIn
                            ? `Teacher ${data.name} checked in!`
                            : `Teacher ${data.name} checked out!`,
                        text: `Time: ${convertToAMPM(time)}`,
                        toast: true,
                        position: 'top-end',
                        timer: 7000,
                        showConfirmButton: false,
                        background: isCheckIn ? '#2ecc70ff' : '#7a6506ff',
                        color: '#fff'
                    });

                    // updateTeacherRow(data);
                }
            }
        })
        .catch(err => console.error('Error fetching attendance:', err));
}

// ✅ First fetch: only initialize, no notification
fetchAttendance(true);

// ✅ Polling: normal mode
setInterval(fetchAttendance, 5000);