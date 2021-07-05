import OrderInMonthChart from './components/OrderInMonthChart';
import RevenueChart from './components/RevenueChart';

import Vue from 'vue';

Vue.component('order-in-month-chart', OrderInMonthChart);
Vue.component('revenue-chart', RevenueChart);

const app = new Vue({
    el: '#vendor-dashboard'
});
