// Job Ads JavaScript - Gestion de l'affichage des détails
// Step 03: Afficher les détails complets sans recharger la page

document.addEventListener('DOMContentLoaded', function() {
    initializeJobAds();
});

// Initialiser les fonctionnalités des annonces d'emploi
function initializeJobAds() {
    // Ajouter des écouteurs d'événements pour tous les boutons
    const learnMoreButtons = document.querySelectorAll('.learn-more-btn');
    
    learnMoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Récupérer l'ID du job depuis l'onclick
            const jobCard = this.closest('.job-ad-card');
            const jobId = jobCard.getAttribute('data-job-id');
            
            if (jobId) {
                toggleJobDetails(jobId);
            }
        });
    });
    
    // Initialiser les animations et effets
    initializeAnimations();
}

// Fonction principale pour basculer l'affichage des détails
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
    
    // Vérifier si les détails sont actuellement affichés
    const isExpanded = detailsElement.style.display !== 'none';
    
    if (isExpanded) {
        // Masquer les détails
        hideJobDetails(detailsElement, button, buttonIcon, buttonText);
    } else {
        // Afficher les détails
        showJobDetails(detailsElement, button, buttonIcon, buttonText);
    }
}

// Afficher les détails d'une offre d'emploi
function showJobDetails(detailsElement, button, buttonIcon, buttonText) {
    // Animation d'ouverture
    detailsElement.style.display = 'block';
    detailsElement.classList.remove('hiding');
    
    // Mettre à jour le bouton
    button.classList.add('expanded');
    if (buttonIcon) {
        buttonIcon.style.transform = 'rotate(180deg)';
    }
    if (buttonText) {
        buttonText.textContent = 'Show Less';
    }
    
    // Scroll vers l'élément pour une meilleure UX
    setTimeout(() => {
        detailsElement.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'nearest' 
        });
    }, 200);
}

// Masquer les détails d'une offre d'emploi
function hideJobDetails(detailsElement, button, buttonIcon, buttonText) {
    // Animation de fermeture
    detailsElement.classList.add('hiding');
    
    // Attendre la fin de l'animation avant de masquer
    setTimeout(() => {
        detailsElement.style.display = 'none';
        detailsElement.classList.remove('hiding');
    }, 300);
    
    // Mettre à jour le bouton
    button.classList.remove('expanded');
    if (buttonIcon) {
        buttonIcon.style.transform = 'rotate(0deg)';
    }
    if (buttonText) {
        buttonText.textContent = 'Learn More';
    }
}

// Afficher les détails d'une offre
function showJobDetails(detailsElement, button, buttonIcon, buttonText, jobId) {
    // Ajouter un effet de chargement
    button.style.opacity = '0.7';
    buttonText.textContent = 'Chargement...';
    
    // Simuler un léger délai pour l'effet de chargement
    setTimeout(() => {
        // Étendre la section des détails
        detailsElement.classList.add('expanded');
        
        // Mettre à jour le bouton
        button.classList.add('expanded');
        buttonIcon.classList.add('expanded');
        buttonText.textContent = 'Réduire';
        
        // Restaurer l'opacité
        button.style.opacity = '1';
        
        // Faire défiler vers la section des détails
        setTimeout(() => {
            detailsElement.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'nearest' 
            });
        }, 300);
        
        // Analytics ou tracking (simulation)
        trackJobDetailView(jobId);
        
    }, 200);
}

// Masquer les détails d'une offre
function hideJobDetails(detailsElement, button, buttonIcon, buttonText) {
    // Réduire la section des détails
    detailsElement.classList.remove('expanded');
    
    // Mettre à jour le bouton
    button.classList.remove('expanded');
    buttonIcon.classList.remove('expanded');
    buttonText.textContent = 'En savoir plus';
    
    // Faire défiler vers le haut de la carte
    setTimeout(() => {
        button.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center' 
        });
    }, 100);
}

// Initialiser les animations et effets visuels
function initializeAnimations() {
    // Effet de survol sur les cartes
    const jobCards = document.querySelectorAll('.job-ad-card');
    
    jobCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            // Ne pas réinitialiser si les détails sont ouverts
            const detailsElement = this.querySelector('.job-details');
            if (!detailsElement.classList.contains('expanded')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
    
    // Effet sur les tags de compétences
    const skillTags = document.querySelectorAll('.skill-tag');
    
    skillTags.forEach(tag => {
        tag.addEventListener('click', function() {
            // Animation de clic
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Simuler une recherche par compétence
            const skill = this.textContent.trim();
            showNotification(`Recherche d'emplois avec la compétence: ${skill}`, 'info');
        });
    });
}

// Fonction utilitaire pour afficher des notifications
function showNotification(message, type = 'info') {
    // Créer l'élément de notification
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    // Icônes selon le type
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
    
    // Ajouter au DOM
    document.body.appendChild(notification);
    
    // Animation d'apparition
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto-suppression après 4 secondes
    setTimeout(() => closeNotification(notification.querySelector('.notification-close')), 4000);
}

// Fermer une notification
function closeNotification(closeBtn) {
    const notification = closeBtn.closest('.notification');
    notification.classList.remove('show');
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}

// Simuler le tracking des vues de détails (pour analytics)
function trackJobDetailView(jobId) {
    console.log(`Job detail viewed: ${jobId}`);
    
    // Ici on pourrait envoyer des données analytics
    // Par exemple: Google Analytics, Mixpanel, etc.
    
    // Simuler un appel API
    if (typeof gtag !== 'undefined') {
        gtag('event', 'view_job_details', {
            'job_id': jobId,
            'event_category': 'job_interaction'
        });
    }
}

// Fonction pour fermer tous les détails ouverts (utile pour mobile)
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

// Gestionnaire pour la touche Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAllJobDetails();
    }
});

// Gestion du responsive - fermer les détails lors du redimensionnement
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        // Sur petits écrans, fermer automatiquement les détails ouverts
        if (window.innerWidth < 768) {
            const expandedDetails = document.querySelectorAll('.job-details.expanded');
            if (expandedDetails.length > 1) {
                // Garder seulement le premier ouvert
                for (let i = 1; i < expandedDetails.length; i++) {
                    const jobCard = expandedDetails[i].closest('.job-ad-card');
                    const jobId = jobCard.getAttribute('data-job-id');
                    toggleJobDetails(jobId);
                }
            }
        }
    }, 250);
});

// Fonction pour partager une offre d'emploi
function shareJob(jobId, jobTitle) {
    if (navigator.share) {
        // API Web Share (pour mobile)
        navigator.share({
            title: `Offre d'emploi: ${jobTitle}`,
            text: `Découvrez cette opportunité d'emploi sur Job Finder`,
            url: `${window.location.origin}/job-detail.html?id=${jobId}`
        });
    } else {
        // Fallback: copier l'URL
        const url = `${window.location.origin}/job-detail.html?id=${jobId}`;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(() => {
                showNotification('Lien copié dans le presse-papiers!', 'success');
            });
        } else {
            // Fallback plus ancien
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

// Ajouter les styles pour les notifications dynamiquement
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

// Ajouter les styles au document
const styleSheet = document.createElement('style');
styleSheet.textContent = notificationStyles;
document.head.appendChild(styleSheet);

// Performance: Lazy loading des images (si présentes)
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

// Initialiser le lazy loading si supporté
if ('IntersectionObserver' in window) {
    initializeLazyLoading();
}

// Export des fonctions pour usage global
window.toggleJobDetails = toggleJobDetails;
window.shareJob = shareJob;
window.closeAllJobDetails = closeAllJobDetails;