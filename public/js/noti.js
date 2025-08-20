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