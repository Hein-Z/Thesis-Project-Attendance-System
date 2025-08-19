
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
                    studentLastActivityKey = key_s;
                    const time =  data.student.check_in ;

                    Swal.fire({
                        icon: 'success',
                            toast: true,

                        title: 
                             message,
                        text: `Time: ${convertToAMPM(time)}`,
                        toast: true,
                        position: 'top-end',
                        timer: 7000,
                        showConfirmButton: false,
                        background:  lastStudentID==ID_key?'#ffd000ff': '#5187acff',
                        color: lastStudentID==ID_key?'#000000ff': '#ffffffff'
                    });

                    // updateTeacherRow(data);
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