// Vite entry JS
import 'bootstrap';
import './bootstrap';
// DataTables & Chart.js
import 'datatables.net-bs5';
import Chart from 'chart.js/auto';
window.Chart = Chart;
// Inisialisasi global jQuery jika SB Admin gunakan jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;
