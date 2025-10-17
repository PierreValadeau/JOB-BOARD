/**
 * Admin.js - JavaScript pour le panel administrateur
 * Gestion des interactions et appels AJAX
 */

// Variables globales
let currentTab = 'dashboard';
let currentPage = {
    users: 1,
    companies: 1,
    offers: 1,
    applications: 1
};

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    initTabs();
    loadDashboard();
    initSearchFilters();
});

// ================================
// GESTION DES ONGLETS
// ================================

function initTabs() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Retirer les classes actives
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabContents.forEach(content => content.classList.remove('active'));
            
            // Ajouter les classes actives
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
            
            currentTab = targetTab;
            
            // Charger le contenu de l'onglet
            loadTabContent(targetTab);
        });
    });
}

function loadTabContent(tab) {
    switch(tab) {
        case 'dashboard':
            loadDashboard();
            break;
        case 'users':
            loadUsers();
            break;
        case 'companies':
            loadCompanies();
            break;
        case 'offers':
            loadOffers();
            loadCompaniesForSelect();
            break;
        case 'applications':
            loadApplications();
            break;
    }
}

// ================================
// GESTION DES FILTRES ET RECHERCHE
// ================================

function initSearchFilters() {
    // Toutes les recherches et filtres ont été supprimés de l'interface admin
    // Le panel admin affiche maintenant simplement les listes complètes
}

// Fonction debounce pour limiter les appels API
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ================================
// DASHBOARD
// ================================

function loadDashboard() {
    fetch('../api/admin.php?action=dashboard')
        .then(response => response.json())
        .then(data => {
            console.log('Dashboard data:', data); // Debug
            if (!data.success) {
                showAlert('error', data.message || data.error || 'Erreur lors du chargement du dashboard');
                return;
            }
            displayDashboardStats(data.data);
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement du dashboard');
        });
}

function displayDashboardStats(stats) {
    const statsGrid = document.getElementById('stats-grid');
    statsGrid.innerHTML = `
        <div class="stat-card">
            <div class="stat-number">${stats.total_users || 0}</div>
            <div class="stat-label">Utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.total_companies || 0}</div>
            <div class="stat-label">Entreprises</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.total_offers || 0}</div>
            <div class="stat-label">Offres d'emploi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.total_applications || 0}</div>
            <div class="stat-label">Candidatures</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.recent_offers || 0}</div>
            <div class="stat-label">Offres cette semaine</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">${stats.recent_applications || 0}</div>
            <div class="stat-label">Candidatures cette semaine</div>
        </div>
    `;
}

function refreshDashboard() {
    loadDashboard();
    showAlert('success', 'Dashboard actualisé');
}

// ================================
// GESTION DES UTILISATEURS
// ================================

function loadUsers(page = 1) {
    // Plus de recherche ni de filtre de rôle
    const search = '';
    const role = '';
    
    const params = new URLSearchParams({
        action: 'get_users',
        page: page,
        search: search,
        role: role
    });
    
    fetch(`../api/admin.php?${params}`)
        .then(response => response.json())
        .then(data => {
            console.log('Users data:', data); // Debug
            if (!data.success) {
                showAlert('error', data.message || data.error || 'Erreur lors du chargement des utilisateurs');
                return;
            }
            displayUsersTable(data.users);
            displayPagination('users', data.current_page, data.pages);
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement des utilisateurs');
        });
}

function displayUsersTable(users) {
    const tableContainer = document.getElementById('users-table');
    
    if (!users || users.length === 0) {
        tableContainer.innerHTML = '<p class="text-center">Aucun utilisateur trouvé</p>';
        return;
    }
    
    let tableHtml = `
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom complet</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    users.forEach(user => {
        const roleClass = getRoleClass(user.role);
        const createdDate = new Date(user.created_at).toLocaleDateString('fr-FR');
        
        tableHtml += `
            <tr>
                <td>${user.user_id}</td>
                <td>${escapeHtml(user.first_name)} ${escapeHtml(user.last_name)}</td>
                <td>${escapeHtml(user.email)}</td>
                <td>${user.phone || '-'}</td>
                <td><span class="badge ${roleClass}">${getRoleLabel(user.role)}</span></td>
                <td>${createdDate}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editUser(${user.user_id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.user_id}, '${escapeHtml(user.first_name)} ${escapeHtml(user.last_name)}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableHtml += '</tbody></table>';
    tableContainer.innerHTML = tableHtml;
}

function openUserModal(userId = null) {
    const modal = document.getElementById('user-modal');
    const title = document.getElementById('user-modal-title');
    const form = document.getElementById('user-form');
    const passwordRequired = document.getElementById('password-required');
    
    form.reset();
    
    if (userId) {
        title.textContent = 'Modifier l\'utilisateur';
        passwordRequired.style.display = 'none';
        loadUserForEdit(userId);
    } else {
        title.textContent = 'Nouvel utilisateur';
        passwordRequired.style.display = 'inline';
        document.getElementById('user-password').required = true;
    }
    
    modal.classList.add('active');
}

function loadUserForEdit(userId) {
    fetch(`../api/admin.php?action=get_user&id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showAlert('error', data.message || 'Erreur lors du chargement de l\'utilisateur');
                return;
            }
            
            document.getElementById('user-id').value = data.user_id;
            document.getElementById('user-first-name').value = data.first_name;
            document.getElementById('user-last-name').value = data.last_name;
            document.getElementById('user-email').value = data.email;
            document.getElementById('user-phone').value = data.phone || '';
            document.getElementById('user-role').value = data.role;
            document.getElementById('user-password').required = false;
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement de l\'utilisateur');
        });
}

function saveUser() {
    const form = document.getElementById('user-form');
    const formData = new FormData(form);
    const userId = formData.get('user_id');
    
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key !== 'user_id') {
            data[key] = value;
        }
    }
    
    const url = userId ? 
        `../api/admin.php?action=update_user&id=${userId}` : 
        '../api/admin.php?action=create_user';
    
    const saveBtn = document.querySelector('#user-modal .btn-primary');
    const loading = saveBtn.querySelector('.loading');
    
    saveBtn.disabled = true;
    loading.style.display = 'inline-block';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.error) {
            showAlert('error', result.message || 'Erreur lors de la sauvegarde');
            return;
        }
        
        showAlert('success', result.message || 'Utilisateur sauvegardé avec succès');
        closeModal('user-modal');
        loadUsers(currentPage.users);
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('error', 'Erreur lors de la sauvegarde');
    })
    .finally(() => {
        saveBtn.disabled = false;
        loading.style.display = 'none';
    });
}

function editUser(userId) {
    openUserModal(userId);
}

function deleteUser(userId, userName) {
    openDeleteModal(
        `Supprimer l'utilisateur "${userName}" ?`,
        () => {
            fetch(`../api/admin.php?action=delete_user&id=${userId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    showAlert('error', result.message || 'Erreur lors de la suppression');
                    return;
                }
                
                showAlert('success', result.message || 'Utilisateur supprimé avec succès');
                loadUsers(currentPage.users);
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('error', 'Erreur lors de la suppression');
            });
        }
    );
}

// ================================
// GESTION DES ENTREPRISES
// ================================

function loadCompanies(page = 1) {
    // Plus de recherche
    const search = '';
    
    const params = new URLSearchParams({
        action: 'get_companies',
        page: page,
        search: search
    });
    
    fetch(`../api/admin.php?${params}`)
        .then(response => response.json())
        .then(data => {
            console.log('Companies data:', data); // Debug
            if (!data.success) {
                showAlert('error', data.message || data.error || 'Erreur lors du chargement des entreprises');
                return;
            }
            displayCompaniesTable(data.companies);
            displayPagination('companies', data.current_page, data.pages);
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement des entreprises');
        });
}

function displayCompaniesTable(companies) {
    const tableContainer = document.getElementById('companies-table');
    
    if (!companies || companies.length === 0) {
        tableContainer.innerHTML = '<p class="text-center">Aucune entreprise trouvée</p>';
        return;
    }
    
    let tableHtml = `
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Localisation</th>
                    <th>Site web</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    companies.forEach(company => {
        const createdDate = new Date(company.created_at).toLocaleDateString('fr-FR');
        
        tableHtml += `
            <tr>
                <td>${company.id_companies}</td>
                <td>${escapeHtml(company.name)}</td>
                <td>${escapeHtml(company.email)}</td>
                <td>${company.phone || '-'}</td>
                <td>${company.location || '-'}</td>
                <td>${company.website ? `<a href="${company.website}" target="_blank">Visiter</a>` : '-'}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editCompany(${company.id_companies})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteCompany(${company.id_companies}, '${escapeHtml(company.name)}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableHtml += '</tbody></table>';
    tableContainer.innerHTML = tableHtml;
}

function openCompanyModal(companyId = null) {
    const modal = document.getElementById('company-modal');
    const title = document.getElementById('company-modal-title');
    const form = document.getElementById('company-form');
    
    form.reset();
    
    if (companyId) {
        title.textContent = 'Modifier l\'entreprise';
        loadCompanyForEdit(companyId);
    } else {
        title.textContent = 'Nouvelle entreprise';
    }
    
    modal.classList.add('active');
}

function loadCompanyForEdit(companyId) {
    fetch(`../api/admin.php?action=get_company&id=${companyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showAlert('error', data.message || 'Erreur lors du chargement de l\'entreprise');
                return;
            }
            
            document.getElementById('company-id').value = data.id_companies;
            document.getElementById('company-name').value = data.name;
            document.getElementById('company-email').value = data.email;
            document.getElementById('company-phone').value = data.phone || '';
            document.getElementById('company-location').value = data.location || '';
            document.getElementById('company-website').value = data.website || '';
            document.getElementById('company-description').value = data.description || '';
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement de l\'entreprise');
        });
}

function saveCompany() {
    const form = document.getElementById('company-form');
    const formData = new FormData(form);
    const companyId = formData.get('company_id');
    
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key !== 'company_id') {
            data[key] = value;
        }
    }
    
    const url = companyId ? 
        `../api/admin.php?action=update_company&id=${companyId}` : 
        '../api/admin.php?action=create_company';
    
    const saveBtn = document.querySelector('#company-modal .btn-primary');
    const loading = saveBtn.querySelector('.loading');
    
    saveBtn.disabled = true;
    loading.style.display = 'inline-block';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.error) {
            showAlert('error', result.message || 'Erreur lors de la sauvegarde');
            return;
        }
        
        showAlert('success', result.message || 'Entreprise sauvegardée avec succès');
        closeModal('company-modal');
        loadCompanies(currentPage.companies);
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('error', 'Erreur lors de la sauvegarde');
    })
    .finally(() => {
        saveBtn.disabled = false;
        loading.style.display = 'none';
    });
}

function editCompany(companyId) {
    openCompanyModal(companyId);
}

function deleteCompany(companyId, companyName) {
    openDeleteModal(
        `Supprimer l'entreprise "${companyName}" ? Toutes les offres associées seront également supprimées.`,
        () => {
            fetch(`../api/admin.php?action=delete_company&id=${companyId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    showAlert('error', result.message || 'Erreur lors de la suppression');
                    return;
                }
                
                showAlert('success', result.message || 'Entreprise supprimée avec succès');
                loadCompanies(currentPage.companies);
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('error', 'Erreur lors de la suppression');
            });
        }
    );
}

// ================================
// GESTION DES OFFRES
// ================================

function loadOffers(page = 1) {
    // Plus de recherche ni de filtre
    const search = '';
    const contract_type = '';
    
    const params = new URLSearchParams({
        action: 'get_offers',
        page: page,
        search: search,
        contract_type: contract_type
    });
    
    fetch(`../api/admin.php?${params}`)
        .then(response => response.json())
        .then(data => {
            console.log('Offers data:', data); // Debug
            if (!data.success) {
                showAlert('error', data.message || data.error || 'Erreur lors du chargement des offres');
                return;
            }
            displayOffersTable(data.offers);
            displayPagination('offers', data.current_page, data.pages);
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement des offres');
        });
}

function displayOffersTable(offers) {
    const tableContainer = document.getElementById('offers-table');
    
    if (!offers || offers.length === 0) {
        tableContainer.innerHTML = '<p class="text-center">Aucune offre trouvée</p>';
        return;
    }
    
    let tableHtml = `
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Entreprise</th>
                    <th>Localisation</th>
                    <th>Contrat</th>
                    <th>Salaire</th>
                    <th>Date publication</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    offers.forEach(offer => {
        const publishedDate = new Date(offer.published_date).toLocaleDateString('fr-FR');
        const salary = offer.salary ? `${offer.salary}€` : '-';
        
        tableHtml += `
            <tr>
                <td>${offer.offers_id}</td>
                <td>${escapeHtml(offer.title)}</td>
                <td>${escapeHtml(offer.company_name)}</td>
                <td>${escapeHtml(offer.location)}</td>
                <td><span class="badge badge-info">${offer.contract_type}</span></td>
                <td>${salary}</td>
                <td>${publishedDate}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="editOffer(${offer.offers_id})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteOffer(${offer.offers_id}, '${escapeHtml(offer.title)}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableHtml += '</tbody></table>';
    tableContainer.innerHTML = tableHtml;
}

function loadCompaniesForSelect() {
    fetch('../api/admin.php?action=get_companies_list')
        .then(response => response.json())
        .then(companies => {
            const select = document.getElementById('offer-company');
            select.innerHTML = '<option value="">Sélectionner une entreprise</option>';
            
            companies.forEach(company => {
                select.innerHTML += `<option value="${company.id_companies}">${escapeHtml(company.name)}</option>`;
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des entreprises:', error);
        });
}

function openOfferModal(offerId = null) {
    const modal = document.getElementById('offer-modal');
    const title = document.getElementById('offer-modal-title');
    const form = document.getElementById('offer-form');
    
    form.reset();
    
    // Réinitialiser le mode d'entreprise par défaut
    document.querySelector('input[name="company_mode"][value="existing"]').checked = true;
    toggleCompanyMode();
    
    // Définir la date par défaut à aujourd'hui
    document.getElementById('offer-published-date').value = new Date().toISOString().split('T')[0];
    
    if (offerId) {
        title.textContent = 'Modifier l\'offre';
        loadOfferForEdit(offerId);
    } else {
        title.textContent = 'Nouvelle offre';
    }
    
    modal.classList.add('active');
}

function loadOfferForEdit(offerId) {
    fetch(`../api/admin.php?action=get_offer&id=${offerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                showAlert('error', data.message || 'Erreur lors du chargement de l\'offre');
                return;
            }
            
            document.getElementById('offer-id').value = data.offers_id;
            document.getElementById('offer-title').value = data.title;
            document.getElementById('offer-company').value = data.id_companies;
            document.getElementById('offer-contract-type').value = data.contract_type;
            document.getElementById('offer-location').value = data.location;
            document.getElementById('offer-salary').value = data.salary || '';
            document.getElementById('offer-published-date').value = data.published_date;
            document.getElementById('offer-description').value = data.description;
            document.getElementById('offer-long-description').value = data.long_description || '';
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement de l\'offre');
        });
}

function toggleCompanyMode() {
    const existingSection = document.getElementById('existing-company-section');
    const manualSection = document.getElementById('manual-company-section');
    const existingRadio = document.querySelector('input[name="company_mode"][value="existing"]');
    const manualRadio = document.querySelector('input[name="company_mode"][value="manual"]');
    
    if (existingRadio.checked) {
        existingSection.style.display = 'block';
        manualSection.style.display = 'none';
        // Rendre le champ entreprise requis
        document.getElementById('offer-company').required = true;
        // Supprimer l'exigence des champs manuels
        clearManualFieldRequirements();
    } else if (manualRadio.checked) {
        existingSection.style.display = 'none';
        manualSection.style.display = 'block';
        // Supprimer l'exigence du champ entreprise
        document.getElementById('offer-company').required = false;
        document.getElementById('offer-company').value = '';
        // Rendre certains champs manuels requis
        setManualFieldRequirements();
    }
}

function clearManualFieldRequirements() {
    document.getElementById('offer-company-name').required = false;
    document.getElementById('offer-company-email').required = false;
}

function setManualFieldRequirements() {
    document.getElementById('offer-company-name').required = true;
    document.getElementById('offer-company-email').required = true;
}

function saveOffer() {
    const form = document.getElementById('offer-form');
    const formData = new FormData(form);
    const offerId = formData.get('offer_id');
    const companyMode = formData.get('company_mode');
    
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key !== 'offer_id') {
            data[key] = value;
        }
    }
    
    // Ajouter le mode d'entreprise aux données
    data.company_mode = companyMode;
    
    console.log('Données envoyées:', data); // Debug
    
    const url = offerId ? 
        `../api/admin.php?action=update_offer&id=${offerId}` : 
        '../api/admin.php?action=create_offer';
    
    const saveBtn = document.querySelector('#offer-modal .btn-primary');
    const loading = saveBtn.querySelector('.loading');
    
    saveBtn.disabled = true;
    loading.style.display = 'inline-block';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        console.log('Réponse du serveur:', result); // Debug
        if (result.error) {
            let errorMessage = result.message || 'Erreur lors de la sauvegarde';
            if (result.details) {
                errorMessage += '\nDétails: ' + result.details.join(', ');
            }
            showAlert('error', errorMessage);
            return;
        }
        
        showAlert('success', result.message || 'Offre sauvegardée avec succès');
        closeModal('offer-modal');
        loadOffers(currentPage.offers);
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('error', 'Erreur lors de la sauvegarde');
    })
    .finally(() => {
        saveBtn.disabled = false;
        loading.style.display = 'none';
    });
}

function editOffer(offerId) {
    openOfferModal(offerId);
}

function deleteOffer(offerId, offerTitle) {
    openDeleteModal(
        `Supprimer l'offre "${offerTitle}" ? Toutes les candidatures associées seront également supprimées.`,
        () => {
            fetch(`../api/admin.php?action=delete_offer&id=${offerId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    showAlert('error', result.message || 'Erreur lors de la suppression');
                    return;
                }
                
                showAlert('success', result.message || 'Offre supprimée avec succès');
                loadOffers(currentPage.offers);
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('error', 'Erreur lors de la suppression');
            });
        }
    );
}

// ================================
// GESTION DES CANDIDATURES
// ================================

function loadApplications(page = 1) {
    // Plus de recherche ni de filtre de statut
    const search = '';
    const status = '';
    
    const params = new URLSearchParams({
        action: 'get_applications',
        page: page,
        search: search,
        status: status
    });
    
    fetch(`../api/admin.php?${params}`)
        .then(response => response.json())
        .then(data => {
            console.log('Applications data:', data); // Debug
            if (!data.success) {
                showAlert('error', data.message || data.error || 'Erreur lors du chargement des candidatures');
                return;
            }
            displayApplicationsTable(data.applications);
            displayPagination('applications', data.current_page, data.pages);
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('error', 'Erreur lors du chargement des candidatures');
        });
}

function displayApplicationsTable(applications) {
    const tableContainer = document.getElementById('applications-table');
    
    if (!applications || applications.length === 0) {
        tableContainer.innerHTML = '<p class="text-center">Aucune candidature trouvée</p>';
        return;
    }
    
    let tableHtml = `
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Candidat</th>
                    <th>Email</th>
                    <th>Offre</th>
                    <th>Entreprise</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
    `;
    
    applications.forEach(application => {
        const applicationDate = new Date(application.application_date).toLocaleDateString('fr-FR');
        const statusClass = getStatusClass(application.status);
        
        tableHtml += `
            <tr>
                <td>${application.id}</td>
                <td>${escapeHtml(application.applicant_name)}</td>
                <td>${escapeHtml(application.applicant_email)}</td>
                <td>${escapeHtml(application.job_title)}</td>
                <td>${escapeHtml(application.company_name)}</td>
                <td>${applicationDate}</td>
                <td>
                    <select class="form-control" onchange="updateApplicationStatus(${application.id}, this.value)">
                        <option value="pending" ${application.status === 'pending' ? 'selected' : ''}>En attente</option>
                        <option value="reviewed" ${application.status === 'reviewed' ? 'selected' : ''}>Examinée</option>
                        <option value="accepted" ${application.status === 'accepted' ? 'selected' : ''}>Acceptée</option>
                        <option value="rejected" ${application.status === 'rejected' ? 'selected' : ''}>Rejetée</option>
                    </select>
                </td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="deleteApplication(${application.id}, '${escapeHtml(application.applicant_name)}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tableHtml += '</tbody></table>';
    tableContainer.innerHTML = tableHtml;
}

function updateApplicationStatus(applicationId, status) {
    fetch(`../api/admin.php?action=update_application_status&id=${applicationId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(result => {
        if (result.error) {
            showAlert('error', result.message || 'Erreur lors de la mise à jour du statut');
            loadApplications(currentPage.applications); // Recharger pour annuler le changement
            return;
        }
        
        showAlert('success', result.message || 'Statut mis à jour avec succès');
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('error', 'Erreur lors de la mise à jour du statut');
        loadApplications(currentPage.applications);
    });
}

function viewApplication(applicationId) {
    // TODO: Implémenter une modal pour voir les détails de la candidature
    showAlert('info', 'Fonctionnalité à implémenter');
}

function deleteApplication(applicationId, applicantName) {
    openDeleteModal(
        `Supprimer la candidature de "${applicantName}" ?`,
        () => {
            fetch(`../api/admin.php?action=delete_application&id=${applicationId}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    showAlert('error', result.message || 'Erreur lors de la suppression');
                    return;
                }
                
                showAlert('success', result.message || 'Candidature supprimée avec succès');
                loadApplications(currentPage.applications);
            })
            .catch(error => {
                console.error('Erreur:', error);
                showAlert('error', 'Erreur lors de la suppression');
            });
        }
    );
}

// ================================
// GESTION DES MODALES
// ================================

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('active');
}

function openDeleteModal(message, confirmCallback) {
    const modal = document.getElementById('delete-modal');
    const messageElement = document.getElementById('delete-message');
    const confirmBtn = document.getElementById('confirm-delete-btn');
    
    messageElement.textContent = message;
    
    // Supprimer l'ancien gestionnaire d'événements
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    
    // Ajouter le nouveau gestionnaire
    newConfirmBtn.addEventListener('click', () => {
        const loading = newConfirmBtn.querySelector('.loading');
        newConfirmBtn.disabled = true;
        loading.style.display = 'inline-block';
        
        confirmCallback();
        
        setTimeout(() => {
            closeModal('delete-modal');
            newConfirmBtn.disabled = false;
            loading.style.display = 'none';
        }, 1000);
    });
    
    modal.classList.add('active');
}

// ================================
// PAGINATION
// ================================

function displayPagination(entity, currentPageNum, totalPages) {
    const paginationContainer = document.getElementById(`${entity}-pagination`);
    
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    let paginationHtml = '';
    
    // Bouton précédent
    if (currentPageNum > 1) {
        paginationHtml += `<button class="pagination-btn" onclick="changePage('${entity}', ${currentPageNum - 1})">‹ Précédent</button>`;
    }
    
    // Numéros de pages
    const startPage = Math.max(1, currentPageNum - 2);
    const endPage = Math.min(totalPages, currentPageNum + 2);
    
    if (startPage > 1) {
        paginationHtml += `<button class="pagination-btn" onclick="changePage('${entity}', 1)">1</button>`;
        if (startPage > 2) {
            paginationHtml += `<span>...</span>`;
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const activeClass = i === currentPageNum ? 'active' : '';
        paginationHtml += `<button class="pagination-btn ${activeClass}" onclick="changePage('${entity}', ${i})">${i}</button>`;
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            paginationHtml += `<span>...</span>`;
        }
        paginationHtml += `<button class="pagination-btn" onclick="changePage('${entity}', ${totalPages})">${totalPages}</button>`;
    }
    
    // Bouton suivant
    if (currentPageNum < totalPages) {
        paginationHtml += `<button class="pagination-btn" onclick="changePage('${entity}', ${currentPageNum + 1})">Suivant ›</button>`;
    }
    
    paginationContainer.innerHTML = paginationHtml;
}

function changePage(entity, page) {
    currentPage[entity] = page;
    
    switch(entity) {
        case 'users':
            loadUsers(page);
            break;
        case 'companies':
            loadCompanies(page);
            break;
        case 'offers':
            loadOffers(page);
            break;
        case 'applications':
            loadApplications(page);
            break;
    }
}

// ================================
// UTILITAIRES
// ================================

function showAlert(type, message) {
    const alertContainer = document.getElementById('alert-container');
    const alertId = 'alert-' + Date.now();
    
    const alertTypes = {
        success: 'alert-success',
        error: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    };
    
    const alertHtml = `
        <div id="${alertId}" class="alert ${alertTypes[type] || 'alert-info'} fade-in">
            ${escapeHtml(message)}
            <button style="float: right; background: none; border: none; font-size: 1.2em; cursor: pointer;" onclick="removeAlert('${alertId}')">&times;</button>
        </div>
    `;
    
    alertContainer.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-supprimer après 5 secondes
    setTimeout(() => {
        removeAlert(alertId);
    }, 5000);
}

function removeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.remove();
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
}

function getRoleClass(role) {
    const roleClasses = {
        'admin': 'badge-danger',
        'recruiter': 'badge-warning',
        'candidate': 'badge-info'
    };
    return roleClasses[role] || 'badge-info';
}

function getRoleLabel(role) {
    const roleLabels = {
        'admin': 'Administrateur',
        'recruiter': 'Recruteur',
        'candidate': 'Candidat'
    };
    return roleLabels[role] || role;
}

function getStatusClass(status) {
    const statusClasses = {
        'pending': 'badge-warning',
        'reviewed': 'badge-info',
        'accepted': 'badge-success',
        'rejected': 'badge-danger'
    };
    return statusClasses[status] || 'badge-info';
}