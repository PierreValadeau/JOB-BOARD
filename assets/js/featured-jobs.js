class FeaturedJobs {
    constructor() {
        this.apiUrl = '../api/jobs.php';
        this.container = document.getElementById('featuredJobsGrid');
        this.currentPage = 1;
        this.itemsPerPage = 3;
        this.totalPages = 1;
        this.totalJobs = 0;
        this.init();
    }

    async init() {
        await this.loadFeaturedJobs();
        this.attachEventListeners();
    }

    async loadFeaturedJobs() {
        try {
            this.showLoadingSpinner();
            
            const response = await fetch(`${this.apiUrl}?limit=${this.itemsPerPage}&page=${this.currentPage}`);
            const data = await response.json();
            
            if (data.success && data.jobs.length > 0) {
                this.totalJobs = data.total || data.jobs.length;
                this.totalPages = Math.ceil(this.totalJobs / this.itemsPerPage);
                this.renderJobs(data.jobs);
                this.renderPagination();
            } else {
                this.showError('Aucune offre disponible pour le moment');
            }
        } catch (error) {
            console.error('Erreur chargement offres:', error);
            this.showError('Erreur de chargement des offres');
        }
    }

    renderJobs(jobs) {
        this.container.innerHTML = jobs.map(job => this.createJobCard(job)).join('');
    }

    createJobCard(job) {
        const salaryFormatted = this.formatSalary(job.salary);
        const dateFormatted = this.formatDate(job.published_date);
        
        return `
            <div class="job-card" data-job-id="${job.offers_id}">
                <div class="job-header">
                    <h3 class="job-title">${job.title}</h3>
                    <span class="company-name">${job.company_name || 'Entreprise confidentielle'}</span>
                </div>
                <div class="job-location">
                    <i class="fas fa-map-marker-alt"></i>
                    ${job.location || 'Lieu non spécifié'}
                </div>
                <div class="job-salary">${salaryFormatted}</div>
                <div class="job-description">
                    ${this.truncateText(job.description, 100)}
                </div>
                <div class="job-footer">
                    <span class="job-type">${job.contract_type || 'CDI'}</span>
                    <span class="job-date">${dateFormatted}</span>
                    <button type="button" class="btn-learn-more" data-job-id="${job.offers_id}">
                        En savoir plus
                    </button>
                </div>
            </div>
        `;
    }

    attachEventListeners() {
        this.container.addEventListener('click', (e) => {
            if (e.target.classList.contains('btn-learn-more')) {
                const jobId = e.target.getAttribute('data-job-id');
                this.showJobDetails(jobId);
            }
        });
    }

    async showJobDetails(jobId) {
        const button = document.querySelector(`[data-job-id="${jobId}"]`);
        
        // Changer le texte du bouton pendant le chargement
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';
        button.disabled = true;
        
        try {
            const response = await fetch(`${this.apiUrl}?id=${jobId}`);
            const data = await response.json();
            
            console.log('API Response:', data); // Debug
            
            if (data.success && data.job) {
                this.showFullJobPage(data.job);
            } else if (data.success && data.jobs && data.jobs.length > 0) {
                // Si l'API retourne un tableau, prendre le premier élément
                const job = data.jobs.find(j => j.offers_id == jobId);
                if (job) {
                    this.showFullJobPage(job);
                } else {
                    this.showError('Offre non trouvée');
                    this.resetButton(button);
                }
            } else {
                this.showError('Impossible de charger les détails de cette offre');
                this.resetButton(button);
            }
        } catch (error) {
            console.error('Erreur chargement détails:', error);
            this.showError('Erreur de chargement des détails');
            this.resetButton(button);
        }
    }

    resetButton(button) {
        setTimeout(() => {
            button.innerHTML = 'En savoir plus';
            button.disabled = false;
        }, 2000);
    }

    showFullJobPage(job) {
        // Sauvegarder le contenu actuel
        this.originalContent = document.body.innerHTML;
        
        // Créer la page détaillée
        const jobPageHtml = this.createJobDetailPage(job);
        
        // Remplacer le contenu avec animation
        document.body.style.opacity = '0';
        document.body.style.transition = 'opacity 0.3s ease-in-out';
        
        setTimeout(() => {
            document.body.innerHTML = jobPageHtml;
            document.body.style.opacity = '1';
            
            // Défiler automatiquement vers la section hero (bandeau bleu)
            setTimeout(() => {
                const heroSection = document.querySelector('.hero');
                if (heroSection) {
                    heroSection.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'start' 
                    });
                }
            }, 100);
            
            // Ajouter l'événement pour revenir (mais maintenant via le logo)
            const logoLink = document.querySelector('.logo-link');
            if (logoLink) {
                logoLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.goBackToHome();
                });
            }
        }, 300);
    }

    goBackToHome() {
        // Animation de retour
        document.body.style.opacity = '0';
        
        setTimeout(() => {
            document.body.innerHTML = this.originalContent;
            document.body.style.opacity = '1';
            
            // Réinitialiser l'instance
            new FeaturedJobs();
        }, 300);
    }

    createJobDetailPage(job) {
        const salaryFormatted = this.formatSalary(job.salary);
        
        return `
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>${job.title} - ${job.company_name || 'Entreprise'}</title>
                <link rel="stylesheet" href="../assets/css/index.css">
                <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
            </head>
            <body>
                <!-- Header avec le même style que l'accueil -->
                <header class="header header-white">
                    <div class="container">
                        <div class="nav-wrapper">
                            <div class="logo">
                                <a href="#" class="logo-link" onclick="goBackToHome()">
                                    <h1 class="logo-text">Job Finder</h1>
                                </a>
                            </div>
                            <nav class="nav">
                                <ul class="nav-list">
                                </ul>
                            </nav>
                            <div class="auth-buttons">
                                <a href="login.php" class="btn-link">Se connecter</a>
                                <a href="register.php" class="btn-primary">Créer un compte</a>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Section hero avec le même style que l'accueil -->
                <section class="hero">
                    <div class="container">
                        <div class="hero-content">
                            <h1>${job.title}</h1>
                            <p class="hero-subtitle">
                                <i class="fas fa-building"></i> ${job.company_name || 'Entreprise confidentielle'} 
                                • <i class="fas fa-map-marker-alt"></i> ${job.location || 'Lieu non spécifié'}
                                • <i class="fas fa-euro-sign"></i> ${salaryFormatted}
                                • ${job.contract_type || 'CDI'}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Contenu principal dans une seule grande carte -->
                <section class="featured-jobs">
                    <div class="container">
                        <div class="job-detail-wrapper">
                            <div class="job-detail-card">
                                <!-- Description du poste -->
                                <div class="job-section">
                                    <h3>Description du poste</h3>
                                    <div class="job-description">
                                        <p>${job.long_description || job.description}</p>
                                    </div>
                                </div>

                                ${this.renderJobRequirements(job.job_requirements)}
                                ${this.renderCompanyInfo(job.company_info)}
                                
                                <!-- Informations supplémentaires -->
                                <div class="job-section">
                                    <h3>Informations sur l'offre</h3>
                                    <div class="job-info-grid">
                                        <div class="info-item">
                                            <strong><i class="fas fa-briefcase"></i> Type de contrat:</strong>
                                            <span>${job.contract_type || 'CDI'}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-map-marker-alt"></i> Localisation:</strong>
                                            <span>${job.location || 'Non spécifié'}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-euro-sign"></i> Salaire:</strong>
                                            <span>${salaryFormatted}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong><i class="fas fa-calendar"></i> Date de publication:</strong>
                                            <span>${this.formatDate(job.published_date)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </body>
            </html>
        `;
    }

    showModal(content) {
        const modal = document.createElement('div');
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-container">
                ${content}
            </div>
        `;
        
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
                document.body.style.overflow = 'auto';
            }
        });
    }

    formatSalary(salary) {
        if (!salary) return 'Salaire non communiqué';
        return `${parseInt(salary).toLocaleString()} € par an`;
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = Math.abs(now - date);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 1) return 'Il y a 1 jour';
        if (diffDays < 7) return `Il y a ${diffDays} jours`;
        if (diffDays < 30) return `Il y a ${Math.ceil(diffDays / 7)} semaine(s)`;
        return `Il y a ${Math.ceil(diffDays / 30)} mois`;
    }

    truncateText(text, maxLength) {
        if (!text) return '';
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }

    renderJobRequirements(requirements) {
        if (!requirements) return '';
        
        // Parse JSON si c'est une string, sinon utilise directement l'objet
        const req = typeof requirements === 'string' ? JSON.parse(requirements) : requirements;
        
        let html = '';
        
        if (req.skills && req.skills.length > 0) {
            html += `
                <div class="job-section">
                    <h3><i class="fas fa-tools"></i> Compétences requises</h3>
                    <div class="skills-tags-spaced">
                        ${req.skills.map(skill => 
                            `<span class="skill-tag">${skill}</span>`
                        ).join(' ')}
                    </div>
                </div>
            `;
        }
        
        if (req.experience) {
            html += `
                <div class="job-section">
                    <h3><i class="fas fa-clock"></i> Expérience demandée</h3>
                    <p>${req.experience}</p>
                </div>
            `;
        }
        
        if (req.education) {
            html += `
                <div class="job-section">
                    <h3><i class="fas fa-graduation-cap"></i> Formation requise</h3>
                    <p>${req.education}</p>
                </div>
            `;
        }
        
        return html;
    }
    
    renderCompanyInfo(companyInfo) {
        if (!companyInfo) return '';
        
        // Parse JSON si c'est une string, sinon utilise directement l'objet
        const info = typeof companyInfo === 'string' ? JSON.parse(companyInfo) : companyInfo;
        
        let html = '';
        
        if (info.benefits && info.benefits.length > 0) {
            html += `
                <div class="job-section">
                    <h3><i class="fas fa-gift"></i> Avantages entreprise</h3>
                    <div class="benefits-list">
                        ${info.benefits.map(benefit => 
                            `<div class="benefit-item">${benefit}</div>`
                        ).join('')}
                    </div>
                </div>
            `;
        }
        
        if (info.working_conditions) {
            html += `
                <div class="job-section">
                    <h3><i class="fas fa-building"></i> Conditions de travail</h3>
                    <p>${info.working_conditions}</p>
                </div>
            `;
        }
        
        return html;
    }

    createPaginationContainer() {
        const featuredSection = document.querySelector('.featured-jobs');
        if (featuredSection) {
            const paginationContainer = document.createElement('div');
            paginationContainer.className = 'pagination-container';
            paginationContainer.id = 'jobsPagination';
            
            // Insérer la pagination après la grille des jobs
            const container = document.querySelector('.featured-jobs .container');
            if (container) {
                container.appendChild(paginationContainer);
            }
        }
    }

    renderPagination() {
        const paginationContainer = document.getElementById('jobsPagination');
        if (!paginationContainer) {
            return;
        }
        
        if (this.totalPages <= 1) {
            paginationContainer.style.display = 'none';
            return;
        }
        
        paginationContainer.style.display = 'block';
        
        let paginationHTML = `
            <div class="pagination">
                <button class="pagination-btn prev-btn" 
                        onclick="featuredJobsInstance.goToPage(${this.currentPage - 1})"
                        ${this.currentPage === 1 ? 'disabled' : ''}>
                    <i class="fas fa-chevron-left"></i>
                    Précédent
                </button>
                
                <div class="pagination-numbers">
        `;

        for (let i = 1; i <= this.totalPages; i++) {
            if (i === this.currentPage) {
                paginationHTML += `
                    <button class="pagination-number active">${i}</button>
                `;
            } else {
                paginationHTML += `
                    <button class="pagination-number" 
                            onclick="featuredJobsInstance.goToPage(${i})">${i}</button>
                `;
            }
        }

        paginationHTML += `
                </div>
                
                <button class="pagination-btn next-btn" 
                        onclick="featuredJobsInstance.goToPage(${this.currentPage + 1})"
                        ${this.currentPage === this.totalPages ? 'disabled' : ''}>
                    Suivant
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="pagination-info">
                Page ${this.currentPage} sur ${this.totalPages} 
                (${this.totalJobs} offres au total)
            </div>
        `;

        paginationContainer.innerHTML = paginationHTML;
    }

    async goToPage(pageNumber) {
        if (pageNumber < 1 || pageNumber > this.totalPages || pageNumber === this.currentPage) {
            return;
        }

        this.currentPage = pageNumber;
        await this.loadFeaturedJobs();
        
        const jobsGrid = document.getElementById('featuredJobsGrid');
        if (jobsGrid) {
            jobsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    showLoadingSpinner() {
        this.container.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Chargement des offres...</p>
            </div>
        `;
    }

    showError(message) {
        this.container.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
            </div>
        `;
    }
}

let featuredJobsInstance = null;

document.addEventListener('DOMContentLoaded', () => {
    featuredJobsInstance = new FeaturedJobs();
});