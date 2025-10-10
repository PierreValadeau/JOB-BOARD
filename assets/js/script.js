document.addEventListener('DOMContentLoaded', function() {
    initializeSearchFunctionality();
    initializeJobActions();
    initializeFilters();
    initializePagination();
    initializeModal();
    initializeMobileMenu();
    initializeJobCardExpansion(); // Nouvelle fonctionnalité d'expansion des cartes
});
function initializeSearchFunctionality() {
    const searchForm = document.querySelector('.search-form');
    const searchInput = document.querySelector('.search-input');
    const locationInput = document.querySelector('.location-input');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const query = searchInput.value.trim();
            const location = locationInput.value.trim();
            if (!query) {
                showNotification('Veuillez saisir un mot-clé de recherche', 'warning');
                return;
            }
            showLoadingState();
            setTimeout(() => {
                window.location.href = `search-results.html?q=${encodeURIComponent(query)}&location=${encodeURIComponent(location)}`;
            }, 500);
        });
    }
    if (searchInput) {
        const suggestions = [
            'Développeur Web', 'Développeur Mobile', 'Chef de projet',
            'Designer UX/UI', 'Data Scientist', 'Product Manager',
            'Commercial', 'Marketing Digital', 'Comptable'
        ];
        setupAutoComplete(searchInput, suggestions);
    }
    if (locationInput) {
        const locations = [
            'Paris', 'Lyon', 'Marseille', 'Toulouse', 'Nice',
            'Nantes', 'Strasbourg', 'Montpellier', 'Bordeaux', 'Lille'
        ];
        setupAutoComplete(locationInput, locations);
    }
}
function setupAutoComplete(input, suggestions) {
    let currentFocus = -1;
    input.addEventListener('input', function() {
        const value = this.value;
        closeAllLists();
        if (!value) return;
        const listContainer = document.createElement('div');
        listContainer.className = 'autocomplete-list';
        this.parentNode.appendChild(listContainer);
        suggestions.forEach((suggestion, index) => {
            if (suggestion.toLowerCase().includes(value.toLowerCase())) {
                const item = document.createElement('div');
                item.className = 'autocomplete-item';
                item.innerHTML = suggestion.replace(new RegExp(value, 'gi'), `<strong>$&</strong>`);
                item.addEventListener('click', function() {
                    input.value = suggestion;
                    closeAllLists();
                });
                listContainer.appendChild(item);
            }
        });
    });
    input.addEventListener('keydown', function(e) {
        const list = this.parentNode.querySelector('.autocomplete-list');
        if (!list) return;
        const items = list.querySelectorAll('.autocomplete-item');
        if (e.keyCode === 40) {
            currentFocus++;
            addActive(items);
        } else if (e.keyCode === 38) {
            currentFocus--;
            addActive(items);
        } else if (e.keyCode === 13) {
            e.preventDefault();
            if (currentFocus > -1 && items[currentFocus]) {
                items[currentFocus].click();
            }
        }
    });
    function addActive(items) {
        if (!items) return;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;
        if (items[currentFocus]) {
            items[currentFocus].classList.add('active');
        }
    }
    function removeActive(items) {
        items.forEach(item => item.classList.remove('active'));
    }
    function closeAllLists(element) {
        const lists = document.querySelectorAll('.autocomplete-list');
        lists.forEach(list => {
            if (element !== list && element !== input) {
                list.remove();
            }
        });
        currentFocus = -1;
    }
    document.addEventListener('click', function(e) {
        closeAllLists(e.target);
    });
}
function initializeJobActions() {
    const saveButtons = document.querySelectorAll('.save-job, .save-job-btn');
    saveButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSaveJob(this);
        });
    });
    const shareButtons = document.querySelectorAll('.share-job-btn');
    shareButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            showShareModal();
        });
    });
    const applyButtons = document.querySelectorAll('.apply-btn');
    applyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            showApplicationModal();
        });
    });
}
function toggleSaveJob(button) {
    const icon = button.querySelector('i');
    const isSaved = button.classList.contains('saved');
    if (isSaved) {
        button.classList.remove('saved');
        icon.classList.remove('fas');
        icon.classList.add('far');
        showNotification('Offre retirée des favoris', 'info');
    } else {
        button.classList.add('saved');
        icon.classList.remove('far');
        icon.classList.add('fas');
        showNotification('Offre ajoutée aux favoris', 'success');
    }
}
function initializeFilters() {
    const filterOptions = document.querySelectorAll('.filter-option input');
    const clearFiltersBtn = document.querySelector('.clear-filters');
    if (filterOptions.length > 0) {
        filterOptions.forEach(filter => {
            filter.addEventListener('change', function() {
                applyFilters();
            });
        });
    }
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            clearAllFilters();
        });
    }
    const sortSelect = document.getElementById('sortBy');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortResults(this.value);
        });
    }
}
function applyFilters() {
    const activeFilters = getActiveFilters();
    showLoadingState();
    setTimeout(() => {
        updateJobListings(activeFilters);
        hideLoadingState();
        showNotification(`Filtres appliqués (${activeFilters.length} critères)`, 'info');
    }, 800);
}
function getActiveFilters() {
    const filters = [];
    const checkedInputs = document.querySelectorAll('.filter-option input:checked');
    checkedInputs.forEach(input => {
        filters.push({
            type: input.name,
            value: input.value,
            label: input.parentElement.textContent.trim()
        });
    });
    return filters;
}
function clearAllFilters() {
    const filterInputs = document.querySelectorAll('.filter-option input');
    filterInputs.forEach(input => {
        input.checked = false;
    });
    applyFilters();
    showNotification('Tous les filtres ont été effacés', 'info');
}
function sortResults(sortBy) {
    showLoadingState();
    setTimeout(() => {
        hideLoadingState();
        showNotification(`Résultats triés par ${getSortLabel(sortBy)}`, 'info');
    }, 500);
}
function getSortLabel(sortValue) {
    const sortLabels = {
        'relevance': 'pertinence',
        'date': 'date',
        'salary': 'salaire',
        'company': 'entreprise'
    };
    return sortLabels[sortValue] || 'pertinence';
}
function initializePagination() {
    const paginationButtons = document.querySelectorAll('.pagination-btn, .pagination-number');
    paginationButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (this.disabled) return;
            const page = this.textContent.trim();
            loadPage(page);
        });
    });
}
function loadPage(page) {
    showLoadingState();
    setTimeout(() => {
        updatePagination(page);
        scrollToTop();
        hideLoadingState();
        showNotification(`Page ${page} chargée`, 'info');
    }, 800);
}
function updatePagination(currentPage) {
    const paginationNumbers = document.querySelectorAll('.pagination-number');
    paginationNumbers.forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent.trim() === currentPage) {
            btn.classList.add('active');
        }
    });
}
function initializeModal() {
    const modal = document.getElementById('applicationModal');
    const closeBtn = document.querySelector('.modal .close');
    const cancelBtn = document.querySelector('.cancel-btn');
    const applicationForm = document.querySelector('.application-form');
    if (closeBtn) {
        closeBtn.addEventListener('click', hideApplicationModal);
    }
    if (cancelBtn) {
        cancelBtn.addEventListener('click', hideApplicationModal);
    }
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideApplicationModal();
            }
        });
    }
    if (applicationForm) {
        applicationForm.addEventListener('submit', handleApplicationSubmit);
    }
    initializeFileUpload();
}
function showApplicationModal() {
    const modal = document.getElementById('applicationModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}
function hideApplicationModal() {
    const modal = document.getElementById('applicationModal');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
function handleApplicationSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    if (!data.fullName || !data.email || !data.resume) {
        showNotification('Veuillez remplir tous les champs obligatoires', 'error');
        return;
    }
    showLoadingState();
    setTimeout(() => {
        hideLoadingState();
        hideApplicationModal();
        showNotification('Votre candidature a été envoyée avec succès !', 'success');
        e.target.reset();
    }, 2000);
}
function initializeFileUpload() {
    const fileInput = document.getElementById('resume');
    const fileUploadLabel = document.querySelector('.file-upload-label');
    if (!fileInput || !fileUploadLabel) return;
    fileInput.addEventListener('change', function() {
        const fileName = this.files[0]?.name || 'Choisir un fichier ou glisser-déposer';
        updateFileUploadLabel(fileName);
    });
    fileUploadLabel.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });
    fileUploadLabel.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });
    fileUploadLabel.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateFileUploadLabel(files[0].name);
        }
    });
}
function updateFileUploadLabel(fileName) {
    const fileUploadLabel = document.querySelector('.file-upload-label');
    if (fileUploadLabel) {
        const icon = fileUploadLabel.querySelector('i');
        if (fileName === 'Choisir un fichier ou glisser-déposer') {
            fileUploadLabel.innerHTML = `${icon.outerHTML} ${fileName}`;
        } else {
            fileUploadLabel.innerHTML = `<i class="fas fa-file-alt"></i> ${fileName}`;
            fileUploadLabel.classList.add('has-file');
        }
    }
}
function initializeMobileMenu() {
    const hamburger = document.querySelector('.hamburger');
    const mobileMenu = document.querySelector('.mobile-menu');
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });
    }
}
function showLoadingState() {
    const existingLoader = document.querySelector('.loading-overlay');
    if (existingLoader) return;
    const loader = document.createElement('div');
    loader.className = 'loading-overlay';
    loader.innerHTML = `
        <div class="spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Chargement...</p>
        </div>
    `;
    document.body.appendChild(loader);
}
function hideLoadingState() {
    const loader = document.querySelector('.loading-overlay');
    if (loader) {
        loader.remove();
    }
}
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    const icon = getNotificationIcon(type);
    notification.innerHTML = `
        <i class="${icon}"></i>
        <span>${message}</span>
        <button class="notification-close">&times;</button>
    `;
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 100);
    setTimeout(() => removeNotification(notification), 5000);
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => removeNotification(notification));
}
function removeNotification(notification) {
    notification.classList.remove('show');
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 300);
}
function getNotificationIcon(type) {
    const icons = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-circle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    };
    return icons[type] || icons.info;
}
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}
function copyJobUrl() {
    const url = window.location.href;
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Lien copié dans le presse-papiers', 'success');
        });
    } else {
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showNotification('Lien copié dans le presse-papiers', 'success');
    }
}
function showShareModal() {
    showNotification('Fonctionnalité de partage à venir', 'info');
}
function updateJobListings(filters) {
    console.log('Filtres appliqués:', filters);
}
window.addEventListener('error', function(e) {
    console.error('Erreur JavaScript:', e.error);
    showNotification('Une erreur est survenue. Veuillez rafraîchir la page.', 'error');
});
function initHomePage() {
    const heroSection = document.querySelector('.hero');
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = heroSection.querySelector('.hero-content');
            if (parallax) {
                const speed = scrolled * 0.5;
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });
    }
}
function initSearchResultsPage() {
    updateResultsFromURL();
}
function initJobDetailPage() {
    initJobDetailActions();
}
function updateResultsFromURL() {
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('q');
    const location = urlParams.get('location');
    if (query || location) {
        const resultsTitle = document.querySelector('.results-info h1');
        if (resultsTitle) {
            let title = 'Emplois';
            if (query) title += ` ${query}`;
            if (location) title += ` à ${location}`;
            resultsTitle.textContent = title;
        }
    }
}
function initJobDetailActions() {
    const reportLink = document.querySelector('.report-link');
    if (reportLink) {
        reportLink.addEventListener('click', function(e) {
            e.preventDefault();
            showNotification('Merci pour votre signalement. Nous examinerons cette offre.', 'info');
        });
    }
}
const dynamicStyles = `
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.spinner {
    text-align: center;
    color: #2557a7;
}
.spinner i {
    font-size: 2rem;
    margin-bottom: 1rem;
}
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 300px;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 1000;
}
.notification.show {
    transform: translateX(0);
}
.notification-success { border-left: 4px solid #28a745; }
.notification-error { border-left: 4px solid #dc3545; }
.notification-warning { border-left: 4px solid #ffc107; }
.notification-info { border-left: 4px solid #17a2b8; }
.notification-success i { color: #28a745; }
.notification-error i { color: #dc3545; }
.notification-warning i { color: #ffc107; }
.notification-info i { color: #17a2b8; }
.notification-close {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #666;
    margin-left: auto;
}
.autocomplete-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #e4e4e4;
    border-top: none;
    border-radius: 0 0 4px 4px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
    z-index: 100;
}
.autocomplete-item {
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}
.autocomplete-item:hover,
.autocomplete-item.active {
    background-color: #f8f9fa;
}
.autocomplete-item:last-child {
    border-bottom: none;
}
.file-upload-label.dragover {
    background-color: #f0f7ff;
    border-color: #2557a7;
}
.file-upload-label.has-file {
    background-color: #e8f4f8;
    color: #2557a7;
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
styleSheet.textContent = dynamicStyles;
document.head.appendChild(styleSheet);
function initializeHomepageSearch() {
    const searchInput = document.getElementById('job-search');
    const locationInput = document.getElementById('location');
    const searchBtn = document.getElementById('search-btn');
    const advancedToggle = document.querySelector('.advanced-toggle');
    const advancedSearch = document.querySelector('.advanced-search');
    function performSearch() {
        const query = searchInput?.value.trim();
        const location = locationInput?.value.trim();
        if (query) {
            const params = new URLSearchParams({
                q: query,
                location: location || ''
            });
            if (advancedSearch && advancedSearch.classList.contains('expanded')) {
                const contractType = document.getElementById('contract-type')?.value;
                const experience = document.getElementById('experience')?.value;
                const salary = document.getElementById('salary')?.value;
                if (contractType && contractType !== 'all') params.append('contract', contractType);
                if (experience && experience !== 'all') params.append('experience', experience);
                if (salary && salary !== 'all') params.append('salary', salary);
            }
            showLoadingState();
            setTimeout(() => {
                window.location.href = `search-results.html?${params}`;
            }, 500);
        } else {
            showNotification('Veuillez saisir un mot-clé de recherche', 'warning');
        }
    }
    if (searchBtn) {
        searchBtn.addEventListener('click', performSearch);
    }
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    }
    if (locationInput) {
        locationInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    }
    if (advancedToggle && advancedSearch) {
        advancedToggle.addEventListener('click', function() {
            const isExpanded = advancedSearch.classList.contains('expanded');
            if (isExpanded) {
                advancedSearch.classList.remove('expanded');
                advancedToggle.classList.remove('active');
                advancedToggle.innerHTML = '<i class="fas fa-sliders-h"></i> Recherche avancée';
            } else {
                advancedSearch.classList.add('expanded');
                advancedToggle.classList.add('active');
                advancedToggle.innerHTML = '<i class="fas fa-times"></i> Fermer';
            }
        });
    }
    const popularTags = document.querySelectorAll('.popular-tag');
    popularTags.forEach(tag => {
        tag.addEventListener('click', function(e) {
            e.preventDefault();
            const searchTerm = this.textContent.trim();
            if (searchInput) {
                searchInput.value = searchTerm;
                performSearch();
            }
        });
    });
    if (searchInput) {
        const homeSuggestions = [
            'Développeur Web', 'Marketing Digital', 'Commercial', 'Comptable',
            'Infirmier', 'Professeur', 'Ingénieur', 'Designer', 'Chef de projet',
            'Vendeur', 'Secrétaire', 'Technicien', 'Consultant', 'Analyste',
            'Développeur Full Stack', 'Product Manager', 'UX Designer', 
            'Data Scientist', 'DevOps', 'Business Analyst'
        ];
        setupAdvancedAutoComplete(searchInput, homeSuggestions);
    }
}
function setupAdvancedAutoComplete(input, suggestions) {
    const container = input.parentElement;
    let suggestionsContainer = container.querySelector('.search-suggestions');
    if (!suggestionsContainer) {
        suggestionsContainer = document.createElement('div');
        suggestionsContainer.className = 'search-suggestions';
        container.appendChild(suggestionsContainer);
    }
    let currentSuggestionIndex = -1;
    input.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        suggestionsContainer.innerHTML = '';
        currentSuggestionIndex = -1;
        if (query.length >= 2) {
            const filtered = suggestions.filter(item => 
                item.toLowerCase().includes(query)
            ).slice(0, 6);
            if (filtered.length > 0) {
                filtered.forEach((suggestion, index) => {
                    const div = document.createElement('div');
                    div.className = 'suggestion-item';
                    const regex = new RegExp(`(${query})`, 'gi');
                    const highlighted = suggestion.replace(regex, '<strong>$1</strong>');
                    div.innerHTML = highlighted;
                    div.addEventListener('click', function() {
                        input.value = suggestion;
                        suggestionsContainer.classList.remove('show');
                        input.focus();
                    });
                    suggestionsContainer.appendChild(div);
                });
                suggestionsContainer.classList.add('show');
            } else {
                suggestionsContainer.classList.remove('show');
            }
        } else {
            suggestionsContainer.classList.remove('show');
        }
    });
    input.addEventListener('keydown', function(e) {
        const suggestionItems = suggestionsContainer.querySelectorAll('.suggestion-item');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentSuggestionIndex = Math.min(currentSuggestionIndex + 1, suggestionItems.length - 1);
            updateSuggestionSelection(suggestionItems, currentSuggestionIndex);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentSuggestionIndex = Math.max(currentSuggestionIndex - 1, -1);
            updateSuggestionSelection(suggestionItems, currentSuggestionIndex);
        } else if (e.key === 'Enter' && currentSuggestionIndex >= 0) {
            e.preventDefault();
            suggestionItems[currentSuggestionIndex].click();
        } else if (e.key === 'Escape') {
            suggestionsContainer.classList.remove('show');
            currentSuggestionIndex = -1;
        }
    });
    document.addEventListener('click', function(e) {
        if (!container.contains(e.target)) {
            suggestionsContainer.classList.remove('show');
            currentSuggestionIndex = -1;
        }
    });
}
function updateSuggestionSelection(items, activeIndex) {
    items.forEach((item, index) => {
        item.classList.toggle('active', index === activeIndex);
    });
    if (activeIndex >= 0 && items[activeIndex]) {
        items[activeIndex].scrollIntoView({
            block: 'nearest'
        });
    }
}
if (window.location.pathname.includes('index.html') || window.location.pathname === '/' || window.location.pathname.endsWith('/')) {
    document.addEventListener('DOMContentLoaded', initializeHomepageSearch);
}

// ====================================
// FONCTIONNALITÉ D'EXPANSION DES CARTES D'EMPLOI
// ====================================

function initializeJobCardExpansion() {
    // Sélectionner tous les boutons "En savoir plus"
    const learnMoreButtons = document.querySelectorAll('.btn-learn-more');
    
    learnMoreButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Trouver la carte parente
            const jobCard = this.closest('.job-card');
            
            if (jobCard) {
                toggleJobCardExpansion(jobCard, this);
            }
        });
    });
}

function toggleJobCardExpansion(jobCard, button) {
    const isExpanded = jobCard.classList.contains('expanded');
    
    if (isExpanded) {
        // Réduire la carte
        collapseJobCard(jobCard, button);
    } else {
        // D'abord fermer toutes les autres cartes ouvertes
        const allExpandedCards = document.querySelectorAll('.job-card.expanded');
        allExpandedCards.forEach(card => {
            if (card !== jobCard) {
                const cardButton = card.querySelector('.btn-learn-more');
                collapseJobCard(card, cardButton);
            }
        });
        
        // Ensuite ouvrir la carte actuelle
        expandJobCard(jobCard, button);
    }
}

function expandJobCard(jobCard, button) {
    // Ajouter la classe d'expansion avec animation
    jobCard.classList.add('expanded');
    
    // Changer le texte du bouton
    button.textContent = 'Réduire';
    
    // Récupérer les données et remplir le contenu étendu
    populateExpandedContent(jobCard);
    
    // Afficher le contenu étendu
    const expandedContent = jobCard.querySelector('.job-expanded-content');
    if (expandedContent) {
        expandedContent.style.display = 'block';
        
        // Scroll smooth vers la carte
        setTimeout(() => {
            jobCard.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 200);
    }
}

function collapseJobCard(jobCard, button) {
    // Supprimer la classe d'expansion
    jobCard.classList.remove('expanded');
    
    // Remettre le texte du bouton
    button.textContent = 'En savoir plus';
    
    // Masquer le contenu étendu
    const expandedContent = jobCard.querySelector('.job-expanded-content');
    if (expandedContent) {
        // Attendre la fin de l'animation CSS avant de cacher
        setTimeout(() => {
            expandedContent.style.display = 'none';
        }, 400);
    }
}

function populateExpandedContent(jobCard) {
    // Récupérer les données depuis les attributs data-
    const fullDescription = jobCard.getAttribute('data-full-description');
    const fullSalary = jobCard.getAttribute('data-full-salary');
    const workingTime = jobCard.getAttribute('data-working-time');
    const preciseLocation = jobCard.getAttribute('data-precise-location');
    const companyInfo = jobCard.getAttribute('data-company-info');
    const benefits = jobCard.getAttribute('data-benefits');
    
    // Remplir les éléments du contenu étendu
    const expandedDescription = jobCard.querySelector('.expanded-description');
    const expandedSalary = jobCard.querySelector('.expanded-salary');
    const expandedWorkingTime = jobCard.querySelector('.expanded-working-time');
    const expandedLocation = jobCard.querySelector('.expanded-location');
    const expandedCompany = jobCard.querySelector('.expanded-company');
    const expandedBenefits = jobCard.querySelector('.expanded-benefits');
    
    if (expandedDescription) expandedDescription.textContent = fullDescription || 'Information non disponible';
    if (expandedSalary) expandedSalary.textContent = fullSalary || 'Information non disponible';
    if (expandedWorkingTime) expandedWorkingTime.textContent = workingTime || 'Information non disponible';
    if (expandedLocation) expandedLocation.textContent = preciseLocation || 'Information non disponible';
    if (expandedCompany) expandedCompany.textContent = companyInfo || 'Information non disponible';
    if (expandedBenefits) expandedBenefits.textContent = benefits || 'Information non disponible';
}

// Fonction pour basculer l'affichage de la recherche avancée
function toggleAdvancedSearch() {
    const advancedSearch = document.getElementById('advancedSearch');
    const toggleButton = document.querySelector('.advanced-toggle');
    const icon = toggleButton.querySelector('i');
    
    if (advancedSearch.style.display === 'none' || advancedSearch.style.display === '') {
        advancedSearch.style.display = 'block';
        icon.className = 'fas fa-chevron-up';
        toggleButton.querySelector('span') ? 
            toggleButton.querySelector('span').textContent = 'Masquer la recherche avancée' :
            toggleButton.innerHTML = '<i class="fas fa-chevron-up"></i> Masquer la recherche avancée';
    } else {
        advancedSearch.style.display = 'none';
        icon.className = 'fas fa-sliders-h';
        toggleButton.querySelector('span') ? 
            toggleButton.querySelector('span').textContent = 'Recherche avancée' :
            toggleButton.innerHTML = '<i class="fas fa-sliders-h"></i> Recherche avancée';
    }
}
