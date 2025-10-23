// resources/js/bootstrap.js
window._ = require('lodash');

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Importar Alpine.js si no lo tienes
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
