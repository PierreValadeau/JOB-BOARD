class ApplicationManager {
    constructor() {
        this.currentJobId = null;
        this.currentJobTitle = null;
        this.appliedJobs = new Set(); // Pour tracker les candidatures déjà envoyées
        this.checkSessionUrl = '../api/check-session.php';
        this.currentUser = null;
        this.init();
    }
    
    async init() {
        // Vérifier la session utilisateur
        await this.checkUserSession();
        
        // Ajouter les boutons Apply à toutes les cartes d'emploi existantes
        this.addApplyButtons();
        
        // Écouter les changements dynamiques du DOM
        const observer = new MutationObserver(() => {
            this.addApplyButtons();
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }
    
    async checkUserSession() {
        try {
            const response = await fetch(this.checkSessionUrl);
            const data = await response.json();
            
            if (data.success && data.is_logged_in) {
                this.currentUser = data.user;
                console.log('Utilisateur connecté:', this.currentUser);
            } else {
                this.currentUser = null;
                console.log('Utilisateur non connecté');
            }
        } catch (error) {
            console.error('Erreur vérification session:', error);
            this.currentUser = null;
        }
    }
    
    addApplyButtons() {
        const jobCards = document.querySelectorAll('.job-card');
        
        jobCards.forEach(card => {
            // Vérifier si le bouton Apply existe déjà
            if (card.querySelector('.btn-apply')) {
                return;
            }
            
            const learnMoreBtn = card.querySelector('.btn-learn-more');
            if (!learnMoreBtn) {
                return;
            }
            
            // Extraire l'ID du job depuis le bouton "En savoir plus"
            const jobId = this.extractJobId(card);
            if (!jobId) {
                return;
            }
            
            // Créer le conteneur des actions
            let actionsContainer = card.querySelector('.job-actions');
            if (!actionsContainer) {
                actionsContainer = document.createElement('div');
                actionsContainer.className = 'job-actions';
                
                // Déplacer le bouton "En savoir plus" dans le conteneur
                actionsContainer.appendChild(learnMoreBtn);
                
                // Ajouter le conteneur à la carte
                card.appendChild(actionsContainer);
            }
            
            // Créer le bouton Apply
            const applyBtn = document.createElement('button');
            applyBtn.className = 'btn-apply';
            applyBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Postuler';
            applyBtn.onclick = (e) => {
                e.stopPropagation();
                this.openApplicationModal(jobId, this.extractJobTitle(card));
            };
            
            // Vérifier si l'utilisateur a déjà postulé
            if (this.appliedJobs.has(jobId)) {
                applyBtn.innerHTML = '<i class="fas fa-check"></i> Candidature envoyée';
                applyBtn.disabled = true;
            }
            
            actionsContainer.appendChild(applyBtn);
        });
    }
    
    extractJobId(card) {
        // Priorité 1: Attribut data-job-id sur la carte
        if (card.dataset.jobId) {
            return parseInt(card.dataset.jobId);
        }
        
        // Priorité 2: Attribut data-job-id sur le bouton "En savoir plus"
        const learnMoreBtn = card.querySelector('.btn-learn-more');
        if (learnMoreBtn && learnMoreBtn.dataset.jobId) {
            return parseInt(learnMoreBtn.dataset.jobId);
        }
        
        // Priorité 3: Attribut data-job-id sur le titre
        const jobTitle = card.querySelector('.job-title');
        if (jobTitle && jobTitle.dataset.jobId) {
            return parseInt(jobTitle.dataset.jobId);
        }
        
        return null;
    }
    
    extractJobTitle(card) {
        const titleElement = card.querySelector('.job-title');
        return titleElement ? titleElement.textContent.trim() : 'Offre d\'emploi';
    }
    
    openApplicationModal(jobId, jobTitle) {
        // Vérifier si l'utilisateur est connecté
        if (!this.currentUser) {
            // Rediriger vers la page de connexion
            window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
            return;
        }
        
        this.currentJobId = jobId;
        this.currentJobTitle = jobTitle;
        
        const modal = this.createApplicationModal();
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        
        // Pré-remplir le formulaire avec les infos utilisateur
        this.prefillUserData(modal);
        
        // Focus sur le premier champ vide
        setTimeout(() => {
            const firstEmptyInput = modal.querySelector('textarea[name="cover_letter"]');
            if (firstEmptyInput) {
                firstEmptyInput.focus();
            }
        }, 100);
    }
    
    createApplicationModal() {
        const modal = document.createElement('div');
        modal.className = 'application-modal';
        modal.innerHTML = `
            <div class="application-form">
                <h2><i class="fas fa-paper-plane"></i> Postuler pour "${this.currentJobTitle}"</h2>
                
                <form id="applicationForm">
                    <div class="form-group">
                        <label for="applicant_name">Nom complet *</label>
                        <input type="text" id="applicant_name" name="applicant_name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="applicant_email">Email *</label>
                        <input type="email" id="applicant_email" name="applicant_email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="applicant_phone">Téléphone</label>
                        <input type="tel" id="applicant_phone" name="applicant_phone">
                    </div>
                    
                    <div class="form-group">
                        <label for="cover_letter">Lettre de motivation</label>
                        <textarea id="cover_letter" name="cover_letter" placeholder="Expliquez pourquoi vous êtes le candidat idéal pour ce poste..."></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn-cancel" onclick="this.closest('.application-modal').remove(); document.body.style.overflow = 'auto';">
                            Annuler
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-paper-plane"></i> Envoyer ma candidature
                        </button>
                    </div>
                </form>
            </div>
        `;
        
        // Fermer le modal en cliquant à l'extérieur
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeModal(modal);
            }
        });
        
        // Gérer la soumission du formulaire
        const form = modal.querySelector('#applicationForm');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitApplication(form, modal);
        });
        
        return modal;
    }
    
    async submitApplication(form, modal) {
        const submitBtn = form.querySelector('.btn-submit');
        const originalText = submitBtn.innerHTML;
        
        // Désactiver le bouton et changer le texte
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi en cours...';
        
        try {
            // Collecter les données du formulaire
            const formData = new FormData(form);
            const applicationData = {
                job_id: this.currentJobId,
                applicant_name: formData.get('applicant_name'),
                applicant_email: formData.get('applicant_email'),
                applicant_phone: formData.get('applicant_phone'),
                cover_letter: formData.get('cover_letter')
            };
            
            // Envoyer la candidature
            const response = await fetch('/api/applications.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(applicationData)
            });
            
            const result = await response.json();
            
            if (response.ok && result.success !== false) {
                // Succès
                this.showSuccessMessage(modal);
                this.appliedJobs.add(this.currentJobId);
                this.updateApplyButton(this.currentJobId);
                
                setTimeout(() => {
                    this.closeModal(modal);
                }, 2000);
                
            } else {
                // Erreur
                this.showErrorMessage(modal, result.error || result.message || 'Erreur lors de l\'envoi de la candidature');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
            
        } catch (error) {
            console.error('Erreur:', error);
            this.showErrorMessage(modal, 'Erreur de connexion. Veuillez réessayer.');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
    
    showSuccessMessage(modal) {
        const form = modal.querySelector('.application-form');
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success';
        alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> Votre candidature a été envoyée avec succès !';
        
        form.insertBefore(alertDiv, form.firstChild);
    }
    
    showErrorMessage(modal, message) {
        // Supprimer les anciens messages d'erreur
        const existingAlert = modal.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        
        const form = modal.querySelector('.application-form');
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-error';
        alertDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        
        form.insertBefore(alertDiv, form.firstChild);
    }
    
    updateApplyButton(jobId) {
        const jobCards = document.querySelectorAll('.job-card');
        jobCards.forEach(card => {
            const cardJobId = this.extractJobId(card);
            if (cardJobId === jobId) {
                const applyBtn = card.querySelector('.btn-apply');
                if (applyBtn) {
                    applyBtn.innerHTML = '<i class="fas fa-check"></i> Candidature envoyée';
                    applyBtn.disabled = true;
                }
            }
        });
    }
    
    prefillUserData(modal) {
        if (!this.currentUser) return;
        
        // Pré-remplir le nom (si disponible)
        const nameInput = modal.querySelector('input[name="applicant_name"]');
        if (nameInput && this.currentUser.name) {
            nameInput.value = this.currentUser.name;
        }
        
        // Pré-remplir l'email
        const emailInput = modal.querySelector('input[name="applicant_email"]');
        if (emailInput && this.currentUser.email) {
            emailInput.value = this.currentUser.email;
        }
        
        // Ajouter un message personnalisé
        const formTitle = modal.querySelector('h2');
        if (formTitle) {
            formTitle.innerHTML = `<i class="fas fa-paper-plane"></i> Postuler pour "${this.currentJobTitle}" - Connecté en tant que ${this.currentUser.name || this.currentUser.email}`;
        }
    }
    
    closeModal(modal) {
        modal.remove();
        document.body.style.overflow = 'auto';
    }
}

// Initialiser le gestionnaire de candidatures quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
    window.applicationManager = new ApplicationManager();
});

// Si FeaturedJobs existe déjà et crée les cartes dynamiquement
if (typeof FeaturedJobs !== 'undefined') {
    // Attendre que les cartes soient créées
    setTimeout(() => {
        window.applicationManager = new ApplicationManager();
    }, 1000);
}