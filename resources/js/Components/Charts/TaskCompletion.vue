<template>
    <div>
        <canvas id="taskCompletionChart"></canvas>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
    taskCounts: {
        type: Object,
        required: true,
    },
});

const chartRef = ref(null);

onMounted(() => {
    const ctx = document.getElementById('taskCompletionChart').getContext('2d');
    chartRef.value = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Open', 'In Progress', 'Done'],
            datasets: [
                {
                    label: 'Tasks by Status',
                    backgroundColor: ['#3b82f6', '#f59e0b', '#10b981'],
                    data: [props.taskCounts.open, props.taskCounts.in_progress, props.taskCounts.done],
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                title: {
                    display: true,
                    text: 'Task Completion by Status'
                }
            }
        }
    });
});

watch(props.taskCounts, (newCounts) => {
    if (chartRef.value) {
        chartRef.value.data.datasets[0].data = [newCounts.open, newCounts.in_progress, newCounts.done];
        chartRef.value.update();
    }
});
</script>