export default {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            enabled: false,
        },
    },
    scales: {
        x: {
            ticks: {
                callback: function (value, index) {
                    // Show tick only if its index is odd
                    return index % 2 !== 0 ? this.getLabelForValue(value) : "";
                },
            },
            grid: {
                display: false,
            },
        },
        y: {
            grid: {
                display: false,
            },
            ticks: {
                min: 0,
                beginAtZero: true,
                callback: function (value, index, values) {
                    if (Math.floor(value) === value) {
                        return value;
                    }
                },
            },
        },
    },
};
