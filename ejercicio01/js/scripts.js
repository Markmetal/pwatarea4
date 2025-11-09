document.addEventListener('DOMContentLoaded', function() {

    const filterButtons = document.querySelectorAll('.btn-filter');
    const tareaCards = document.querySelectorAll('.tarea-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {

            filterButtons.forEach(btn => btn.classList.remove('active'));

            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            tareaCards.forEach(card => {
                if (filter === 'todas') {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    const estado = card.getAttribute('data-estado');
                    if (estado === filter) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'scale(1)';
                        }, 10);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                }
            });
        });
    });
 
    tareaCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        setTimeout(() => {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    
    if (mensaje) {
        mostrarNotificacion(mensaje, 'success');
    }

    const formsEliminar = document.querySelectorAll('form[action="eliminar_tarea.php"]');
    formsEliminar.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
                e.preventDefault();
            }
        });
    });

    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.style.display = 'none';
            }, 300);
        }, 5000);
    });
});
function mostrarNotificacion(mensaje, tipo = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${tipo}`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.textContent = mensaje;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

const forms = document.querySelectorAll('form');
forms.forEach(form => {
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let valid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                valid = false;
                field.style.borderColor = '#dc3545';
            } else {
                field.style.borderColor = '#e0e0e0';
            }
        });
        
        if (!valid) {
            e.preventDefault();
            mostrarNotificacion('Por favor, completa todos los campos requeridos', 'error');
        }
    });
});

const textareas = document.querySelectorAll('textarea');
textareas.forEach(textarea => {
    const maxLength = 500;
    const counter = document.createElement('div');
    counter.style.textAlign = 'right';
    counter.style.fontSize = '0.85em';
    counter.style.color = '#666';
    counter.style.marginTop = '5px';
    textarea.parentNode.appendChild(counter);
    
    function updateCounter() {
        const length = textarea.value.length;
        counter.textContent = `${length} / ${maxLength} caracteres`;
        
        if (length > maxLength * 0.9) {
            counter.style.color = '#dc3545';
        } else {
            counter.style.color = '#666';
        }
    }
    
    textarea.addEventListener('input', updateCounter);
    updateCounter();
});