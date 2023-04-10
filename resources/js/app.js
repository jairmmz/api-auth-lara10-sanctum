import './bootstrap';

import { createApp } from 'vue';
import ClientsGrid from './components/ClientsGrid.vue';

const app = createApp();

app.component('clients-grid', ClientsGrid);

app.mount('#app');


