@extends('layout')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h2 class="page-title mb-0">Attendance Records</h2>
        <p class="text-muted mb-0">List of student attendance.</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">Add Record</button>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white">
        <thead class="table-primary">
            <tr>
                <th>Student Name</th>
                <th>Date</th>
                <th>Status</th>
                <th>Remarks</th>
                <th width="180">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr>
                    <td>{{ $record->student_name }}</td>
                    <td>{{ $record->attendance_date }}</td>
                    <td>
                        <span class="badge text-bg-{{ $record->status === 'Present' ? 'success' : ($record->status === 'Late' ? 'warning' : 'danger') }}">
                            {{ $record->status }}
                        </span>
                    </td>
                    <td>{{ $record->remarks ?? '-' }}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editAttendance{{ $record->id }}">Edit</button>
                        <form method="POST" action="/attendance/{{ $record->id }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this record?')">Delete</button>
                        </form>
                    </td>
                </tr>

                <div class="modal fade" id="editAttendance{{ $record->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="/attendance/{{ $record->id }}">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Attendance</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('attendance.form', ['record' => $record])
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No attendance records yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal fade" id="addAttendanceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/attendance">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('attendance.form', ['record' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
