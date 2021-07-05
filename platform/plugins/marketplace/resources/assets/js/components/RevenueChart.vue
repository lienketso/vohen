<template>
    <div>
        <div class="revenue-chart"></div>
    </div>
</template>

<script>

export default {
    data: () => {
        return {
            isLoading: true,
        };
    },
    props: {
        data: {
            type: Array,
            default: () => [],
            required: true
        },
    },
    mounted: function () {
        if (!this.data.length) {
            return;
        }
        let series = [];
        const labels = [];
        let total = 0;

        this.data.map((x) => {
            total += parseFloat(x.value)
            labels.push(x.label);
        });
        if (total == 0) {
            this.data.map(() => {
                series.push(100 / this.data.length);
            });
        } else {
            this.data.map((x) => {
                series.push(100 / total * parseFloat(x.value));
            });
        }

        new ApexCharts(this.$el.querySelector('.revenue-chart'), {
            series: series,
            chart: {height: '250', type: 'donut'},
            chartOptions: {labels: labels},
            plotOptions: {pie: {donut: {size: '71%', polygons: {strokeWidth: 0}}, expandOnClick: false}},
            states: {hover: {filter: {type: 'darken', value: .9}}},
            dataLabels: {enabled: false},
            legend: {show: false},
            tooltip: {enabled: false}
        }).render();

    },
}
</script>
