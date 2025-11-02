// Vite entry JS
import 'bootstrap';
// import SB Admin 2
import '/sb-admin-2/js/sb-admin-2.min.js';
// DataTables & Chart.js
import 'datatables.net-bs5';
import Chart from 'chart.js/auto';
window.Chart = Chart;
// Inisialisasi global jQuery jika SB Admin gunakan jQuery
import $ from 'jquery';
window.$ = window.jQuery = $;
