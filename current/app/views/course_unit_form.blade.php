{{ FormList::open('', 'timetable/add/' . $titleId . '/' . $teacher->teacher_id) }}
{{ FormList::description('設定〔' . $teacher->teacher_name . '〕排課資料') }}
	
{{ FormList::select('course_id', '課程', array('required' => 'required'), array('Course', 'course_id', 'course_name')) }}	
{{ FormList::select('classes_id', '班級', array('required' => 'required'), array('Classes', 'classes_id', 'classes_name')) }}
{{ FormList::select('count', '節數', array('range' => array(1, 15))) }}
{{ FormList::select('classroom_id', '使用教室', array('valueArray' => array('0' => '無'), 'required' => 'required'), array('Classroom', 'classroom_id', 'classroom_name')) }}
{{ FormList::hidden('teacher_id', $teacher->teacher_id) }}
{{ FormList::submit('新增') }}
{{ FormList::close() }}


限制排課時間

