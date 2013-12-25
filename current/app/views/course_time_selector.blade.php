<div id="course_time_selector">
	<table>
		<tr id="day_row">
			<th id="course_time_description">上課時間</th>
			<th>週一</th>
			<th>週二</th>
			<th>週三</th>
			<th>週四</th>
			<th>週五</th>
		</tr>
		<tr>
			<td class="course_column">第一節</td>
			@for ($i = 1; $i <= 35; $i = $i + 7)
				<td class="course" id="course_{{$i}}" data-selected="{{ (isset($course_time)) ? substr($course_time, $i-1, 1) : '0'}}">&nbsp;</td>
			@endfor
		</tr>
		<tr>
			<td class="course_column">第二節</td>
			@for ($i = 2; $i <= 35; $i = $i + 7)
				<td class="course" id="course_{{$i}}" data-selected="{{ (isset($course_time)) ? substr($course_time, $i-1, 1) : '0'}}">&nbsp;</td>
			@endfor
		</tr>
		<tr>
			<td class="course_column">第三節</td>
			@for ($i = 3; $i <= 35; $i = $i + 7)
				<td class="course" id="course_{{$i}}" data-selected="{{ (isset($course_time)) ? substr($course_time, $i-1, 1) : '0'}}">&nbsp;</td>
			@endfor
		</tr>
		<tr>
			<td class="course_column">第四節</td>
			@for ($i = 4; $i <= 35; $i = $i + 7)
				<td class="course" id="course_{{$i}}" data-selected="{{ (isset($course_time)) ? substr($course_time, $i-1, 1) : '0'}}">&nbsp;</td>
			@endfor
		</tr>
		<tr>
			<td id="noon_break" colspan="6">午休</td>
		</tr>
		<tr>
			<td class="course_column">第五節</td>
			@for ($i = 5; $i <= 35; $i = $i + 7)
				<td class="course" id="course_{{$i}}" data-selected="{{ (isset($course_time)) ? substr($course_time, $i-1, 1) : '0'}}">&nbsp;</td>
			@endfor
		</tr>
		<tr>
			<td class="course_column">第六節</td>
			@for ($i = 6; $i <= 35; $i = $i + 7)
				<td class="course" id="course_{{$i}}" data-selected="{{ (isset($course_time)) ? substr($course_time, $i-1, 1) : '0'}}">&nbsp;</td>
			@endfor
		</tr>
		<tr>
			<td class="course_column">第七節</td>
			@for ($i = 7; $i <= 35; $i = $i + 7)
				<td class="course" id="course_{{$i}}" data-selected="{{ (isset($course_time)) ? substr($course_time, $i-1, 1) : '0'}}">&nbsp;</td>
			@endfor
		</tr>
	</table>
</div>

