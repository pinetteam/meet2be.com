<!-- Notification Component -->
<div id="notification-container" class="fixed top-20 right-2 sm:right-4 z-50 pointer-events-none">
    <!-- Notifications will be injected here -->
</div>

<script>
// Initialize notification system after Alpine is ready
document.addEventListener('DOMContentLoaded', () => {
    // Create notification system
    window.notificationSystem = {
        container: document.getElementById('notification-container'),
        notifications: [],
        
        addNotification(title, message, type = 'info', duration = 5000) {
            const id = Date.now();
            const notification = {
                id,
                title,
                message,
                type
            };
            
            this.notifications.push(notification);
            
            // Create notification element
            const notificationEl = this.createNotificationElement(notification);
            this.container.appendChild(notificationEl);
            
            // Show animation
            setTimeout(() => {
                notificationEl.classList.remove('opacity-0', 'translate-x-full');
                notificationEl.classList.add('opacity-100', 'translate-x-0');
            }, 100);
            
            // Auto remove
            if (duration > 0) {
                setTimeout(() => {
                    this.removeNotification(id);
                }, duration);
            }
        },
        
        createNotificationElement(notification) {
            const div = document.createElement('div');
            div.id = `notification-${notification.id}`;
            div.className = 'mb-3 pointer-events-auto transform transition-all duration-300 ease-out opacity-0 translate-x-full';
            
            const colors = {
                success: {
                    bg: 'bg-green-50 dark:bg-green-900',
                    border: 'border-green-500',
                    icon: 'text-green-600 dark:text-green-400',
                    title: 'text-gray-900 dark:text-white',
                    text: 'text-gray-700 dark:text-gray-300',
                    iconClass: 'fa-circle-check'
                },
                error: {
                    bg: 'bg-red-50 dark:bg-red-900',
                    border: 'border-red-500',
                    icon: 'text-red-600 dark:text-red-400',
                    title: 'text-gray-900 dark:text-white',
                    text: 'text-gray-700 dark:text-gray-300',
                    iconClass: 'fa-circle-xmark'
                },
                warning: {
                    bg: 'bg-yellow-50 dark:bg-yellow-900',
                    border: 'border-yellow-500',
                    icon: 'text-yellow-600 dark:text-yellow-400',
                    title: 'text-gray-900 dark:text-white',
                    text: 'text-gray-700 dark:text-gray-300',
                    iconClass: 'fa-triangle-exclamation'
                },
                info: {
                    bg: 'bg-blue-50 dark:bg-blue-900',
                    border: 'border-blue-500',
                    icon: 'text-blue-600 dark:text-blue-400',
                    title: 'text-gray-900 dark:text-white',
                    text: 'text-gray-700 dark:text-gray-300',
                    iconClass: 'fa-circle-info'
                }
            };
            
            const style = colors[notification.type] || colors.info;
            
            div.innerHTML = `
                <div class="flex items-start gap-3 w-full max-w-[calc(100vw-1rem)] sm:max-w-md p-4 rounded-lg border-l-4 shadow-lg ${style.bg} ${style.border}">
                    <!-- Icon -->
                    <div class="${style.icon} flex-shrink-0">
                        <i class="fa-solid ${style.iconClass} text-lg sm:text-xl"></i>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-sm ${style.title} truncate">${notification.title}</h3>
                        ${notification.message ? `<p class="text-sm mt-1 ${style.text} break-words">${notification.message}</p>` : ''}
                    </div>
                    
                    <!-- Close button -->
                    <button onclick="window.notificationSystem.removeNotification(${notification.id})"
                            class="flex-shrink-0 ml-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
            `;
            
            return div;
        },
        
        removeNotification(id) {
            const notificationEl = document.getElementById(`notification-${id}`);
            if (notificationEl) {
                // Hide animation
                notificationEl.classList.remove('opacity-100', 'translate-x-0');
                notificationEl.classList.add('opacity-0', 'translate-x-full');
                
                // Remove from DOM after animation
                setTimeout(() => {
                    notificationEl.remove();
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }, 300);
            }
        }
    };
    
    // Global notification helper
    window.notify = function(title, message = '', type = 'info', duration = 5000) {
        window.notificationSystem.addNotification(title, message, type, duration);
    };
    
    // Handle Laravel session flash messages
    <?php if(session('success')): ?>
        window.notify('<?php echo e(__("portal.general.success")); ?>', '<?php echo e(session("success")); ?>', 'success');
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        window.notify('<?php echo e(__("portal.general.error")); ?>', '<?php echo e(session("error")); ?>', 'error');
    <?php endif; ?>
    
    <?php if(session('warning')): ?>
        window.notify('<?php echo e(__("portal.general.warning")); ?>', '<?php echo e(session("warning")); ?>', 'warning');
    <?php endif; ?>
    
    <?php if(session('info')): ?>
        window.notify('<?php echo e(__("portal.general.info")); ?>', '<?php echo e(session("info")); ?>', 'info');
    <?php endif; ?>
});
</script> <?php /**PATH C:\Users\Ali Erdem Sunar\Documents\Projects\meet2be.com\resources\views/components/portal/notification.blade.php ENDPATH**/ ?>