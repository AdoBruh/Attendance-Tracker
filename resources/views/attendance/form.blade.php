<div class="mb-3">
    <label class="form-label">Student Name</label>
    <input type="text" name="student_name" class="form-control" value="{{ old('student_name', $record->student_name ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Date</label>
    <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', $record->attendance_date ?? date('Y-m-d')) }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Status</label>
    @php($selected = old('status', $record->status ?? 'Present'))
    <select name="status" class="form-select" required>
        <option value="Present" @selected($selected === 'Present')>Present</option>
        <option value="Absent" @selected($selected === 'Absent')>Absent</option>
        <option value="Late" @selected($selected === 'Late')>Late</option>
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Remarks</label>
    <input type="text" name="remarks" class="form-control" value="{{ old('remarks', $record->remarks ?? '') }}">
</div>
