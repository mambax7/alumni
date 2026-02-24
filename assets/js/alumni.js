/**
 * Alumni Module - Frontend JavaScript
 * Vanilla JavaScript (no jQuery dependency)
 * Progressive enhancement approach
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        initViewToggle();
        initRSVPHandlers();
        initConnectionHandlers();
        initFilterHandlers();
        initImageUploadPreview();
        initSkillTagManagement();
        initFormValidation();
        initSearchAutocomplete();
        initSmoothScrolling();
        initMobileMenu();
        initAutoHideAlerts();
    }

    /* ===========================
       View Toggle (Grid/List)
       =========================== */
    function initViewToggle() {
        const viewToggleBtns = document.querySelectorAll('.view-toggle-btn');
        const directoryContainer = document.querySelector('.directory-container');

        if (!viewToggleBtns.length || !directoryContainer) return;

        viewToggleBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const view = this.dataset.view;

                // Update active state
                viewToggleBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Update container class
                directoryContainer.classList.remove('directory-grid', 'directory-list');
                directoryContainer.classList.add(`directory-${view}`);

                // Save preference
                localStorage.setItem('alumni_view_preference', view);
            });
        });

        // Restore saved preference
        const savedView = localStorage.getItem('alumni_view_preference');
        if (savedView) {
            const btn = document.querySelector(`[data-view="${savedView}"]`);
            if (btn) btn.click();
        }
    }

    /* ===========================
       AJAX RSVP Handling
       =========================== */
    function initRSVPHandlers() {
        const rsvpBtns = document.querySelectorAll('.btn-rsvp');

        rsvpBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const eventId = this.dataset.eventId;
                const status = this.dataset.status;
                const originalHtml = this.innerHTML;

                // Show loading state
                this.disabled = true;
                this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

                fetch(XOOPS_URL + '/modules/alumni/ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=rsvp&event_id=${eventId}&status=${status}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state
                        updateRSVPButton(this, status);

                        // Update RSVP count if element exists
                        const countElement = document.querySelector(`#rsvp-count-${eventId}`);
                        if (countElement && data.count) {
                            countElement.textContent = data.count;
                        }

                        showNotification(data.message || 'RSVP updated successfully', 'success');
                    } else {
                        this.innerHTML = originalHtml;
                        showNotification(data.message || 'Failed to update RSVP', 'error');
                    }
                })
                .catch(error => {
                    console.error('RSVP Error:', error);
                    this.innerHTML = originalHtml;
                    showNotification('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    }

    function updateRSVPButton(btn, status) {
        const statusConfig = {
            'going': {
                html: '<i class="fa fa-check"></i> Going',
                class: 'btn-success'
            },
            'maybe': {
                html: '<i class="fa fa-question"></i> Maybe',
                class: 'btn-warning'
            },
            'declined': {
                html: '<i class="fa fa-times"></i> Can\'t Go',
                class: 'btn-secondary'
            }
        };

        const config = statusConfig[status];
        if (config) {
            btn.innerHTML = config.html;
            btn.className = 'btn btn-rsvp ' + config.class;
        }
    }

    /* ===========================
       Connection Request Handling
       =========================== */
    function initConnectionHandlers() {
        const connectBtns = document.querySelectorAll('.btn-connect');

        connectBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const alumniId = this.dataset.alumniId;
                const originalHtml = this.innerHTML;

                // Show loading state
                this.disabled = true;
                this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

                fetch(XOOPS_URL + '/modules/alumni/ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=send_connection&alumni_id=${alumniId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.innerHTML = '<i class="fa fa-clock"></i> Pending';
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-secondary');
                        showNotification(data.message || 'Connection request sent', 'success');
                    } else {
                        this.innerHTML = originalHtml;
                        showNotification(data.message || 'Failed to send request', 'error');
                    }
                })
                .catch(error => {
                    console.error('Connection Error:', error);
                    this.innerHTML = originalHtml;
                    showNotification('An error occurred. Please try again.', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });

        // Accept/Decline connection requests
        const actionBtns = document.querySelectorAll('.btn-connection-action');

        actionBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const connectionId = this.dataset.connectionId;
                const action = this.dataset.action;

                fetch(XOOPS_URL + '/modules/alumni/ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=connection_action&connection_id=${connectionId}&type=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the connection card
                        const card = this.closest('.connection-card');
                        if (card) {
                            card.style.transition = 'opacity 0.3s';
                            card.style.opacity = '0';
                            setTimeout(() => card.remove(), 300);
                        }
                        showNotification(data.message || 'Action completed', 'success');
                    } else {
                        showNotification(data.message || 'Failed to complete action', 'error');
                    }
                })
                .catch(error => {
                    console.error('Connection Action Error:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                });
            });
        });
    }

    /* ===========================
       Filter Form Handlers
       =========================== */
    function initFilterHandlers() {
        const autoSubmitInputs = document.querySelectorAll('.auto-submit');

        autoSubmitInputs.forEach(input => {
            input.addEventListener('change', function() {
                const form = this.closest('form');
                if (form) {
                    form.submit();
                }
            });
        });

        // Reset filters button
        const resetBtn = document.querySelector('.btn-reset-filters');
        if (resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                if (form) {
                    form.reset();
                    form.submit();
                }
            });
        }
    }

    /* ===========================
       Image Upload Preview
       =========================== */
    function initImageUploadPreview() {
        const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');

        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                const previewId = this.dataset.preview;
                const preview = document.getElementById(previewId);

                if (!preview) return;

                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.add('show');
                    };

                    reader.readAsDataURL(this.files[0]);
                } else {
                    preview.classList.remove('show');
                }
            });
        });

        // Update file input labels
        const fileInputLabels = document.querySelectorAll('.custom-file-input');
        fileInputLabels.forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.value.split('\\').pop();
                const label = this.nextElementSibling;
                if (label) {
                    label.textContent = fileName || 'Choose file';
                }
            });
        });
    }

    /* ===========================
       Skill Tag Management
       =========================== */
    function initSkillTagManagement() {
        const skillInput = document.getElementById('skill-input');
        const skillsHidden = document.getElementById('skills-hidden');
        const skillsContainer = document.getElementById('skills-container');

        if (!skillInput || !skillsContainer) return;

        let skills = [];

        // Load existing skills
        if (skillsHidden && skillsHidden.value) {
            try {
                skills = JSON.parse(skillsHidden.value);
                renderSkills();
            } catch (e) {
                skills = [];
            }
        }

        skillInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const skill = this.value.trim();

                if (skill && !skills.includes(skill)) {
                    skills.push(skill);
                    updateSkillsHidden();
                    renderSkills();
                    this.value = '';
                }
            }
        });

        function renderSkills() {
            skillsContainer.innerHTML = skills.map(skill => `
                <span class="skill-tag">
                    ${escapeHtml(skill)}
                    <span class="skill-tag-remove" data-skill="${escapeHtml(skill)}">&times;</span>
                </span>
            `).join('');

            // Add remove handlers
            skillsContainer.querySelectorAll('.skill-tag-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const skill = this.dataset.skill;
                    skills = skills.filter(s => s !== skill);
                    updateSkillsHidden();
                    renderSkills();
                });
            });
        }

        function updateSkillsHidden() {
            if (skillsHidden) {
                skillsHidden.value = JSON.stringify(skills);
            }
        }
    }

    /* ===========================
       Form Validation
       =========================== */
    function initFormValidation() {
        const forms = document.querySelectorAll('.needs-validation');

        forms.forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });

        // File upload validation
        const resumeInputs = document.querySelectorAll('input[type="file"][data-allowed-types]');

        resumeInputs.forEach(input => {
            input.addEventListener('change', function() {
                const allowedTypes = this.dataset.allowedTypes.split(',');
                const maxSize = parseInt(this.dataset.maxSize) || 5242880; // 5MB default

                if (this.files.length > 0) {
                    const file = this.files[0];
                    const fileExt = file.name.split('.').pop().toLowerCase();

                    if (!allowedTypes.includes(fileExt)) {
                        showNotification(`Please upload a valid file. Allowed types: ${allowedTypes.join(', ')}`, 'error');
                        this.value = '';
                        return;
                    }

                    if (file.size > maxSize) {
                        showNotification(`File size must not exceed ${Math.round(maxSize / 1048576)}MB`, 'error');
                        this.value = '';
                        return;
                    }
                }
            });
        });
    }

    /* ===========================
       Search Autocomplete
       =========================== */
    function initSearchAutocomplete() {
        const searchInput = document.getElementById('search-query');
        if (!searchInput) return;

        let debounceTimer;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value.trim();

            if (query.length < 2) return;

            debounceTimer = setTimeout(() => {
                fetch(XOOPS_URL + '/modules/alumni/ajax.php?action=search_autocomplete&q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        if (data.suggestions) {
                            showAutocomplete(searchInput, data.suggestions);
                        }
                    })
                    .catch(error => {
                        console.error('Autocomplete Error:', error);
                    });
            }, 300);
        });
    }

    function showAutocomplete(input, suggestions) {
        // Remove existing autocomplete
        const existing = document.querySelector('.autocomplete-dropdown');
        if (existing) existing.remove();

        if (!suggestions || suggestions.length === 0) return;

        const dropdown = document.createElement('div');
        dropdown.className = 'autocomplete-dropdown';
        dropdown.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
        `;

        suggestions.forEach(item => {
            const div = document.createElement('div');
            div.className = 'autocomplete-item';
            div.style.cssText = 'padding: 10px; cursor: pointer; border-bottom: 1px solid #eee;';
            div.textContent = item.text;
            div.addEventListener('click', () => {
                window.location.href = item.url;
            });
            div.addEventListener('mouseenter', () => {
                div.style.backgroundColor = '#f8f9fa';
            });
            div.addEventListener('mouseleave', () => {
                div.style.backgroundColor = 'white';
            });
            dropdown.appendChild(div);
        });

        input.parentNode.style.position = 'relative';
        input.parentNode.appendChild(dropdown);

        // Close on outside click
        document.addEventListener('click', function closeDropdown(e) {
            if (!dropdown.contains(e.target) && e.target !== input) {
                dropdown.remove();
                document.removeEventListener('click', closeDropdown);
            }
        });
    }

    /* ===========================
       Smooth Scrolling
       =========================== */
    function initSmoothScrolling() {
        const scrollLinks = document.querySelectorAll('a[href^="#"]');

        scrollLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#') return;

                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    /* ===========================
       Mobile Menu Toggle
       =========================== */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileMenu = document.querySelector('.mobile-menu');

        if (!menuToggle || !mobileMenu) return;

        menuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
        });
    }

    /* ===========================
       Auto-hide Alerts
       =========================== */
    function initAutoHideAlerts() {
        const alerts = document.querySelectorAll('.alert.auto-dismiss');

        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        });
    }

    /* ===========================
       Utility Functions
       =========================== */
    function showNotification(message, type = 'info') {
        const alertClass = {
            'success': 'alert-success',
            'error': 'alert-danger',
            'warning': 'alert-warning',
            'info': 'alert-info'
        }[type] || 'alert-info';

        const alert = document.createElement('div');
        alert.className = `alert ${alertClass} alert-dismissible fade show`;
        alert.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(alert);

        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Confirm delete
    const confirmDeleteBtns = document.querySelectorAll('.confirm-delete');
    confirmDeleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
                return false;
            }
        });
    });

})();
