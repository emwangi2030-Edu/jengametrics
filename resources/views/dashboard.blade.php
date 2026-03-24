@extends('layouts.app')

@section('content')
@php
    $T = [
        'bg' => '#f4f6f4', 'surface' => '#ffffff', 'card' => '#ffffff',
        'green' => '#1a7a3c', 'greenLt' => '#e8f5ee', 'greenMid' => '#b6ddc5', 'greenViv' => '#22c55e',
        'amber' => '#d97706', 'amberLt' => '#fef3c7', 'red' => '#dc2626', 'redLt' => '#fee2e2',
        'blue' => '#2563eb', 'blueLt' => '#dbeafe', 'purple' => '#7c3aed', 'purpleLt' => '#ede9fe',
        'border' => '#e5ebe6', 'borderMd' => '#cfdad1',
        'txt1' => '#0d1f0f', 'txt2' => '#456048', 'txt3' => '#8fa894',
        'shadow' => '0 1px 3px rgba(0,0,0,0.05), 0 4px 12px rgba(0,0,0,0.04)',
    ];
    $fmtM = function($n) { return 'KES ' . number_format($n / 1e6, 1) . 'M'; };
    $avgProg = count($dashboardProjects) > 0 ? (int) round(collect($dashboardProjects)->avg('progress')) : 0;
    $projectColors = [$T['green'], $T['blue'], $T['amber'], $T['purple']];
@endphp
<style>
    .jm-dash { font-family: 'Outfit', 'Inter', sans-serif; color: {{ $T['txt1'] }}; background: {{ $T['bg'] }}; }
    .jm-card { background: {{ $T['card'] }}; border: 1px solid {{ $T['border'] }}; border-radius: 16px; box-shadow: {{ $T['shadow'] }}; }
    .jm-sl { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    .jm-sl-bar { width: 3px; height: 14px; background: {{ $T['green'] }}; border-radius: 99px; }
    .jm-sl-title { font-weight: 800; font-size: 12.5px; color: {{ $T['txt1'] }}; text-transform: uppercase; letter-spacing: 0.06em; margin-left: 8px; }
    .jm-arc { transform: rotate(-90deg); flex-shrink: 0; }
    .jm-pill { border-radius: 20px; padding: 3px 10px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; letter-spacing: 0.04em; white-space: nowrap; }
    @keyframes ap { 0%,100%{opacity:1;} 50%{opacity:0.3;} }

    .jm-dash-wrap { padding: 22px 26px; }
    .jm-dash-header { background:#fff; border:1px solid {{ $T['border'] }}; border-radius:12px; padding:0 28px; height:58px; }
    .jm-dash-title { font-size:19px; font-weight:800; letter-spacing:-0.03em; }
    .jm-radius-10 { border-radius:10px; }
    .jm-cursor-grab { cursor: grab; }
</style>
<link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<div class="jm-dash container-fluid jm-dash-wrap">
    {{-- Top bar --}}
    <header class="d-flex align-items-center justify-content-between mb-4 jm-dash-header">
        <div class="d-flex align-items-center gap-3">
            <span class="jm-dash-title">Dashboard</span>
            <span data-inline-style="background:{{ $T['bg'] }}; border:1px solid {{ $T['border'] }}; border-radius:7px; padding:3px 10px; font-size:11px; color:{{ $T['txt3'] }}; font-family:'IBM Plex Mono',monospace;">{{ now()->format('F Y') }}</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if(count(array_filter($alerts, fn($a)=>($a['type']??'')==='danger')) > 0)
                <div data-inline-style="background:{{ $T['redLt'] }}; border:1px solid #fca5a5; border-radius:8px; padding:5px 13px; font-size:11px; color:{{ $T['red'] }}; font-weight:700; display:flex; align-items:center; gap:6;">
                    <span data-inline-style="width:6px; height:6px; border-radius:50%; background:{{ $T['red'] }}; animation:ap 1.5s infinite;"></span>
                    {{ count(array_filter($alerts, fn($a)=>($a['type']??'')==='danger')) }} critical alert
                </div>
            @endif
            <a href="{{ route('projects.index') }}" class="btn btn-primary" data-inline-style="background:{{ $T['green'] }}; border:none; border-radius:10px; padding:9px 20px; font-size:12.5px; font-weight:800; box-shadow:0 2px 10px rgba(26,122,60,0.33);">+ New Project</a>
            <button type="button" class="btn btn-outline-secondary jm-radius-10" data-bs-toggle="modal" data-bs-target="#projectStepsModal">Project Steps</button>
        </div>
    </header>

    {{-- Stat cards --}}
    <div class="row g-3 mb-4">
        @php
            $statCards = [
                ['label'=>'Active Projects', 'value'=>$activeCount, 'pre'=>'', 'suf'=>'', 'sub'=>count($dashboardProjects).' total', 'accent'=>$T['green'], 'bg'=>$T['greenLt'], 'icon'=>'🏗'],
                ['label'=>'Total Budget', 'value'=>$fmtM($totalBudget), 'pre'=>'', 'suf'=>'', 'sub'=>'across all projects', 'accent'=>$T['txt1'], 'bg'=>'#fafbfa', 'icon'=>'💰'],
                ['label'=>'Total Spent', 'value'=>$fmtM($totalSpent), 'pre'=>'', 'suf'=>'', 'sub'=>($totalBudget > 0 ? round($totalSpent/$totalBudget*100) : 0).'% of budget', 'accent'=>$T['green'], 'bg'=>$T['greenLt'], 'icon'=>'📤'],
                ['label'=>'Pending Invoices', 'value'=>$pendingInvoicesCount, 'pre'=>'', 'suf'=>'', 'sub'=>$fmtM($pendingInvoicesAmount).' outstanding', 'accent'=>$T['red'], 'bg'=>$T['redLt'], 'icon'=>'🧾'],
            ];
        @endphp
        @foreach($statCards as $c)
        <div class="col-md-3">
            <div class="jm-card position-relative overflow-hidden" data-inline-style="padding:20px 22px; background:{{ $c['bg'] }}; border-color:{{ $T['border'] }};">
                <div data-inline-style="font-size:22px; margin-bottom:10px;">{{ $c['icon'] }}</div>
                <div data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:28px; font-weight:700; color:{{ $c['accent'] }}; letter-spacing:-0.03em;">{{ $c['pre'] }}{{ $c['value'] }}{{ $c['suf'] }}</div>
                <div data-inline-style="font-size:10.5px; font-weight:800; color:{{ $T['txt2'] }}; margin-top:7px; text-transform:uppercase; letter-spacing:0.08em;">{{ $c['label'] }}</div>
                <div data-inline-style="font-size:11px; color:{{ $T['txt3'] }}; margin-top:3px;">{{ $c['sub'] }}</div>
                <div data-inline-style="position:absolute; right:-18px; top:-18px; width:72px; height:72px; border-radius:50%; background:{{ $c['accent'] }}0e; border:1px solid {{ $c['accent'] }}1a;"></div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Area + Donut --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="jm-card p-4">
                <div class="jm-sl"><div class="d-flex align-items-center"><span class="jm-sl-bar"></span><span class="jm-sl-title">Monthly Spend — {{ $selectedYear }} (KES M)</span></div><span data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:{{ $T['green'] }}; cursor:pointer;">Export →</span></div>
                <div data-inline-style="height:196px;"><canvas id="areaChart"></canvas></div>
                <div class="d-flex gap-3 mt-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2" data-inline-style="font-size:10px; color:{{ $T['txt3'] }};"><span data-inline-style="width:16px; height:2.5px; background:{{ $T['green'] }}; border-radius:99px;"></span>Material</div>
                    <div class="d-flex align-items-center gap-2" data-inline-style="font-size:10px; color:{{ $T['txt3'] }};"><span data-inline-style="width:16px; height:2.5px; background:{{ $T['amber'] }}; border-radius:99px;"></span>Labour</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="jm-card p-4">
                <div class="jm-sl"><div class="d-flex align-items-center"><span class="jm-sl-bar"></span><span class="jm-sl-title">Cost Breakdown</span></div></div>
                <div class="position-relative d-flex justify-content-center align-items-center" data-inline-style="height:154px;">
                    <canvas id="donutChart" width="154" height="154" data-inline-style="position:absolute;"></canvas>
                    <div class="text-center" data-inline-style="position:relative; z-index:1;">
                        <div data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:22px; font-weight:700; color:{{ $T['txt1'] }};">{{ $avgProg }}%</div>
                        <div data-inline-style="font-size:9px; color:{{ $T['txt3'] }}; text-transform:uppercase; letter-spacing:0.08em;">avg done</div>
                    </div>
                </div>
                <div class="d-flex flex-column gap-2">
                    @foreach($costBreakdown ?? [] as $item)
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span data-inline-style="width:9px; height:9px; border-radius:3px; background:{{ $item['color'] }};"></span>
                            <span data-inline-style="font-size:11.5px; color:{{ $T['txt2'] }}; font-weight:500;">{{ $item['name'] }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div data-inline-style="width:52px; background:{{ $T['bg'] }}; border-radius:99px; height:4px;"><div data-inline-style="width:{{ $item['value'] }}%; height:100%; background:{{ $item['color'] }}; border-radius:99px;"></div></div>
                            <span data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:{{ $T['txt1'] }}; min-width:24px; text-align:right;">{{ $item['value'] }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Projects + Side --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="jm-card p-4">
                <div class="jm-sl"><div class="d-flex align-items-center"><span class="jm-sl-bar"></span><span class="jm-sl-title">Active Projects</span></div><a href="{{ route('projects.index') }}" data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:{{ $T['green'] }};">View all →</a></div>
                <div class="d-flex flex-column gap-2">
                    @forelse($dashboardProjects as $i => $p)
                    @php
                        $sp = $p['budget'] > 0 ? min(100, (int)round($p['spent']/$p['budget']*100)) : 0;
                        $rc = $sp > 90 ? $T['red'] : ($sp > 75 ? $T['amber'] : ($projectColors[$i % 4] ?? $T['green']));
                        $workers = $p['workers'] ?? 0;
                    @endphp
                    <div class="p-3 rounded-3" data-inline-style="border:1px solid {{ $T['border'] }}; background:{{ $T['bg'] }}; transition:all 0.16s;">
                        <div class="d-flex align-items-center gap-3">
                            <div class="position-relative" data-inline-style="width:48px; height:48px;">
                                <svg class="jm-arc" width="48" height="48" data-inline-style="position:absolute; left:0; top:0;"><circle cx="24" cy="24" r="20" fill="none" stroke="{{ $T['border'] }}" stroke-width="4.5"/><circle cx="24" cy="24" r="20" fill="none" stroke="{{ $rc }}" stroke-width="4.5" stroke-dasharray="{{ 2*3.14159*20*$p['progress']/100 }} {{ 2*3.14159*20 }}" stroke-linecap="round"/></svg>
                                <div data-inline-style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono',monospace; font-size:10px; font-weight:700; color:{{ $rc }};">{{ $p['progress'] }}%</div>
                            </div>
                            <div class="flex-grow-1">
                                <div data-inline-style="font-size:13.5px; font-weight:700; color:{{ $T['txt1'] }};">{{ $p['name'] }}</div>
                                <div data-inline-style="font-size:10px; color:{{ $T['txt3'] }}; margin-top:2px; font-family:'IBM Plex Mono',monospace;">{{ $p['project_uid'] }} · {{ $p['phase'] }} · {{ $p['weeksLeft'] }}w left · {{ $workers }} workers</div>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <div data-inline-style="flex:1; background:{{ $T['border'] }}; border-radius:99px; height:5px; overflow:hidden;"><div data-inline-style="width:{{ $sp }}%; height:100%; background:{{ $rc }}; border-radius:99px;"></div></div>
                                    <span data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:{{ $T['txt3'] }}; white-space:nowrap;">{{ $fmtM($p['spent']) }} / {{ $fmtM($p['budget']) }}</span>
                                </div>
                            </div>
                            @php
                                $pillStyle = ($p['status'] ?? '') === 'On Hold' ? [$T['amber'], $T['amberLt']] : (($p['status'] ?? '') === 'Completed' ? [$T['blue'], $T['blueLt']] : [$T['green'], $T['greenLt']]);
                            @endphp
                            <span class="jm-pill" data-inline-style="background:{{ $pillStyle[1] }}; color:{{ $pillStyle[0] }};"><span data-inline-style="width:5px; height:5px; border-radius:50%; background:{{ $pillStyle[0] }};"></span>{{ $p['status'] ?? 'Active' }}</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No projects. <a href="{{ route('projects.index') }}">Create one</a>.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="jm-card p-4 mb-3">
                <div class="jm-sl"><div class="d-flex align-items-center"><span class="jm-sl-bar"></span><span class="jm-sl-title">Labour — This Week</span></div></div>
                <div data-inline-style="height:120px;"><canvas id="labourBarChart"></canvas></div>
                <div class="d-flex justify-content-around mt-2 pt-2" data-inline-style="border-top:1px solid {{ $T['border'] }};">
                    <div class="text-center"><div data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:17px; font-weight:700; color:{{ $T['green'] }};">—</div><div data-inline-style="font-size:9px; color:{{ $T['txt3'] }}; text-transform:uppercase; letter-spacing:0.06em;">Peak day</div></div>
                    <div class="text-center"><div data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:17px; font-weight:700; color:{{ $T['green'] }};">—</div><div data-inline-style="font-size:9px; color:{{ $T['txt3'] }}; text-transform:uppercase;">Avg/day</div></div>
                    <div class="text-center"><div data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:17px; font-weight:700; color:{{ $T['green'] }};">—</div><div data-inline-style="font-size:9px; color:{{ $T['txt3'] }};">Man-hours</div></div>
                </div>
            </div>
            <div class="jm-card p-4 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2"><span class="jm-sl-bar"></span><span class="jm-sl-title mb-0">Alerts</span></div>
                    @if(count($alerts) > 0)<span data-inline-style="background:{{ $T['redLt'] }}; color:{{ $T['red'] }}; border-radius:20px; padding:2px 8px; font-size:10px; font-weight:700;">{{ count($alerts) }}</span>@endif
                </div>
                <div class="d-flex flex-column gap-2">
                    @forelse($alerts as $a)
                    @php $ac = ($a['type']??'')==='danger' ? [$T['red'],$T['redLt']] : (($a['type']??'')==='warning' ? [$T['amber'],$T['amberLt']] : [$T['blue'],$T['blueLt']]); @endphp
                    <div data-inline-style="padding:9px 11px; border-radius:9px; background:{{ $ac[1] }}; border-left:3px solid {{ $ac[0] }}; font-size:11.5px; color:{{ $T['txt1'] }}; line-height:1.45;">{{ $a['message'] ?? '' }}</div>
                    @empty
                    <div data-inline-style="font-size:11px; color:{{ $T['txt3'] }};">No alerts.</div>
                    @endforelse
                </div>
            </div>
            <div class="jm-card p-4">
                <div class="d-flex align-items-center gap-2 mb-3"><span class="jm-sl-bar"></span><span class="jm-sl-title mb-0">Recent Activity</span></div>
                @forelse($recentActivity as $i => $a)
                <div class="d-flex gap-2 {{ $i < count($recentActivity)-1 ? 'mb-3 pb-3' : '' }}" data-inline-style="{{ $i < count($recentActivity)-1 ? 'border-bottom:1px solid '.$T['border'] : '' }}">
                    <div data-inline-style="width:30px; height:30px; border-radius:8px; background:{{ $T['bg'] }}; border:1px solid {{ $T['border'] }}; display:flex; align-items:center; justify-content:center; font-size:13px; flex-shrink:0;">{{ $a['icon'] ?? '•' }}</div>
                    <div class="flex-grow-1 min-w-0">
                        <div data-inline-style="font-size:11.5px; font-weight:700;">{{ $a['action'] ?? '' }} <span data-inline-style="color:{{ $T['txt3'] }}; font-weight:400;">· {{ $a['project'] ?? '' }}</span></div>
                        <div data-inline-style="font-size:10px; color:{{ $T['txt3'] }}; margin-top:2px; font-family:'IBM Plex Mono',monospace;">{{ $a['user'] ?? '' }} · {{ $a['time'] ?? '' }}</div>
                    </div>
                </div>
                @empty
                <div data-inline-style="font-size:11px; color:{{ $T['txt3'] }};">No recent activity.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Milestones + Radial --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="jm-card p-4">
                <div class="jm-sl"><div class="d-flex align-items-center"><span class="jm-sl-bar"></span><span class="jm-sl-title">Upcoming Milestones</span></div><span data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:{{ $T['green'] }};">Full schedule →</span></div>
                <div class="d-flex flex-column gap-2">
                    @forelse($milestones ?? [] as $m)
                    <div class="d-flex align-items-center gap-3 p-3 rounded-3" data-inline-style="background:{{ $T['bg'] }}; border:1px solid {{ $T['border'] }};">
                        <div data-inline-style="width:3px; height:30px; background:{{ $T['green'] }}; border-radius:99px;"></div>
                        <div class="flex-grow-1">
                            <div data-inline-style="font-size:12.5px; font-weight:700;">{{ $m->title }}</div>
                            <div data-inline-style="font-size:10px; color:{{ $T['txt3'] }}; font-family:'IBM Plex Mono',monospace; margin-top:1px;">{{ $m->project->name ?? '—' }}</div>
                        </div>
                        <div class="text-end"><span data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:11px; font-weight:700; color:{{ $T['green'] }};">Upcoming</span></div>
                    </div>
                    @empty
                    <div data-inline-style="font-size:12px; color:{{ $T['txt3'] }};">No upcoming steps.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="jm-card p-4">
                <div class="jm-sl"><div class="d-flex align-items-center"><span class="jm-sl-bar"></span><span class="jm-sl-title">Budget Used</span></div></div>
                <div data-inline-style="height:155px;"><canvas id="radialChart"></canvas></div>
                <div class="d-flex flex-column gap-2 mt-2">
                    @foreach($dashboardProjects as $i => $p)
                    @php $sp = $p['budget'] > 0 ? min(100, (int)round($p['spent']/$p['budget']*100)) : 0; $c = $sp > 90 ? $T['red'] : ($sp > 75 ? $T['amber'] : ($projectColors[$i % 4] ?? $T['green'])); @endphp
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span data-inline-style="width:8px; height:8px; border-radius:2px; background:{{ $c }};"></span>
                            <span data-inline-style="font-size:10.5px; color:{{ $T['txt2'] }};">{{ implode(' ', array_slice(explode(' ', $p['name']), 0, 2)) }}</span>
                        </div>
                        <span data-inline-style="font-family:'IBM Plex Mono',monospace; font-size:10.5px; color:{{ $c }}; font-weight:700;">{{ $sp }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Year + trend --}}
    <div class="jm-card p-4">
        <form method="GET" action="{{ route('dashboard') }}" class="mb-2">
            <label for="year" class="form-label small" data-inline-style="color:{{ $T['green'] }};">Year:</label>
            <select name="year" id="year" onchange="this.form.submit()" class="form-select form-select-sm w-auto" data-inline-style="border-radius:8px;">
                @foreach($availableYears as $y)<option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>{{ $y }}</option>@endforeach
            </select>
        </form>
        <div class="jm-sl"><div class="d-flex align-items-center"><span class="jm-sl-bar"></span><span class="jm-sl-title">Material & Labour Trends ({{ $selectedYear }})</span></div></div>
        <canvas id="expenseChart" height="120"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-inline-style]').forEach(function(el){
        el.style.cssText = el.getAttribute('data-inline-style') || '';
    });
    var T = { green:'#1a7a3c', greenMid:'#b6ddc5', amber:'#d97706', blue:'#2563eb', purple:'#7c3aed', red:'#dc2626', border:'#e5ebe6', txt3:'#8fa894' };
    var labels = @json($labels);
    var materialData = @json($data);
    var labourData = @json($labourData);
    var costBreakdown = @json($costBreakdown ?? []);
    var dashboardProjects = @json($dashboardProjects);
    var fmtM = function(n) { return (n/1e6).toFixed(1) + 'M'; };

    if (document.getElementById('areaChart')) {
        new Chart(document.getElementById('areaChart').getContext('2d'), {
            type: 'line',
            data: { labels: labels, datasets: [
                { label: 'Material', data: materialData, borderColor: T.green, backgroundColor: T.green + '33', fill: true, tension: 0.3 },
                { label: 'Labour', data: labourData, borderColor: T.amber, backgroundColor: T.amber + '22', fill: true, tension: 0.3 }
            ]},
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('donutChart')) {
        new Chart(document.getElementById('donutChart').getContext('2d'), {
            type: 'doughnut',
            data: { labels: costBreakdown.map(function(x){ return x.name; }), datasets: [{ data: costBreakdown.map(function(x){ return x.value; }), backgroundColor: costBreakdown.map(function(x){ return x.color; }), borderColor: '#fff', borderWidth: 2.5 }] },
            options: { cutout: '60%', responsive: false, plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('labourBarChart')) {
        var days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        new Chart(document.getElementById('labourBarChart').getContext('2d'), {
            type: 'bar',
            data: { labels: days, datasets: [{ label: 'Workers', data: [0,0,0,0,0,0,0], backgroundColor: T.greenMid + 'cc', borderRadius: 4 }] },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('radialChart')) {
        var radialData = dashboardProjects.map(function(p){ return p.budget > 0 ? Math.min(100, Math.round(p.spent / p.budget * 100)) : 0; });
        var radialColors = [T.green, T.blue, T.amber, T.purple];
        new Chart(document.getElementById('radialChart').getContext('2d'), {
            type: 'bar',
            data: { labels: dashboardProjects.map(function(p){ return p.project_uid; }), datasets: [{ data: radialData, backgroundColor: radialData.map(function(v,i){ return v > 90 ? T.red : v > 75 ? T.amber : radialColors[i % 4]; }), borderRadius: 4 }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, scales: { x: { max: 100, grid: { display: false } }, y: { grid: { display: false } } }, plugins: { legend: { display: false } } }
        });
    }
    if (document.getElementById('expenseChart')) {
        new Chart(document.getElementById('expenseChart').getContext('2d'), {
            type: 'line',
            data: { labels: labels, datasets: [
                { label: 'Material', data: materialData, borderColor: T.green, backgroundColor: T.green + '18', fill: true, tension: 0.3 },
                { label: 'Labour', data: labourData, borderColor: T.amber, backgroundColor: T.amber + '18', fill: true, tension: 0.3 }
            ]},
            options: { responsive: true, scales: { y: { beginAtZero: true } }, plugins: { tooltip: { callbacks: { label: function(c) { return 'KES ' + c.parsed.y.toLocaleString(); } } } } }
        });
    }
});
</script>

{{-- Project Steps modals --}}
<div class="modal fade" id="projectStepsModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Project Steps</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div id="projectStepsList">@if($projectSteps->isEmpty())<p class="text-muted mb-0">No project steps yet.</p>@else @foreach($projectSteps as $step)<div class="project-step-item d-flex align-items-center justify-content-between" draggable="true" data-step-id="{{ $step->id }}"><div class="d-flex align-items-center gap-3"><span class="text-muted jm-cursor-grab">&#9776;</span><input class="form-check-input js-step-toggle" type="checkbox" data-url="{{ route('dashboard.project_steps.toggle', $step) }}" {{ $step->is_completed ? 'checked' : '' }}><span class="js-step-title {{ $step->is_completed ? 'text-decoration-line-through text-muted' : '' }}">{{ $step->title }}</span></div><small class="text-muted js-step-completed-at">@if($step->is_completed && $step->completed_at) Done {{ $step->completed_at->diffForHumans() }} @endif</small></div>@endforeach @endif</div></div><div class="modal-footer d-flex justify-content-between"><button type="button" class="btn btn-primary" data-bs-target="#addProjectStepsModal" data-bs-toggle="modal">Add Project Steps</button><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button></div></div></div></div>
<div class="modal fade" id="addProjectStepsModal" tabindex="-1"><div class="modal-dialog"><div class="modal-content"><form method="POST" action="{{ route('dashboard.project_steps.store') }}">@csrf<div class="modal-header"><h5 class="modal-title">Add Project Steps</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div><div class="modal-body"><div id="projectStepsInputList"><div class="input-group mb-2"><input type="text" class="form-control" name="steps[]" maxlength="255" placeholder="Enter project step" required><button type="button" class="btn btn-outline-danger remove-step-input" disabled>Remove</button></div></div><button type="button" class="btn btn-sm btn-outline-success" id="addAnotherStepBtn">+ Add Another Step</button></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-success">Save Steps</button></div></form></div></div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var csrfToken = @json(csrf_token());
    var stepsContainer = document.getElementById('projectStepsInputList');
    var addStepButton = document.getElementById('addAnotherStepBtn');
    var projectStepsList = document.getElementById('projectStepsList');
    var reorderUrl = @json(route('dashboard.project_steps.reorder'));
    if (!stepsContainer || !addStepButton) return;
    addStepButton.addEventListener('click', function () { var row = document.createElement('div'); row.className = 'input-group mb-2'; row.innerHTML = '<input type="text" class="form-control" name="steps[]" maxlength="255" placeholder="Enter project step" required><button type="button" class="btn btn-outline-danger remove-step-input">Remove</button>'; stepsContainer.appendChild(row); stepsContainer.querySelectorAll('.remove-step-input').forEach(function(b){ b.disabled = false; }); });
    stepsContainer.addEventListener('click', function (e) { if (!e.target.classList.contains('remove-step-input')) return; var r = e.target.closest('.input-group'); if (r) { r.remove(); if (stepsContainer.querySelectorAll('.input-group').length === 1) stepsContainer.querySelector('.remove-step-input').disabled = true; } });
    if (projectStepsList) {
        projectStepsList.addEventListener('change', async function (e) { var cb = e.target; if (!cb.classList.contains('js-step-toggle')) return; var row = cb.closest('.project-step-item'); var title = row ? row.querySelector('.js-step-title') : null; var completedAt = row ? row.querySelector('.js-step-completed-at') : null; try { var res = await fetch(cb.dataset.url, { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ is_completed: cb.checked ? 1 : 0 }) }); if (!res.ok) throw new Error(); var payload = await res.json(); if (title) { title.classList.toggle('text-decoration-line-through', !!payload.step && payload.step.is_completed); title.classList.toggle('text-muted', !!payload.step && payload.step.is_completed); } if (completedAt) completedAt.textContent = (payload.step && payload.step.is_completed && payload.step.completed_at_human) ? 'Done ' + payload.step.completed_at_human : ''; } catch (err) { cb.checked = !cb.checked; } });
        var draggedRow = null, originalOrder = [];
        function getOrder() { return Array.from(projectStepsList.querySelectorAll('.project-step-item[data-step-id]')).map(function(r){ return +r.dataset.stepId; }); }
        projectStepsList.addEventListener('dragstart', function (e) { var r = e.target.closest('.project-step-item[data-step-id]'); if (r) { draggedRow = r; originalOrder = getOrder(); r.classList.add('opacity-50'); e.dataTransfer.effectAllowed = 'move'; } });
        projectStepsList.addEventListener('dragover', function (e) { e.preventDefault(); var target = e.target.closest('.project-step-item[data-step-id]'); if (target && draggedRow && target !== draggedRow) { var rect = target.getBoundingClientRect(); projectStepsList.insertBefore(draggedRow, e.clientY < rect.top + rect.height/2 ? target : target.nextSibling); } });
        projectStepsList.addEventListener('dragend', async function () { if (!draggedRow) return; draggedRow.classList.remove('opacity-50'); var newOrder = getOrder(); draggedRow = null; if (JSON.stringify(originalOrder) === JSON.stringify(newOrder) || !newOrder.length) return; try { await fetch(reorderUrl, { method: 'PATCH', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ steps: newOrder }) }); } catch (err) { originalOrder.forEach(function(id){ var el = projectStepsList.querySelector(\"[data-step-id=\\\"\"+id+\"\\\"]\"); if (el) projectStepsList.appendChild(el); }); } });
    }
});
</script>
@endsection
