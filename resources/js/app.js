import 'bootstrap/dist/css/bootstrap.min.css'; // ✅ Ensure Bootstrap CSS is included
import * as bootstrap from 'bootstrap'; // ✅ Ensure Bootstrap JavaScript is imported globally

window.bootstrap = bootstrap; // ✅ Make Bootstrap available globally

import '../css/andrei.css';
import '../css/login.css';

import { createApp } from 'vue';
import { initCharts } from './charts/initCharts';

// Import other components
import VueDatepickerNext from './components/DatePicker.vue';

function setupCurrencySync() {
    const selects = Array.from(document.querySelectorAll('select.js-currency-select[data-currency-group][data-currency-hidden]'));
    if (!selects.length) return;

    const groups = new Map();
    for (const select of selects) {
        const group = select.getAttribute('data-currency-group');
        const hiddenId = select.getAttribute('data-currency-hidden');
        if (!group || !hiddenId) continue;

        if (!groups.has(group)) {
            groups.set(group, { hiddenId, selects: [] });
        }
        groups.get(group).selects.push(select);
    }

    for (const { hiddenId, selects: groupSelects } of groups.values()) {
        const hiddenInput = document.getElementById(hiddenId);
        if (!hiddenInput) continue;

        const applyValue = (value) => {
            hiddenInput.value = value;
            for (const s of groupSelects) s.value = value;
        };

        const initialValue = hiddenInput.value || groupSelects[0]?.value;
        if (initialValue) applyValue(initialValue);

        for (const s of groupSelects) {
            s.addEventListener('change', () => applyValue(s.value));
        }
    }
}

// App pentru DatePicker
const datePicker = createApp({});
datePicker.component('vue-datepicker-next', VueDatepickerNext);
if (document.getElementById('datePicker') != null) {
    datePicker.mount('#datePicker');
}

document.addEventListener('DOMContentLoaded', () => {
    setupCurrencySync();
    initCharts();
});
