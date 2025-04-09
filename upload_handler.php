$stmt = $conn->prepare("INSERT INTO lessons (
  teacher_email, date, access_time, start_time, end_time, lesson_type,
  area, school, meeting_group, grade, class, lesson_period,
  meeting_link, material, material_link, feedback_form
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param(
  "ssssssssssisssss",
  $data[0],  // teacher_email
  $data[1],  // date
  $data[2],  // access_time
  $data[3],  // start_time
  $data[4],  // end_time
  $data[5],  // lesson_type ✅ now correctly placed
  $data[6],  // area
  $data[7],  // school
  $data[8],  // meeting_group
  $data[9],  // grade
  $data[10], // class
  $data[11], // lesson_period
  $data[12], // meeting_link
  $data[13], // material
  $data[14], // material_link
  $data[15]  // feedback_form
);
