@extends('layout')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h2 class="page-title mb-0">Dashboard</h2>
        <p class="text-muted mb-0">Hello, {{ auth()->user()->name }}</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Users</p>
                <div class="stat-number">{{ $userCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Attendance</p>
                <div class="stat-number">{{ $attendanceCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <p class="text-muted mb-1">Present</p>
                <div class="stat-number">{{ $presentCount }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Attendance Chart</h5>
        @php
            $total = max(array_sum($chartData), 1);
            $presentPercent = round(($chartData[0] / $total) * 100);
            $absentPercent = round(($chartData[1] / $total) * 100);
            $latePercent = round(($chartData[2] / $total) * 100);
        @endphp

        <div
            id="attendanceChart"
            class="mb-4"
            data-present="{{ $chartData[0] }}"
            data-absent="{{ $chartData[1] }}"
            data-late="{{ $chartData[2] }}"
        ></div>

        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span>Present</span>
                <strong>{{ $chartData[0] }}</strong>
            </div>
            <div class="progress">
                <div class="progress-bar bg-success" style="width: {{ $presentPercent }}%"></div>
            </div>
        </div>

        <div class="mb-3">
            <div class="d-flex justify-content-between mb-1">
                <span>Absent</span>
                <strong>{{ $chartData[1] }}</strong>
            </div>
            <div class="progress">
                <div class="progress-bar bg-danger" style="width: {{ $absentPercent }}%"></div>
            </div>
        </div>

        <div>
            <div class="d-flex justify-content-between mb-1">
                <span>Late</span>
                <strong>{{ $chartData[2] }}</strong>
            </div>
            <div class="progress">
                <div class="progress-bar bg-warning" style="width: {{ $latePercent }}%"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let chartBox = document.getElementById('attendanceChart');

    if (chartBox) {
        let data = [
            { label: 'Present', value: Number(chartBox.dataset.present), color: 'bg-success' },
            { label: 'Absent', value: Number(chartBox.dataset.absent), color: 'bg-danger' },
            { label: 'Late', value: Number(chartBox.dataset.late), color: 'bg-warning' }
        ];

        function AttendanceChart() {
            let highest = 1;
            data.forEach(function(row) {
                if (row.value > highest) {
                    highest = row.value;
                }
            });

            return React.createElement('div', { className: 'd-flex align-items-end gap-4 border rounded bg-light p-3', style: { height: '230px' } },
                data.map(function(row) {
                    let height = (row.value / highest) * 150;

                    if (row.value > 0 && height < 20) {
                        height = 20;
                    }

                    return React.createElement('div', {
                        key: row.label,
                        className: 'd-flex flex-column align-items-center flex-fill h-100 justify-content-end'
                    },
                        React.createElement('strong', { className: 'mb-2' }, row.value),
                        React.createElement('div', {
                            className: row.color + ' rounded-top w-75',
                            style: { height: height + 'px' }
                        }),
                        React.createElement('small', { className: 'mt-2 text-muted' }, row.label)
                    );
                })
            );
        }

        ReactDOM.createRoot(chartBox).render(React.createElement(AttendanceChart));
    }
</script>
@endsection
