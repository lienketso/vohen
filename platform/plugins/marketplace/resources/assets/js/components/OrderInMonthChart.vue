<template>
    <div>
        <div class="orders-in-month-chart"></div>
        <div class="row" v-if="earningSales.length">
            <div class="col-12">
                <ul>
                    <li v-for="earningSale in earningSales" v-bind:key="earningSale.text">
                        <i class="fas fa-circle" v-bind:style="{ color: earningSale.color }"></i> {{ earningSale.text }}
                    </li>
                </ul>
            </div>
        </div>
        <!-- loading in here -->
        <div class="loading"></div>
    </div>
</template>

<script>

export default {
    data: () => {
        return {
            isLoading: true,
            earningSales: [],
            colors: ['#fcb800', '#80bc00'],
        };
    },
    props: {
        url: {
            type: String,
            default: null,
            required: true
        },
        format: {
            type: String,
            default: 'dd/MM/yy',
            required: false
        },
    },
    mounted: function () {
        if (this.url) {
            axios.get(this.url)
                .then(res => {
                    if (res.data.error) {
                        Botble.showError(res.data.message);
                    } else {
                        this.earningSales = res.data.data.earningSales;
                        new ApexCharts(this.$el.querySelector('.orders-in-month-chart'), {
                            series: res.data.data.series,
                            chart: {height: 350, type: 'area', toolbar: {show: false}},
                            dataLabels: {enabled: false},
                            stroke: {curve: 'smooth'},
                            colors: res.data.data.colors,
                            xaxis: {
                                type: 'datetime',
                                categories: res.data.data.dates
                            },
                            tooltip: {x: {format: this.format}}
                        }).render();
                    }
                });
        }
    },
}
</script>
