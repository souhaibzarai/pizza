<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Sales Overview (Last 7 Days)</h6>
    </div>
    <div class="card-body">
        <canvas id="salesChart" width="100%" height="25"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels:                                       <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Total Sales (DH)',
                data:                                           <?php echo json_encode($datasets); ?>,
                backgroundColor: 'rgba(78, 115, 223, 0.2)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                x: {
                    beginAtZero: false
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>