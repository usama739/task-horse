@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 pb-5">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-3xl font-bold text-white">Dashboard</h1>
        <div class="flex flex-wrap gap-2">
            <select class="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #161f30;">
                <option>All Projects</option>
                <option>Project A</option>
                <option>Project B</option>
            </select>
            <select class="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #161f30;">
                <option>Status: All</option>
                <option>Pending</option>
                <option>In Progress</option>
                <option>Completed</option>
            </select>
            <input type="text" placeholder="Search..." class="border border-blue-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" style="background: #161f30;"/>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-800 to-blue-600 rounded-xl p-6 shadow flex flex-col items-center">
            <span class="text-lg text-blue-200 font-semibold mb-2">Total Tasks</span>
            <span class="text-4xl font-bold text-white">123</span>
        </div>
        <div class="bg-gradient-to-br from-green-700 to-green-500 rounded-xl p-6 shadow flex flex-col items-center">
            <span class="text-lg text-green-100 font-semibold mb-2">Completed</span>
            <span class="text-4xl font-bold text-white">87</span>
        </div>
        <div class="bg-gradient-to-br from-yellow-700 to-yellow-500 rounded-xl p-6 shadow flex flex-col items-center">
            <span class="text-lg text-yellow-100 font-semibold mb-2">Pending</span>
            <span class="text-4xl font-bold text-white">36</span>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        <!-- Line Chart: Task Volume by Status Over Last 30 Days -->
        <div class="rounded-xl p-6 shadow flex flex-col" style="background: #161f30;">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-semibold text-white">Task Volume by Status (Last 30 Days)</h2>
            </div>
            <canvas id="taskStatusLineChart" height="120"></canvas>
        </div>

        <!-- Bar Chart: Tasks Completed Per User This Month -->
        <div class="rounded-xl p-6 shadow flex flex-col" style="background: #161f30;">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-semibold text-white">Tasks Completed Per User</h2>
            </div>
            <canvas id="userBarChart" height="120"></canvas>
        </div>

        <!-- Pie Chart: Current Tasks by Priority -->
        <div class="rounded-xl p-6 shadow flex flex-col" style="background: #161f30;">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-semibold text-white">Current Tasks by Priority</h2>
            </div>
            <canvas id="priorityPieChart" height="120"></canvas>
        </div>

        <!-- Donut Chart: Tasks Contribution by Project -->
        <div class="rounded-xl p-6 shadow flex flex-col" style="background: #161f30;">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-semibold text-white">Tasks by Project</h2>
            </div>
            <canvas id="projectDonutChart" height="120"></canvas>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Timeline Card: Tasks Timeline -->
        <div class="rounded-xl p-6 shadow flex flex-col" style="background: #161f30; min-height: 260px;">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-semibold text-white">Tasks Timeline</h2>
            </div>
            <ul class="space-y-4">
                <li class="relative pl-6">
                    <span class="absolute left-0 top-1 w-3 h-3 bg-blue-500 rounded-full"></span>
                    <span class="pl-5 text-blue-200 font-semibold">2025-06-01</span> — Task "Design UI" created
                </li>
                <li class="relative pl-6">
                    <span class="absolute left-0 top-1 w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="pl-5 text-green-200 font-semibold">2025-06-03</span> — Task "Setup AWS S3" completed
                </li>
                <li class="relative pl-6">
                    <span class="absolute left-0 top-1 w-3 h-3 bg-yellow-400 rounded-full"></span>
                    <span class="pl-5 text-yellow-200 font-semibold">2025-06-05</span> — Task "Write Docs" pending
                </li>
                <li class="relative pl-6">
                    <span class="absolute left-0 top-1 w-3 h-3 bg-blue-500 rounded-full"></span>
                    <span class="pl-5 text-blue-200 font-semibold">2025-06-07</span> — Task "Review Security" created
                </li>
            </ul>
        </div>
        <!-- Recent Activity Card -->
        <div class="rounded-xl p-6 shadow flex flex-col" style="background: #161f30;">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-semibold text-white">Recent Activity</h2>
            </div>
            <ul class="divide-y divide-blue-900">
                <li class="py-3 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    <span class="text-blue-100">Task <span class="font-semibold text-white">"Setup AWS S3"</span> was completed by <span class="font-semibold text-white">Alex</span></span>
                    <span class="ml-auto text-xs text-blue-400">2 hours ago</span>
                </li>
                <li class="py-3 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    <span class="text-blue-100">Task <span class="font-semibold text-white">"Design UI"</span> was created by <span class="font-semibold text-white">Jamie</span></span>
                    <span class="ml-auto text-xs text-blue-400">5 hours ago</span>
                </li>
                <li class="py-3 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                    <span class="text-blue-100">Task <span class="font-semibold text-white">"Write Docs"</span> is pending review</span>
                    <span class="ml-auto text-xs text-blue-400">1 day ago</span>
                </li>
                <li class="py-3 flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-green-400"></span>
                    <span class="text-blue-100">Task <span class="font-semibold text-white">"Setup AWS S3"</span> was completed by <span class="font-semibold text-white">Alex</span></span>
                    <span class="ml-auto text-xs text-blue-400">2 days ago</span>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Line Chart: Task Volume by Status Over Last 30 Days
    new Chart(document.getElementById('taskStatusLineChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: [...Array(30).keys()].map(i => `Day ${i + 1}`),
            datasets: [
                {
                    label: 'Created',
                    data: [5, 4, 6, 8, 7, 4, 5, 6, 5, 3, 7, 8, 6, 4, 3, 4, 6, 5, 7, 8, 9, 10, 7, 6, 5, 4, 6, 5, 3, 2],
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'In Progress',
                    data: [2, 3, 2, 5, 6, 7, 6, 5, 4, 6, 5, 7, 6, 5, 4, 3, 2, 3, 4, 6, 7, 6, 5, 4, 3, 2, 1, 2, 3, 4],
                    borderColor: '#facc15',
                    backgroundColor: 'rgba(250,204,21,0.2)',
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Completed',
                    data: [1, 2, 1, 2, 3, 4, 3, 5, 6, 5, 4, 3, 4, 5, 6, 5, 4, 5, 6, 7, 6, 5, 4, 3, 2, 1, 2, 1, 2, 3],
                    borderColor: '#22d3ee',
                    backgroundColor: 'rgba(34,211,238,0.2)',
                    fill: true,
                    tension: 0.4
                }
            ]
        },
        options: {
            plugins: { legend: { labels: { color: '#cbd5e1' } } },
            scales: {
                x: { ticks: { color: '#cbd5e1' }, grid: { color: '#1e293b' } },
                y: { ticks: { color: '#cbd5e1' }, grid: { color: '#1e293b' } }
            }
        }
    });

    // Bar Chart: Tasks Completed Per User
    new Chart(document.getElementById('userBarChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Alice', 'Bob', 'Charlie', 'Diana', 'Eva'],
            datasets: [{
                label: 'Completed Tasks',
                data: [12, 18, 9, 15, 11],
                backgroundColor: ['#3b82f6', '#22d3ee', '#facc15', '#38bdf8', '#a78bfa'],
                borderRadius: 8
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { color: '#cbd5e1' }, grid: { color: '#1e293b' } },
                y: { ticks: { color: '#cbd5e1' }, grid: { color: '#1e293b' } }
            }
        }
    });

    // Pie Chart: Current Tasks by Priority
    new Chart(document.getElementById('priorityPieChart').getContext('2d'), {
        type: 'pie',
        data: {
            labels: ['High', 'Medium', 'Low'],
            datasets: [{
                data: [20, 35, 45],
                backgroundColor: ['#ef4444', '#facc15', '#10b981'],
                borderColor: '#161f30',
                borderWidth: 2
            }]
        },
        options: {
            plugins: {
                legend: {
                    labels: { color: '#cbd5e1', font: { size: 14 } }
                }
            }
        }
    });

    // Donut Chart: Tasks by Project
    new Chart(document.getElementById('projectDonutChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['CRM Revamp', 'E-commerce Site', 'Internal Tools', 'Marketing Dash'],
            datasets: [{
                data: [40, 25, 20, 15],
                backgroundColor: ['#38bdf8', '#34d399', '#f472b6', '#facc15'],
                borderColor: '#161f30',
                borderWidth: 2
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: {
                    labels: { color: '#cbd5e1', font: { size: 14 } }
                }
            }
        }
    });
</script>

@endsection
