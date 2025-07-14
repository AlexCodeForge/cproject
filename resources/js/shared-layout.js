import Chart from 'chart.js/auto';
import 'chartjs-adapter-date-fns';

window.Chart = Chart;

function addTrackedEventListener(element, event, handler, eventListenersMap) {
    if (!element) return;
    const key = `${event}-${element.id || element.tagName}`;
    if (eventListenersMap.has(key)) {
        const oldListener = eventListenersMap.get(key);
        element.removeEventListener(oldListener.event, oldListener.handler);
    }
    element.addEventListener(event, handler);
    eventListenersMap.set(key, { event, handler, element });
}

function initializeSharedLayout() {
    if (window.cleanupSharedLayout) {
        window.cleanupSharedLayout();
    }
    const eventListeners = new Map();
    let sidebarTimer = null;

    window.cleanupSharedLayout = () => {
        eventListeners.forEach(listener => {
            listener.element.removeEventListener(listener.event, listener.handler);
        });
        eventListeners.clear();
        if (sidebarTimer) {
            clearTimeout(sidebarTimer);
        }
    };

    const sidebar = document.getElementById('sidebar');
    if (sidebar) {
        sidebar.classList.add('is-expanded');
        sidebar.classList.remove('is-collapsed');

        sidebarTimer = setTimeout(() => {
            if (!sidebar.matches(':hover')) {
                sidebar.classList.replace('is-expanded', 'is-collapsed');
            }
        }, 1000);

        addTrackedEventListener(sidebar, 'mouseenter', () => {
            clearTimeout(sidebarTimer);
            sidebar.classList.replace('is-collapsed', 'is-expanded');
        }, eventListeners);

        addTrackedEventListener(sidebar, 'mouseleave', () => {
            sidebar.classList.replace('is-expanded', 'is-collapsed');
        }, eventListeners);
    }

    // Listen for theme-toggled event from Livewire component
    window.addEventListener('theme-toggled', event => {
        // console.log('Theme toggled event received:', event.detail);
        document.documentElement.classList.toggle('dark', event.detail[0] === 'dark');
    });
}

document.addEventListener('DOMContentLoaded', initializeSharedLayout);
document.addEventListener('livewire:navigated', initializeSharedLayout);
window.addEventListener('beforeunload', () => {
    if (window.cleanupSharedLayout) window.cleanupSharedLayout();
});
