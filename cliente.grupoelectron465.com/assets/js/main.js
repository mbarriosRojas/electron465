// JavaScript principal del portal

document.addEventListener('DOMContentLoaded', function() {
    // Formato automático para código de verificación
    const codeInput = document.getElementById('codigo');
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            // Solo permitir números
            this.value = this.value.replace(/\D/g, '');
            // Limitar a 6 dígitos
            if (this.value.length > 6) {
                this.value = this.value.slice(0, 6);
            }
        });
    }
    
    // Validación de confirmación de contraseña
    const verifyForm = document.getElementById('verifyForm');
    if (verifyForm) {
        verifyForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
                return false;
            }
        });
    }
    
    // Auto-formato de documento de identidad
    const documentoInputs = document.querySelectorAll('input[name="documento_id"]');
    documentoInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            // Solo permitir números y letras
            this.value = this.value.replace(/[^0-9a-zA-Z]/g, '');
        });
    });
});

// Función para formatear moneda
function formatCurrency(amount) {
    // Asegurar que amount sea un número
    const num = parseFloat(amount) || 0;
    // Formatear con punto para miles y coma para decimales (formato europeo/venezolano)
    const formatted = num.toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return '$USD ' + formatted;
}

// Función para formatear fecha
function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('es-VE');
}

