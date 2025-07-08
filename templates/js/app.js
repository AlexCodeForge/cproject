document.addEventListener('DOMContentLoaded', function () {
    console.log('🎉 DOM Content Loaded - JavaScript is running!');
    
    // --- Context Detection ---
    const isAdminContext = window.location.pathname.includes('admin-') || document.querySelector('#admin-nav');
    const isUserContext = !isAdminContext;
    console.log('🏠 Context detected:', isAdminContext ? 'Admin Panel' : 'User Panel');
    
    // --- Element Selectors (with null safety) ---
    const sidebar = document.getElementById('sidebar');
    const adminModeToggle = document.getElementById('admin-mode-toggle');
    const mainNav = document.getElementById('main-nav');
    const adminNav = document.getElementById('admin-nav');
    const sections = document.querySelectorAll('.section');
    const allNavItems = document.querySelectorAll('.nav-item');
    const mobileNavItems = document.querySelectorAll('.mobile-nav-item');
    const courseCards = document.querySelectorAll('.course-card');
    const backToCoursesButton = document.getElementById('back-to-courses');
    const notificationsToggle = document.getElementById('notifications-toggle');
    const notificationsSidebar = document.getElementById('notifications-sidebar');
    const closeNotifications = document.getElementById('close-notifications');
    const feedContainer = document.getElementById('feed-container');
    const feedListViewBtn = document.getElementById('feed-list-view');
    const feedGridViewBtn = document.getElementById('feed-grid-view');
    const feedItems = document.querySelectorAll('.feed-item');
    const backToFeedButton = document.getElementById('back-to-feed');
    const feedFilterBtns = document.querySelectorAll('.feed-filter-btn');
    const profileLink = document.querySelector('.user-profile-link');
    const themeToggle = document.getElementById('theme-toggle');
    let isAdminMode = false;
    let chartsInitialized = false;
    let isDarkMode = false;

    // --- PERFORMANCE OPTIMIZATIONS ---

    // Cache DOM elements to avoid repeated queries
    const domCache = {
        sections: new Map(),
        navItems: new Map(),
        mobileNavItems: new Map()
    };

    // Initialize DOM cache
    function initDOMCache() {
        sections.forEach(section => {
            domCache.sections.set(section.id, section);
        });
        
        allNavItems.forEach(item => {
            const sectionId = item.getAttribute('data-section');
            if (sectionId) {
                if (!domCache.navItems.has(sectionId)) {
                    domCache.navItems.set(sectionId, []);
                }
                domCache.navItems.get(sectionId).push(item);
            }
        });
        
        mobileNavItems.forEach(item => {
            const sectionId = item.getAttribute('data-section');
            if (sectionId) {
                if (!domCache.mobileNavItems.has(sectionId)) {
                    domCache.mobileNavItems.set(sectionId, []);
                }
                domCache.mobileNavItems.get(sectionId).push(item);
            }
        });
    }

    // --- MAIN SECTION SWITCHING FUNCTION ---
    function showSection(sectionId) {
        console.log('🔄 showSection called with:', sectionId);
        
        // Check if we're in a multi-page context (separate HTML files)
        const currentPage = window.location.pathname.split('/').pop().replace('.html', '');
        const targetPage = sectionId;
        
        console.log('📄 Current page:', currentPage);
        console.log('📄 Target page:', targetPage);
        
        // If target section is not the current page, navigate to the correct HTML file
        if (targetPage !== currentPage && targetPage !== 'dashboard') {
            const targetFile = `${targetPage}.html`;
            console.log('🔗 Navigating to:', targetFile);
            window.location.href = targetFile;
            return;
        }
        
        // For same-page sections or if we're in single-page mode
        console.log('📋 Available sections:', sections.length);
        
        // Hide all sections
        sections.forEach(section => {
            console.log('🔄 Hiding section:', section.id);
            section.classList.remove('active');
        });
        
        // Show target section
        const targetSection = document.getElementById(sectionId);
        console.log('🎯 Target section found:', targetSection ? targetSection.id : 'NOT FOUND');
        
        if (targetSection) {
            targetSection.classList.add('active');
            console.log('✅ Added active class to:', sectionId);
        } else {
            console.log('ℹ️ Section not found in current page - this is normal for multi-page setup');
        }
        
        // Initialize charts if needed
        if (sectionId === 'alerts' && !chartsInitialized) {
            setTimeout(() => initAlertCharts(), 50);
        }
        
        // Update navigation states
        updateNavigationState(sectionId);
    }

    // Update navigation state
    function updateNavigationState(sectionId) {
        // Get current page from URL for multi-page context
        const currentPage = window.location.pathname.split('/').pop().replace('.html', '') || 'dashboard';
        const targetPage = sectionId || currentPage;
        
        console.log('🎨 Updating nav state for:', targetPage);
        
        // Reset all navigation items to default state
        allNavItems.forEach(item => {
            // Remove all active state classes
            item.classList.remove('bg-slate-700', 'text-white', 'shadow-lg', 'active');
            item.classList.remove('bg-stone-200', 'dark:bg-gray-700', 'text-slate-800', 'dark:text-gray-200');
            
            // Add default state classes
            item.classList.add('text-slate-500', 'dark:text-gray-400');
            item.classList.add('hover:bg-stone-200', 'dark:hover:bg-gray-700');
            item.classList.add('hover:text-slate-800', 'dark:hover:text-gray-200');
        });
        
        // Set active navigation item - check both data-section and href
        let activeItem = document.querySelector(`[data-section="${targetPage}"]`);
        if (!activeItem) {
            // Try to find by href for multi-page navigation
            activeItem = document.querySelector(`[href="${targetPage}.html"]`);
        }
        
        if (activeItem && activeItem.classList.contains('nav-item')) {
            // Remove default state classes
            activeItem.classList.remove('text-slate-500', 'dark:text-gray-400');
            activeItem.classList.remove('hover:bg-stone-200', 'dark:hover:bg-gray-700');
            activeItem.classList.remove('hover:text-slate-800', 'dark:hover:text-gray-200');
            
            // Add active state classes (consistent with template HTML)
            activeItem.classList.add('active', 'text-slate-800', 'dark:text-gray-200');
            activeItem.classList.add('bg-stone-200', 'dark:bg-gray-700');
            console.log('✅ Set active nav item:', activeItem);
        }
        
        // Update mobile navigation
        mobileNavItems.forEach(item => {
            const span = item.querySelector('span');
            const itemSection = item.getAttribute('data-section');
            const itemHref = item.getAttribute('href');
            const isActive = itemSection === targetPage || (itemHref && itemHref.includes(targetPage));
            
            item.classList.toggle('text-slate-700', isActive);
            item.classList.toggle('dark:text-gray-200', isActive);
            item.classList.toggle('text-slate-500', !isActive);
            item.classList.toggle('dark:text-gray-400', !isActive);
            
            if (span) {
                span.classList.toggle('font-bold', isActive);
            }
        });
    }

    // --- SIDEBAR HOVER ---
    sidebar.addEventListener('mouseenter', () => {
        sidebar.classList.add('is-expanded');
    });

    sidebar.addEventListener('mouseleave', () => {
        sidebar.classList.remove('is-expanded');
    });

    // --- THEME TOGGLE ---
    const toggleTheme = () => {
        console.log('🌙 Theme toggle clicked! Current mode:', isDarkMode ? 'dark' : 'light');
        isDarkMode = !isDarkMode;
        document.documentElement.classList.toggle('dark', isDarkMode);
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
        
        const icon = themeToggle.querySelector('ion-icon');
        icon.setAttribute('name', isDarkMode ? 'sunny-outline' : 'moon-outline');
        
        themeToggle.classList.toggle('bg-slate-700', isDarkMode);
        themeToggle.classList.toggle('text-white', isDarkMode);
        console.log('✅ Theme switched to:', isDarkMode ? 'dark' : 'light');
    };

    // Initialize theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        toggleTheme();
    }

    console.log('🔄 Theme toggle element:', themeToggle);
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
        console.log('✅ Theme toggle listener added');
    } else {
        console.error('❌ Theme toggle element not found');
    }

    // --- ADMIN MODE TOGGLE (only in user context) ---
    if (adminModeToggle && isUserContext) {
        adminModeToggle.addEventListener('click', () => {
            isAdminMode = !isAdminMode;
            if (mainNav) mainNav.classList.toggle('hidden');
            if (adminNav) {
                adminNav.classList.toggle('hidden');
                adminNav.classList.toggle('flex');
            }
            adminModeToggle.classList.toggle('bg-slate-700');
            adminModeToggle.classList.toggle('text-white');
            showSection(isAdminMode ? 'admin-dashboard' : 'dashboard');
        });
        console.log('✅ Admin mode toggle listener added (user context)');
    } else if (isAdminContext) {
        console.log('ℹ️ Admin mode toggle not needed in admin context');
    }

    // --- NAVIGATION EVENT LISTENERS ---
    console.log('🔄 Setting up navigation listeners for', allNavItems.length, 'items');
    allNavItems.forEach((item, index) => {
        const sectionId = item.getAttribute('data-section');
        const href = item.getAttribute('href');
        console.log(`📌 Nav item ${index}:`, sectionId, 'href:', href);
        
        item.addEventListener('click', (e) => {
            // Handle different types of navigation
            if (sectionId) {
                // Single-page app navigation (data-section attribute)
                e.preventDefault();
                console.log('🖱️ SPA nav item clicked:', sectionId);
                showSection(sectionId);
            } else if (href && href.endsWith('.html')) {
                // Multi-page navigation (href to HTML file)
                console.log('🔗 Multi-page link clicked:', href);
                // Let the browser handle the navigation naturally
            } else {
                console.log('🔗 Regular link clicked, allowing default behavior');
            }
        });
    });

    if (profileLink) {
        profileLink.addEventListener('click', (e) => {
            e.preventDefault();
            showSection(profileLink.getAttribute('data-section'));
        });
    }

    mobileNavItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            const sectionId = item.getAttribute('data-section');
            if (sectionId) {
                showSection(sectionId);
            }
        });
    });

    // Course functionality (only if elements exist)
    if (courseCards.length > 0) {
        courseCards.forEach(card => {
            card.addEventListener('click', () => showSection('course-view'));
        });
        console.log('✅ Course card listeners added');
    }

    if (backToCoursesButton) {
        backToCoursesButton.addEventListener('click', () => showSection('courses'));
        console.log('✅ Back to courses listener added');
    }

    // --- NOTIFICATIONS PANEL ---
    let notificationTimeout;
    const openNotificationPanel = () => {
        clearTimeout(notificationTimeout);
        notificationTimeout = setTimeout(() => {
            notificationsSidebar.classList.remove('translate-x-full');
            document.addEventListener('click', handleDocumentClickForNotifications, { passive: true });
        }, 10);
    };

    const closeNotificationPanel = () => {
        clearTimeout(notificationTimeout);
        notificationsSidebar.classList.add('translate-x-full');
        document.removeEventListener('click', handleDocumentClickForNotifications);
    };

    const handleDocumentClickForNotifications = (e) => {
        if (!notificationsSidebar.contains(e.target) && !notificationsToggle.contains(e.target)) {
            closeNotificationPanel();
        }
    };

    // Add null checks and debugging for notifications
    console.log('🔔 Notifications elements check:');
    console.log('  - notificationsToggle:', notificationsToggle);
    console.log('  - notificationsSidebar:', notificationsSidebar);
    console.log('  - closeNotifications:', closeNotifications);
    
    if (notificationsToggle && notificationsSidebar) {
        notificationsToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            console.log('🔔 Notifications toggle clicked!');
            const isHidden = notificationsSidebar.classList.contains('translate-x-full');
            console.log('🔔 Sidebar is hidden:', isHidden);
            if (isHidden) {
                openNotificationPanel();
            } else {
                closeNotificationPanel();
            }
        });
        console.log('✅ Notifications toggle listener added');
    } else {
        console.error('❌ Notifications toggle or sidebar not found!');
    }

    if (closeNotifications) {
        closeNotifications.addEventListener('click', () => {
            console.log('🔔 Close notifications clicked!');
            closeNotificationPanel();
        });
        console.log('✅ Close notifications listener added');
    } else {
        console.error('❌ Close notifications button not found!');
    }

    // --- FEED FUNCTIONALITY ---
    const setGridView = () => {
        feedContainer.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'xl:grid-cols-3', 'gap-8');
        feedContainer.classList.remove('space-y-8');
        feedItems.forEach(item => {
            item.classList.remove('sm:flex-row');
            item.classList.add('feed-item-grid-view');
            const imageContainer = item.querySelector('.feed-image');
            if (imageContainer) {
                imageContainer.classList.remove('sm:w-1/3');
                imageContainer.classList.add('sm:w-full');
            }
        });
        feedGridViewBtn.classList.add('bg-stone-200', 'text-slate-700');
        feedListViewBtn.classList.remove('bg-stone-200', 'text-slate-700');
    };

    const setListView = () => {
        feedContainer.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-2', 'xl:grid-cols-3', 'gap-8');
        feedContainer.classList.add('space-y-8');
        feedItems.forEach(item => {
            item.classList.add('sm:flex-row');
            item.classList.remove('feed-item-grid-view');
            const imageContainer = item.querySelector('.feed-image');
            if (imageContainer) {
                imageContainer.classList.add('sm:w-1/3');
                imageContainer.classList.remove('sm:w-full');
            }
        });
        feedListViewBtn.classList.add('bg-stone-200', 'text-slate-700');
        feedGridViewBtn.classList.remove('bg-stone-200', 'text-slate-700');
    };

    // Feed functionality (only if elements exist)
    if (feedListViewBtn && feedGridViewBtn) {
        feedListViewBtn.addEventListener('click', setListView);
        feedGridViewBtn.addEventListener('click', setGridView);
        console.log('✅ Feed view toggle listeners added');
    }
    
    if (feedItems.length > 0) {
        feedItems.forEach(item => {
            item.addEventListener('click', () => showSection('post-view'));
        });
        console.log('✅ Feed item listeners added');
    }

    if (backToFeedButton) {
        backToFeedButton.addEventListener('click', () => showSection('feed'));
        console.log('✅ Back to feed listener added');
    }

    feedFilterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const category = btn.getAttribute('data-category');

            feedFilterBtns.forEach(b => {
                b.classList.remove('bg-slate-700', 'text-white');
                b.classList.add('bg-white', 'text-slate-600');
            });
            btn.classList.add('bg-slate-700', 'text-white');
            btn.classList.remove('bg-white', 'text-slate-600');
            
            feedItems.forEach(item => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // --- REMIND ME BUTTONS ---
    document.querySelectorAll('.remind-me-btn').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            
            const icon = button.querySelector('ion-icon');
            const text = button.querySelector('span');
            
            if (button.classList.contains('reminder-set')) {
                button.classList.remove('reminder-set', 'bg-green-600', 'text-white');
                button.classList.add('bg-stone-200', 'text-slate-700');
                icon.setAttribute('name', 'notifications-outline');
                text.textContent = 'Recordarme';
            } else {
                button.classList.add('reminder-set', 'bg-green-600', 'text-white');
                button.classList.remove('bg-stone-200', 'text-slate-700');
                icon.setAttribute('name', 'checkmark-outline');
                text.textContent = 'Agendado';
            }
        });
    });

    // --- CHART INITIALIZATION ---
    function initAlertCharts() {
        if (chartsInitialized) return;
        
        const createChart = (canvasId, data, color) => {
            const ctx = document.getElementById(canvasId);
            if (!ctx) return;
            
            const renderChart = () => {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map((_, i) => new Date(Date.now() - (10 - i) * 24 * 60 * 60 * 1000)),
                        datasets: [{ 
                            data, 
                            borderColor: color, 
                            borderWidth: 2, 
                            pointRadius: 0, 
                            tension: 0.4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true, 
                        maintainAspectRatio: false,
                        animation: { duration: 0 },
                        plugins: { 
                            legend: { display: false }, 
                            tooltip: { enabled: false } 
                        },
                        scales: { 
                            x: { display: false }, 
                            y: { display: false } 
                        },
                        elements: {
                            point: { radius: 0 },
                            line: { tension: 0.4 }
                        }
                    }
                });
            };
            
            if (window.requestIdleCallback) {
                requestIdleCallback(renderChart);
            } else {
                setTimeout(renderChart, 0);
            }
        };
        
        const charts = [
            ['alertChart1', [180, 182, 181, 184, 186, 185, 187, 188, 190, 185.5], '#22c55e'],
            ['alertChart2', [215, 214, 212, 210, 211, 213, 215, 214, 213, 212.3], '#22c55e'],
            ['alertChart3', [125, 124, 123, 122, 121, 120, 119, 121, 122, 120.8], '#ef4444']
        ];
        
        charts.forEach(([id, data, color], index) => {
            setTimeout(() => createChart(id, data, color), index * 16);
        });
        
        chartsInitialized = true;
    }

    // --- MODAL FUNCTIONALITY ---
    
    // Modal state management
    let currentEditingUser = null;
    let currentEditingItem = null;
    
    // Modal elements
    const modals = document.querySelectorAll('.modal');
    const openModalButtons = document.querySelectorAll('.open-modal');
    const closeModalButtons = document.querySelectorAll('.close-modal');
    
    // Sample data for demonstration
    const sampleUsers = [
        {
            id: 1,
            name: 'Robert Fox',
            email: 'robert.fox@example.com',
            role: 'premium',
            status: 'active',
            avatar: 'https://randomuser.me/api/portraits/men/32.jpg'
        },
        {
            id: 2,
            name: 'Jenny Wilson',
            email: 'jenny.wilson@example.com',
            role: 'regular',
            status: 'active',
            avatar: 'https://randomuser.me/api/portraits/women/25.jpg'
        }
    ];

    const samplePnlData = [
        { id: 1, symbol: '$SPY', result: '+150%', amount: 8240, date: '2025-07-03', notes: 'Excellent momentum trade' },
        { id: 2, symbol: '$META', result: '-25%', amount: -1250, date: '2025-07-02', notes: 'Stop loss triggered' }
    ];

    const samplePaymentData = [
        { id: 'ch_3L2f1b...', user: 'Robert Fox', amount: '$29.00', date: '28 Junio, 2024', status: 'completed' },
        { id: 'ch_3L2e9a...', user: 'Jenny Wilson', amount: '$29.00', date: '27 Junio, 2024', status: 'failed' },
        { id: 'ch_3L2d5c...', user: 'Carlos R.', amount: '$278.00', date: '25 Junio, 2024', status: 'completed' }
    ];

    const samplePostData = [
        { id: 1, title: 'El Halving de Bitcoin y su impacto', category: 'crypto', author: 'David Vega', date: '28 Junio, 2024', content: 'Análisis completo del halving...' },
        { id: 2, title: '3 Opciones de compra en el sector tecnológico', category: 'premium', author: 'Ana Torres', date: '26 Junio, 2024', content: 'Estrategias para el sector tech...' }
    ];

    const sampleAlertData = [
        { id: 1, symbol: 'TSLA', type: 'buy', price: 185.50, status: 'active', notes: 'Breakout pattern confirmed' },
        { id: 2, symbol: 'AAPL', type: 'buy', price: 212.30, status: 'active', notes: 'Support level bounce' },
        { id: 3, symbol: 'NVDA', type: 'sell', price: 120.80, status: 'expired', notes: 'Resistance level reached' }
    ];

    const sampleEventData = [
        { id: 1, title: 'Webinar: Análisis Macroeconómico Q3', date: '2025-07-15T14:00', type: 'premium', description: 'Análisis detallado de tendencias macro' },
        { id: 2, title: 'Sesión de Trading en Vivo: Apertura de Mercado', date: '2025-07-08T09:30', type: 'public', description: 'Trading en vivo de apertura' }
    ];
    
    // Open modal functionality
    openModalButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                if (modalId.includes('edit-')) {
                    populateEditModal(modalId);
                }
                
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
    });
    
    // Close modal functionality
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            closeAllModals();
        });
    });
    
    // Close modal when clicking outside
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAllModals();
            }
        });
    });
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });
    
    function closeAllModals() {
        modals.forEach(modal => {
            modal.classList.add('hidden');
        });
        document.body.style.overflow = '';
        currentEditingUser = null;
        currentEditingItem = null;
    }
    
    function populateEditModal(modalId) {
        switch(modalId) {
            case 'edit-user-modal':
                populateUserModal();
                break;
            case 'edit-pnl-modal':
                populatePnlModal();
                break;
            case 'edit-payment-modal':
                populatePaymentModal();
                break;
            case 'edit-post-modal':
                populatePostModal();
                break;
            case 'edit-alert-modal':
                populateAlertModal();
                break;
            case 'edit-event-modal':
                populateEventModal();
                break;
        }
    }
    
    function populateUserModal() {
        const user = sampleUsers[0];
        currentEditingUser = user;
        
        document.getElementById('edit-user-avatar').src = user.avatar;
        document.getElementById('edit-user-name').value = user.name;
        document.getElementById('edit-user-email').value = user.email;
        document.getElementById('edit-user-role').value = user.role;
        document.getElementById('edit-user-status').value = user.status;
    }

    function populatePnlModal() {
        const pnl = samplePnlData[0];
        currentEditingItem = pnl;
        
        document.getElementById('edit-pnl-symbol').value = pnl.symbol;
        document.getElementById('edit-pnl-result').value = pnl.result.replace('%', '').replace('+', '');
        document.getElementById('edit-pnl-amount').value = Math.abs(pnl.amount);
        document.getElementById('edit-pnl-date').value = pnl.date;
        document.getElementById('edit-pnl-notes').value = pnl.notes;
    }

    function populatePaymentModal() {
        const payment = samplePaymentData[0];
        currentEditingItem = payment;
        
        document.getElementById('edit-payment-id').value = payment.id;
        document.getElementById('edit-payment-user').value = payment.user;
        document.getElementById('edit-payment-amount').value = payment.amount;
        document.getElementById('edit-payment-status').value = payment.status;
    }

    function populatePostModal() {
        const post = samplePostData[0];
        currentEditingItem = post;
        
        document.getElementById('edit-post-title').value = post.title;
        document.getElementById('edit-post-category').value = post.category;
        document.getElementById('edit-post-author').value = post.author;
        document.getElementById('edit-post-content').value = post.content;
    }

    function populateAlertModal() {
        const alert = sampleAlertData[0];
        currentEditingItem = alert;
        
        document.getElementById('edit-alert-symbol').value = alert.symbol;
        document.getElementById('edit-alert-type').value = alert.type;
        document.getElementById('edit-alert-price').value = alert.price;
        document.getElementById('edit-alert-status').value = alert.status;
        document.getElementById('edit-alert-notes').value = alert.notes;
    }

    function populateEventModal() {
        const event = sampleEventData[0];
        currentEditingItem = event;
        
        document.getElementById('edit-event-title').value = event.title;
        document.getElementById('edit-event-date').value = event.date;
        document.getElementById('edit-event-type').value = event.type;
        document.getElementById('edit-event-description').value = event.description;
    }
    
    // Form submission handlers
    const forms = document.querySelectorAll('form[id$="-form"]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleFormSubmission(this);
        });
    });
    
    function handleFormSubmission(form) {
        const formId = form.id;
        showNotification(`${getFormActionText(formId)} exitosamente`, 'success');
        closeAllModals();
        form.reset();
    }
    
    function getFormActionText(formId) {
        const actions = {
            'new-user-form': 'Usuario creado',
            'edit-user-form': 'Usuario actualizado',
            'new-pnl-form': 'Registro P&L creado',
            'edit-pnl-form': 'Registro P&L actualizado',
            'new-payment-form': 'Pago creado',
            'edit-payment-form': 'Pago actualizado',
            'new-post-form': 'Publicación creada',
            'edit-post-form': 'Publicación actualizada',
            'new-alert-form': 'Alerta creada',
            'edit-alert-form': 'Alerta actualizada',
            'new-event-form': 'Evento creado',
            'edit-event-form': 'Evento actualizado'
        };
        
        return actions[formId] || 'Acción completada';
    }
    
    // Special handlers
    const refundButton = document.getElementById('refund-payment-btn');
    if (refundButton) {
        refundButton.addEventListener('click', function() {
            if (confirm('¿Estás seguro de que quieres procesar este reembolso?')) {
                showNotification('Reembolso procesado exitosamente', 'success');
                closeAllModals();
            }
        });
    }
    
    // Notification system
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg text-white max-w-sm ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 
            'bg-blue-600'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    
    // Avatar upload handler
    const avatarUploads = document.querySelectorAll('[id$="avatar-upload"]');
    avatarUploads.forEach(upload => {
        upload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarId = upload.id.replace('-upload', '');
                    const avatarImg = document.getElementById(avatarId);
                    if (avatarImg) {
                        avatarImg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // --- INITIALIZE APP ---
    console.log('🚀 Initializing app...');
    console.log('📋 Elements found:');
    console.log('  - Sidebar:', sidebar);
    console.log('  - Sections:', sections.length);
    console.log('  - Nav items:', allNavItems.length);
    console.log('  - Theme toggle:', themeToggle);
    console.log('  - Admin toggle:', adminModeToggle);
    
    // Initialize with current page context
    const currentPage = window.location.pathname.split('/').pop().replace('.html', '') || 'dashboard';
    console.log('🏠 Initializing for page:', currentPage);
    
    // Set correct navigation state for current page
    updateNavigationState(currentPage);
    
    // Only call showSection if we have sections in current page (single-page mode)
    if (sections.length > 0) {
        showSection(currentPage);
    }
}); 