import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: 'srhvlhu5urjglevkbufl',
    wsHost: 'localhost',
    wsPort: 9090,
    wssPort: 9090,
    forceTLS: false,
    enabledTransports: ['ws', 'wss'],
});
