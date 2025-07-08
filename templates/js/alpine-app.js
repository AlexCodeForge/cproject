document.addEventListener('alpine:init', () => {
    console.log('🎉 Alpine.js is initializing!');

    // =================================================================== 
    // GLOBAL STORES
    // =================================================================== 

    // App State Store
    Alpine.store('app', {
        // Theme management
        isDarkMode: localStorage.getItem('theme') === 'dark' || 
                   (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        
        // Context detection
        isAdminContext: window.location.pathname.includes('admin-') || document.querySelector('#admin-nav'),
        get isUserContext() { return !this.isAdminContext },
        
        // Admin mode toggle (for user context)
        isAdminMode: false,
        
        // Current page/section
        currentPage: window.location.pathname.split('/').pop().replace('.html', '') || 'dashboard',
        
        // Charts state
        chartsInitialized: false,
        
        // Toggle theme
        toggleTheme() {
            this.isDarkMode = !this.isDarkMode;
            document.documentElement.classList.toggle('dark', this.isDarkMode);
            localStorage.setItem('theme', this.isDarkMode ? 'dark' : 'light');
            console.log('✅ Theme switched to:', this.isDarkMode ? 'dark' : 'light');
        },
        
        // Toggle admin mode
        toggleAdminMode() {
            this.isAdminMode = !this.isAdminMode;
            this.showSection(this.isAdminMode ? 'admin-dashboard' : 'dashboard');
        },
        
        // Navigation handling
        showSection(sectionId) {
            console.log('🔄 showSection called with:', sectionId);
            
            const currentPage = window.location.pathname.split('/').pop().replace('.html', '');
            const targetPage = sectionId;
            
            // If target section is not the current page, navigate to the correct HTML file
            if (targetPage !== currentPage && targetPage !== 'dashboard') {
                const targetFile = `${targetPage}.html`;
                console.log('🔗 Navigating to:', targetFile);
                window.location.href = targetFile;
                return;
            }
            
            this.currentPage = sectionId;
            
            // Initialize charts if needed
            if (sectionId === 'alerts' && !this.chartsInitialized) {
                setTimeout(() => this.initAlertCharts(), 50);
            }
        },
        
        // Initialize alert charts
        initAlertCharts() {
            if (this.chartsInitialized) return;
            
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
            
            this.chartsInitialized = true;
        }
    });

    // Notifications Store
    Alpine.store('notifications', {
        isOpen: false,
        notifications: [
            { id: 1, title: 'Nueva alerta', message: 'TSLA alcanzó tu precio objetivo', time: '2 min', unread: true },
            { id: 2, title: 'Actualización de mercado', message: 'Análisis semanal disponible', time: '1 hr', unread: true },
            { id: 3, title: 'Evento premium', message: 'Webinar en 30 minutos', time: '2 hr', unread: false }
        ],
        
        toggle() {
            this.isOpen = !this.isOpen;
        },
        
        close() {
            this.isOpen = false;
        },
        
        markAsRead(id) {
            const notification = this.notifications.find(n => n.id === id);
            if (notification) {
                notification.unread = false;
            }
        },
        
        get unreadCount() {
            return this.notifications.filter(n => n.unread).length;
        }
    });

    // Feed Store
    Alpine.store('feed', {
        viewMode: 'list', // 'list' or 'grid'
        currentFilter: 'all',
        posts: [], // This would be populated from your data source
        
        setGridView() {
            this.viewMode = 'grid';
        },
        
        setListView() {
            this.viewMode = 'list';
        },
        
        setFilter(category) {
            this.currentFilter = category;
        },
        
        get filteredPosts() {
            if (this.currentFilter === 'all') {
                return this.posts;
            }
            return this.posts.filter(post => post.category === this.currentFilter);
        }
    });

    // =================================================================== 
    // REUSABLE COMPONENTS
    // =================================================================== 

    // Sidebar Component
    Alpine.data('sidebar', () => ({
        isExpanded: false,
        
        init() {
            // Set correct navigation state for current page
            this.updateNavigationState();
        },
        
        expand() {
            this.isExpanded = true;
        },
        
        collapse() {
            this.isExpanded = false;
        },
        
        navigateTo(sectionId, event) {
            if (event) event.preventDefault();
            this.$store.app.showSection(sectionId);
            this.updateNavigationState();
        },
        
        updateNavigationState() {
            const currentPage = this.$store.app.currentPage;
            
            // Reset all navigation items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('bg-slate-700', 'text-white', 'shadow-lg', 'active');
                item.classList.remove('bg-stone-200', 'dark:bg-gray-700', 'text-slate-800', 'dark:text-gray-200');
                item.classList.add('text-slate-500', 'dark:text-gray-400');
                item.classList.add('hover:bg-stone-200', 'dark:hover:bg-gray-700');
                item.classList.add('hover:text-slate-800', 'dark:hover:text-gray-200');
            });
            
            // Set active navigation item
            let activeItem = document.querySelector(`[data-section="${currentPage}"]`);
            if (!activeItem) {
                activeItem = document.querySelector(`[href="${currentPage}.html"]`);
            }
            
            if (activeItem && activeItem.classList.contains('nav-item')) {
                activeItem.classList.remove('text-slate-500', 'dark:text-gray-400');
                activeItem.classList.remove('hover:bg-stone-200', 'dark:hover:bg-gray-700');
                activeItem.classList.remove('hover:text-slate-800', 'dark:hover:text-gray-200');
                activeItem.classList.add('active', 'text-slate-800', 'dark:text-gray-200');
                activeItem.classList.add('bg-stone-200', 'dark:bg-gray-700');
            }
        }
    }));

    // Modal Component
    Alpine.data('modal', (id) => ({
        isOpen: false,
        modalId: id,
        
        open() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
        },
        
        close() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },
        
        closeOnEscape(event) {
            if (event.key === 'Escape') {
                this.close();
            }
        },
        
        closeOnClickOutside(event) {
            if (event.target === event.currentTarget) {
                this.close();
            }
        }
    }));

    // Form Component
    Alpine.data('form', (type) => ({
        formData: {},
        isSubmitting: false,
        
        async submit() {
            this.isSubmitting = true;
            
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                this.showNotification(`${this.getActionText(type)} exitosamente`, 'success');
                this.reset();
                
                // Close modal if this form is in a modal
                const modal = this.$el.closest('[x-data*="modal"]');
                if (modal) {
                    Alpine.$data(modal).close();
                }
            } catch (error) {
                this.showNotification('Error al procesar la solicitud', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        reset() {
            this.formData = {};
            this.$el.reset();
        },
        
        getActionText(formType) {
            const actions = {
                'new-user': 'Usuario creado',
                'edit-user': 'Usuario actualizado',
                'new-pnl': 'Registro P&L creado',
                'edit-pnl': 'Registro P&L actualizado',
                'new-payment': 'Pago creado',
                'edit-payment': 'Pago actualizado',
                'new-post': 'Publicación creada',
                'edit-post': 'Publicación actualizada',
                'new-alert': 'Alerta creada',
                'edit-alert': 'Alerta actualizada',
                'new-event': 'Evento creado',
                'edit-event': 'Evento actualizado'
            };
            
            return actions[formType] || 'Acción completada';
        },
        
        showNotification(message, type = 'info') {
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
    }));

    // Dropdown Component
    Alpine.data('dropdown', (initialOpen = false) => ({
        open: initialOpen,
        
        toggle() {
            this.open = !this.open;
        },
        
        close() {
            this.open = false;
        }
    }));

    // Feed Item Component
    Alpine.data('feedItem', (post) => ({
        post: post,
        isReminded: false,
        
        toggleReminder() {
            this.isReminded = !this.isReminded;
        },
        
        openPost() {
            this.$store.app.showSection('post-view');
        }
    }));

    // Course Component
    Alpine.data('course', (course) => ({
        course: course,
        
        openCourse() {
            this.$store.app.showSection('course-view');
        }
    }));

    // Avatar Upload Component
    Alpine.data('avatarUpload', () => ({
        imageUrl: '',
        
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }));

    // =================================================================== 
    // INITIALIZATION
    // =================================================================== 

    // Initialize theme
    document.documentElement.classList.toggle('dark', Alpine.store('app').isDarkMode);
    
    console.log('🚀 Alpine.js initialization complete!');
    console.log('📋 Context:', Alpine.store('app').isAdminContext ? 'Admin Panel' : 'User Panel');
    console.log('🏠 Current page:', Alpine.store('app').currentPage);
});

// =================================================================== 
// UTILITY FUNCTIONS (available globally)
// =================================================================== 

window.alpineUtils = {
    // Format currency
    formatCurrency(amount) {
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },
    
    // Format date
    formatDate(date) {
        return new Intl.DateTimeFormat('es-ES', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }).format(new Date(date));
    },
    
    // Format time
    formatTime(date) {
        return new Intl.DateTimeFormat('es-ES', {
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date(date));
    },
    
    // Copy to clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            console.error('Failed to copy text: ', err);
            return false;
        }
    }
}; 