import './echo';

import { createApp } from 'vue';

const appElement = document.getElementById('app');

if (appElement) {
    createApp({}).mount('#app');
}

console.log('App initialized');
console.log('Echo loaded:', window.Echo);