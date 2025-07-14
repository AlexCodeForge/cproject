// This file is intentionally left blank.
// Livewire 3 automatically includes AlpineJS.
// Shared JavaScript logic is in `shared-layout.js`.

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
import './shared-layout';
import.meta.glob([
    '../images/**',
    '../fonts/**',
]);

// Add debugging for Echo connection
console.log('🚀 App.js loaded');
console.log('🔗 Echo instance:', window.Echo);

// Test Echo connection
window.Echo.connector.pusher.connection.bind('state_change', function(states) {
    console.log('🔄 Echo connection state change:', states);
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ Echo connected successfully');
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.log('❌ Echo disconnected');
});

window.Echo.connector.pusher.connection.bind('error', function(error) {
    console.error('❌ Echo connection error:', error);
});

// Test authentication
window.Echo.connector.pusher.connection.bind('auth:fail', function(error) {
    console.error('❌ Echo authentication failed:', error);
});

window.Echo.connector.pusher.connection.bind('auth:success', function() {
    console.log('✅ Echo authentication successful');
});
