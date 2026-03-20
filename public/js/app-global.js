document.addEventListener('DOMContentLoaded', function() {
    // Global jQuery Validation Defaults
    if (window.jQuery && jQuery.validator) {
        jQuery.validator.setDefaults({
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid').removeClass('is-valid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('choices__input')) {
                   error.insertAfter(element.closest('.choices'));
                } else {
                    error.insertAfter(element);
                }
            }
        });

        // Custom method for Phone
        jQuery.validator.addMethod("phoneFormat", function(value, element) {
            return this.optional(element) || /^\+?[0-9\s\-()]{7,20}$/.test(value);
        }, "Please enter a valid phone number.");

        // Fix: Remove loader if validation fails
        $(document).on('invalid-form.validate', 'form', function() {
            const btn = $(this).find('button[type="submit"]');
            window.removeLoader(btn[0]);
        });
    }

    // Manual button loader utilities
    window.showLoader = function(btn) {
        if (!btn) return;
        btn.classList.add('btn-loading');
        if (btn.classList.contains('btn-light') || btn.classList.contains('btn-brand-mint') || btn.classList.contains('bg-brand-mint')) {
            btn.classList.add('btn-loading-dark');
        }
    };

    window.removeLoader = function(btn) {
        if (!btn) return;
        btn.classList.remove('btn-loading');
        btn.classList.remove('btn-loading-dark');
    };

    // Enhanced Global Form Handler
    document.querySelectorAll('form').forEach(form => {
        if (form.classList.contains('no-global-handler')) return;

        const onsubmitStr = form.getAttribute('onsubmit');
        
        // Case A: Form has "return confirm"
        if (onsubmitStr && onsubmitStr.includes('return confirm')) {
            const msgMatch = onsubmitStr.match(/confirm\(['"](.*?)['"]\)/);
            const msg = msgMatch ? msgMatch[1] : 'Are you sure?';
            
            form.removeAttribute('onsubmit');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = e.submitter || form.querySelector('button[type="submit"]');
                
                // Check jQuery validation first
                if (window.jQuery && $(form).data('validator') && !$(form).valid()) {
                    window.removeLoader(btn);
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: msg,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#142472',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, proceed',
                    customClass: { popup: 'rounded-4 border-0 shadow' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.showLoader(btn);
                        form.submit();
                    } else {
                        window.removeLoader(btn);
                    }
                });
            });
        } 
        // Case B: Regular Form (including jQuery validated ones)
        else {
            form.addEventListener('submit', function(e) {
                const submitBtn = e.submitter || form.querySelector('button[type="submit"]');

                // Check jQuery validation if present
                if (window.jQuery && $(form).data('validator') && !$(form).valid()) {
                    window.removeLoader(submitBtn);
                    return;
                }

                if (submitBtn && !submitBtn.classList.contains('no-loader')) {
                    window.showLoader(submitBtn);
                }
            });
        }
    });

    // Sidebar Toggle for Mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && 
                sidebar && 
                sidebarToggle &&
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    }
});
