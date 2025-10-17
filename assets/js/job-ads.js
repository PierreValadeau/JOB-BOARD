document.addEventListener('DOMContentLoaded', function() {
    initializeJobAds();
});
function initializeJobAds() {
    const learnMoreButtons = document.querySelectorAll('.learn-more-btn');
    learnMoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const jobCard = this.closest('.job-ad-card');
            const jobId = jobCard.getAttribute('data-job-id');
            if (jobId) {
                toggleJobDetails(jobId);
            }
        });
    });
    initializeAnimations();
}
function toggleJobDetails(jobId) {
    const detailsElement = document.getElementById(`job-details-${jobId}`);
    const jobCard = document.querySelector(`[data-job-id="${jobId}"]`);
    const button = jobCard ? jobCard.querySelector('.learn-more-btn') : null;
    const buttonIcon = button ? button.querySelector('.btn-icon') : null;
    const buttonText = button ? button.querySelector('.btn-text') : null;
    if (!detailsElement || !button) {
        console.error(`Éléments non trouvés pour job ID: ${jobId}`);
        return;
    }
    const isExpanded = detailsElement.style.display !== 'none';
    if (isExpanded) {
        hideJobDetails(detailsElement, button, buttonIcon, buttonText);
    } else {
        showJobDetails(detailsElement, button, buttonIcon, buttonText);
    }
}
function showJobDetails(detailsElement, button, buttonIcon, buttonText) {
    detailsElement.style.display = 'block';
    detailsElement.classList.remove('hiding');
    button.classList.add('expanded');
    if (buttonIcon) {
        buttonIcon.style.transform = 'rotate(180deg)';
    }
    if (buttonText) {
        buttonText.textContent = 'Show Less';
    }
    setTimeout(() => {
        detailsElement.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest' 
        });
    }, 200);
}
function hideJobDetails(detailsElement, button, buttonIcon, buttonText) {
    detailsElement.classList.add('hiding');
    setTimeout(() => {
        detailsElement.style.display = 'none';
        detailsElement.classList.remove('hiding');
    }, 300);
    button.classList.remove('expanded');
    if (buttonIcon) {
        buttonIcon.style.transform = 'rotate(0deg)';
    }
    if (buttonText) {
        buttonText.textContent = 'Learn More';
    }
}
function showJobDetails(detailsElement, button, buttonIcon, buttonText, jobId) {
    button.style.opacity = '0.7';
    buttonText.textContent = 'Chargement...';
    setTimeout(() => {
        detailsElement.classList.add('expanded');
        button.classList.add('expanded');
        buttonIcon.classList.add('expanded');
        buttonText.textContent = 'Réduire';
        button.style.opacity = '1';
        setTimeout(() => {
            detailsElement.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        }, 300);
        trackJobDetailView(jobId);
    }, 200);
}
function hideJobDetails(detailsElement, button, buttonIcon, buttonText) {
    detailsElement.classList.remove('expanded');
    button.classList.remove('expanded');
    buttonIcon.classList.remove('expanded');
    buttonText.textContent = 'En savoir plus';
    setTimeout(() => {
        button.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }, 100);
}
function initializeAnimations() {
    const jobCards = document.querySelectorAll('.job-ad-card');
    jobCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        card.addEventListener('mouseleave', function() {
            const detailsElement = this.querySelector('.job-details');
            if (!detailsElement.classList.contains('expanded')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
    const skillTags = document.querySelectorAll('.skill-tag');
    skillTags.forEach(tag => {
        tag.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            const skill = this.textContent.trim();
            showNotification(`Recherche d'emplois avec la compétence: ${skill}`, 'info');
        });
    });
}
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    const icons = {
        'info': 'fas fa-info-circle',
        'success': 'fas fa-check-circle',
        'warning': 'fas fa-exclamation-triangle',
        'error': 'fas fa-exclamation-circle'
    };
    notification.innerHTML = `
        <i class="${icons[type] || icons.info}"></i>
        <span>${message}</span>
        <button class="notification-close" onclick="closeNotification(this)">&times;</button>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 100);
    setTimeout(() => closeNotification(notification.querySelector('.notification-close')), 4000);
}
function closeNotification(closeBtn) {
    const notification = closeBtn.closest('.notification');
    notification.classList.remove('show');
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}
function trackJobDetailView(jobId) {
    console.log(`Job detail viewed: ${jobId}`);
    if (typeof gtag !== 'undefined') {
        gtag('event', 'view_job_details', {
            'job_id': jobId,
            'event_category': 'job_interaction'
        });
    }
}
function closeAllJobDetails() {
    const expandedDetails = document.querySelectorAll('.job-details.expanded');
    expandedDetails.forEach(details => {
        const jobCard = details.closest('.job-ad-card');
        const jobId = jobCard.getAttribute('data-job-id');
        if (jobId) {
            const button = jobCard.querySelector('.learn-more-btn');
            const buttonIcon = button.querySelector('i');
            const buttonText = button.querySelector('.btn-text');
            hideJobDetails(details, button, buttonIcon, buttonText);
        }
    });
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAllJobDetails();
    }
});
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (window.innerWidth < 768) {
            const expandedDetails = document.querySelectorAll('.job-details.expanded');
            if (expandedDetails.length > 1) {
                for (let i = 1; i < expandedDetails.length; i++) {
                    const jobCard = expandedDetails[i].closest('.job-ad-card');
                    const jobId = jobCard.getAttribute('data-job-id');
                    toggleJobDetails(jobId);
                }
            }
        }
    }, 250);
});
function shareJob(jobId, jobTitle) {
    if (navigator.share) {
        navigator.share({
            title: `Offre d'emploi: ${jobTitle}`,
            text: `Découvrez cette opportunité d'emploi sur Job Finder`,
            url: `${window.location.origin}/job-detail.html?id=${jobId}`
        });
    } else {
        const url = `${window.location.origin}/job-detail.html?id=${jobId}`;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                showNotification('Lien copié dans le presse-papiers!', 'success');
            });
        } else {
            const textArea = document.createElement('textarea');
            textArea.value = url;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showNotification('Lien copié dans le presse-papiers!', 'success');
        }
    }
}
const notificationStyles = `
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(30, 58, 95, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 300px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 1000;
    border-left: 4px solid #1e3a5f;
}
.notification.show {
    transform: translateX(0);
}
.notification-info { border-left-color: #1e3a5f; }
.notification-success { border-left-color: #28a745; }
.notification-warning { border-left-color: #ffc107; }
.notification-error { border-left-color: #dc3545; }
.notification-info i { color: #1e3a5f; }
.notification-success i { color: #28a745; }
.notification-warning i { color: #ffc107; }
.notification-error i { color: #dc3545; }
.notification-close {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #666;
    margin-left: auto;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.notification-close:hover {
    color: #333;
}
@media (max-width: 768px) {
    .notification {
        right: 10px;
        left: 10px;
        min-width: auto;
    }
}
`;
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);
function initializeLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });
    images.forEach(img => imageObserver.observe(img));
}
if ('IntersectionObserver' in window) {
    initializeLazyLoading();
}
window.toggleJobDetails = toggleJobDetails;
window.shareJob = shareJob;
window.closeAllJobDetails = closeAllJobDetails;