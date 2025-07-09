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

    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        const themeIcon = themeToggle.querySelector('ion-icon');
        const applyTheme = (theme) => {
            document.documentElement.classList.toggle('dark', theme === 'dark');
            if (themeIcon) {
                themeIcon.setAttribute('name', theme === 'dark' ? 'sunny-outline' : 'moon-outline');
            }
        };
        const currentTheme = localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(currentTheme);
        addTrackedEventListener(themeToggle, 'click', () => {
            const newTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            applyTheme(newTheme);
        }, eventListeners);
    }

    const notificationsToggle = document.getElementById('notifications-toggle');
    const notificationsSidebar = document.getElementById('notifications-sidebar');
    const closeNotifications = document.getElementById('close-notifications');
    if (notificationsToggle && notificationsSidebar) {
        addTrackedEventListener(notificationsToggle, 'click', (e) => {
            e.stopPropagation();
            notificationsSidebar.classList.remove('translate-x-full');
        }, eventListeners);
    }
    if (closeNotifications && notificationsSidebar) {
        addTrackedEventListener(closeNotifications, 'click', () => notificationsSidebar.classList.add('translate-x-full'), eventListeners);
    }
    if (notificationsSidebar) {
        addTrackedEventListener(document, 'click', (e) => {
            if (!notificationsSidebar.contains(e.target) && !e.target.closest('#notifications-toggle')) {
                notificationsSidebar.classList.add('translate-x-full');
            }
        }, eventListeners);
    }
}

document.addEventListener('DOMContentLoaded', initializeSharedLayout);
document.addEventListener('livewire:navigated', initializeSharedLayout);
window.addEventListener('beforeunload', () => {
    if (window.cleanupSharedLayout) window.cleanupSharedLayout();
});
