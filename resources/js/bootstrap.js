/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// import axios from 'axios';
// import Echo from 'laravel-echo';

// window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     host: '0.0.0.0:8080',
// });

import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';


window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: `127.0.0.1:6001`, // or use your actual Reverb host/port
});
