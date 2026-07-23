var allOrders = [];
var dailyChart = null;
var statusChart = null;
var topFoodsChart = null;

function loadStats() {
    $.ajax({
        url: '../api/dashboard.php',
        method: 'GET',
        data: { action: 'get_stats' },
        success: function(res) {
            if (!res.success) return;
            $('#metricTotalOrders').text(res.totalOrders.toLocaleString());
            $('#metricTotalRevenue').text('$' + res.totalRevenue.toLocaleString(undefined, {minimumFractionDigits: 2}));
            $('#metricActiveTables').text(res.activeTables);
            $('#metricPopularFood').text(res.popularFood);
            $('#todayRevenue').text('$' + res.todayRevenue.toLocaleString(undefined, {minimumFractionDigits: 2}));
            $('#todayOrders').text(res.todayOrders);
        }
    });
}

function loadDailyChart() {
    $.ajax({
        url: '../api/dashboard.php',
        method: 'GET',
        data: { action: 'get_daily' },
        success: function(res) {
            if (!res.success) return;
            var ctx = document.getElementById('dailyChart').getContext('2d');
            if (dailyChart) dailyChart.destroy();
            dailyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: res.labels,
                    datasets: [
                        {
                            label: 'Orders',
                            data: res.orders,
                            borderColor: '#6750A4',
                            backgroundColor: 'rgba(103,80,164,0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#6750A4',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Revenue ($)',
                            data: res.revenues,
                            borderColor: '#7D5260',
                            backgroundColor: 'rgba(125,82,96,0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#7D5260',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: 'index' },
                    plugins: { legend: { labels: { usePointStyle: true, padding: 16 } } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            position: 'left',
                            grid: { color: 'rgba(0,0,0,0.06)' },
                            ticks: { stepSize: 1 }
                        },
                        y1: {
                            beginAtZero: true,
                            position: 'right',
                            grid: { drawOnChartArea: false },
                            ticks: { callback: function(v) { return '$' + v; } }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    });
}

function loadStatusChart() {
    $.ajax({
        url: '../api/dashboard.php',
        method: 'GET',
        data: { action: 'get_status_breakdown' },
        success: function(res) {
            if (!res.success || res.data.length === 0) return;
            var ctx = document.getElementById('statusChart').getContext('2d');
            if (statusChart) statusChart.destroy();
            var colorMap = {
                'Pending': '#FFB74D',
                'Completed': '#81C784',
                'Paid': '#6750A4'
            };
            var colors = res.labels.map(function(l) { return colorMap[l] || '#B0BEC5'; });
            statusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: res.labels,
                    datasets: [{
                        data: res.data,
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#fff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }
                    }
                }
            });
        }
    });
}

function loadTop() {
    $.ajax({
        url: '../api/dashboard.php',
        method: 'GET',
        data: { action: 'get_top' },
        success: function(res) {
            if (!res.success) return;

            // Horizontal bar chart
            var ctx = document.getElementById('topFoodsChart').getContext('2d');
            if (topFoodsChart) topFoodsChart.destroy();
            if (res.foods.length === 0) return;

            var labels = res.foods.map(function(f) { return f.food_name; });
            var orders = res.foods.map(function(f) { return parseInt(f.order_count); });
            var revenues = res.foods.map(function(f) { return parseFloat(f.revenue); });

            topFoodsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Orders',
                            data: orders,
                            backgroundColor: 'rgba(103,80,164,0.8)',
                            borderRadius: 4,
                            barThickness: 20
                        },
                        {
                            label: 'Revenue ($)',
                            data: revenues,
                            backgroundColor: 'rgba(125,82,96,0.6)',
                            borderRadius: 4,
                            barThickness: 20
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { labels: { usePointStyle: true, padding: 16 } } },
                    scales: {
                        y: { grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { callback: function(v) { return '$' + v; } } },
                        x: { grid: { display: false } }
                    }
                }
            });

            // Top performance sidebar (top 3)
            var $perf = $('#topPerformance');
            $perf.empty();
            var top3 = res.foods.slice(0, 3);
            var icons = ['bg-primary-container/40 text-on-primary-container', 'bg-tertiary-container/20 text-tertiary', 'bg-secondary-fixed/40 text-on-secondary-container'];
            top3.forEach(function(f, i) {
                $perf.append(
                    '<div class="flex items-center gap-3 p-2.5 rounded-lg bg-surface-container">' +
                        '<div class="w-10 h-10 rounded-lg ' + (icons[i] || icons[0]) + ' flex items-center justify-center shrink-0">' +
                            '<span class="material-symbols-outlined text-lg">restaurant</span>' +
                        '</div>' +
                        '<div class="flex-1 min-w-0">' +
                            '<p class="text-body-md font-semibold text-on-surface truncate">' + f.food_name + '</p>' +
                            '<p class="text-body-sm text-on-surface-variant">' + f.order_count + ' orders</p>' +
                        '</div>' +
                        '<span class="text-body-sm font-semibold text-primary font-data-mono">$' + parseFloat(f.revenue).toLocaleString(undefined, {minimumFractionDigits: 2}) + '</span>' +
                    '</div>'
                );
            });
        }
    });
}

function loadRecent() {
    $.ajax({
        url: '../api/dashboard.php',
        method: 'GET',
        data: { action: 'get_recent' },
        success: function(res) {
            var $tbody = $('#recentOrdersBody');
            if (!res.success || res.orders.length === 0) {
                $tbody.html('<tr><td colspan="6" class="px-5 py-8 text-center text-on-surface-variant text-body-sm">No orders yet</td></tr>');
                return;
            }
            $tbody.empty();
            var statusClasses = {
                'Pending': 'bg-warning-container/30 text-on-warning-container',
                'Completed': 'bg-success-container text-on-success-container',
                'Paid': 'bg-primary-container/60 text-on-primary-container'
            };
            res.orders.forEach(function(o) {
                var items = o.items.map(function(it) { return it.food_name + ' x' + it.quantity; }).join(', ');
                var dt = new Date(o.order_date);
                var time = dt.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
                var badge = statusClasses[o.status] || 'bg-surface-container text-on-surface-variant';
                $tbody.append(
                    '<tr class="hover:bg-surface-container/50 transition-colors">' +
                        '<td class="px-5 py-3 text-body-sm font-data-mono text-on-surface">#' + o.order_id + '</td>' +
                        '<td class="px-5 py-3 text-body-sm text-on-surface">' + o.table_name + '</td>' +
                        '<td class="px-5 py-3 text-body-sm text-on-surface-variant max-w-[200px] truncate">' + items + '</td>' +
                        '<td class="px-5 py-3"><span class="text-body-xs font-semibold px-2.5 py-1 rounded-full ' + badge + '">' + o.status + '</span></td>' +
                        '<td class="px-5 py-3 text-body-sm font-semibold text-on-surface font-data-mono">$' + parseFloat(o.total_amount).toLocaleString(undefined, {minimumFractionDigits: 2}) + '</td>' +
                        '<td class="px-5 py-3 text-body-sm text-on-surface-variant">' + time + '</td>' +
                    '</tr>'
                );
            });
        }
    });
}

$(function() {
    loadStats();
    loadDailyChart();
    loadStatusChart();
    loadTop();
    loadRecent();
});