<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}
require_once '../../model/UserModel.php';
require_once '../../model/JobModel.php';
require_once '../../model/CompanyModel.php';
require_once '../../model/ApplicationModel.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Job Board</title>
    <link rel="stylesheet" href="../../assets/css/common.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../assets/css/admin/admin.css?v=<?php echo time(); ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="admin-header">
        <h1 class="admin-title">
            <i class="fas fa-cogs"></i>
            Administration
        </h1>
        <div class="admin-user">
            <span>Bienvenue, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <button class="logout-btn" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i>
                Déconnexion
            </button>
        </div>
    </div>
    <div class="admin-container">
        <div class="tabs">
            <button class="tab-button active" onclick="openTab(event, 'users')">
                <i class="fas fa-users"></i>
                Utilisateurs
            </button>
            <button class="tab-button" onclick="openTab(event, 'jobs')">
                <i class="fas fa-briefcase"></i>
                Offres d'emploi
            </button>
            <button class="tab-button" onclick="openTab(event, 'companies')">
                <i class="fas fa-building"></i>
                Entreprises
            </button>
            <button class="tab-button" onclick="openTab(event, 'applications')">
                <i class="fas fa-file-alt"></i>
                Candidatures
            </button>
        </div>
        <div id="users" class="tab-content active">
            <div class="table-controls">
                <input type="text" class="search-box" id="userSearch" placeholder="Rechercher un utilisateur...">
                <button class="btn btn-primary" onclick="openCreateUserModal()">
                    <i class="fas fa-plus"></i>
                    Nouvel utilisateur
                </button>
            </div>
            <div id="usersTable"></div>
        </div>
        <div id="jobs" class="tab-content">
            <div class="table-controls">
                <input type="text" class="search-box" id="jobSearch" placeholder="Rechercher une offre...">
                <button class="btn btn-primary" onclick="openCreateJobModal()">
                    <i class="fas fa-plus"></i>
                    Nouvelle offre
                </button>
            </div>
            <div id="jobsTable"></div>
        </div>
        <div id="companies" class="tab-content">
            <div class="table-controls">
                <input type="text" class="search-box" id="companySearch" placeholder="Rechercher une entreprise...">
                <button class="btn btn-primary" onclick="openCreateCompanyModal()">
                    <i class="fas fa-plus"></i>
                    Nouvelle entreprise
                </button>
            </div>
            <div id="companiesTable"></div>
        </div>
        <div id="applications" class="tab-content">
            <div class="table-controls">
                <input type="text" class="search-box" id="applicationSearch" placeholder="Rechercher une candidature...">
            </div>
            <div id="applicationsTable"></div>
        </div>
    </div>
    <div id="userModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="userModalTitle">Nouvel utilisateur</h2>
                <span class="close" onclick="closeModal('userModal')">&times;</span>
            </div>
            <form id="userForm">
                <input type="hidden" id="userId" name="user_id">
                <div class="form-group">
                    <label class="form-label" for="firstName">Prénom</label>
                    <input type="text" class="form-control" id="firstName" name="first_name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="lastName">Nom</label>
                    <input type="text" class="form-control" id="lastName" name="last_name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="phone">Téléphone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label class="form-label" for="role">Rôle</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="candidate">Candidat</option>
                        <option value="company">Entreprise</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <div class="form-group" id="passwordGroup">
                    <label class="form-label" for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('userModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    <div id="jobModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="jobModalTitle">Nouvelle offre d'emploi</h2>
                <span class="close" onclick="closeModal('jobModal')">&times;</span>
            </div>
            <form id="jobForm">
                <input type="hidden" id="jobId" name="job_id">
                <div class="form-group">
                    <label class="form-label" for="jobTitle">Titre</label>
                    <input type="text" class="form-control" id="jobTitle" name="title" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="jobDescription">Description</label>
                    <textarea class="form-control" id="jobDescription" name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="jobLocation">Localisation</label>
                    <input type="text" class="form-control" id="jobLocation" name="location" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="jobSalary">Salaire</label>
                    <input type="text" class="form-control" id="jobSalary" name="salary">
                </div>
                <div class="form-group">
                    <label class="form-label" for="jobType">Type d'emploi</label>
                    <select class="form-control" id="jobType" name="employment_type" required>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                        <option value="Stage">Stage</option>
                        <option value="Freelance">Freelance</option>
                        <option value="Alternance">Alternance</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="jobCompany">Entreprise</label>
                    <select class="form-control" id="jobCompany" name="company_id" required>
                        <option value="">Sélectionner une entreprise</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('jobModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    <div id="companyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="companyModalTitle">Nouvelle entreprise</h2>
                <span class="close" onclick="closeModal('companyModal')">&times;</span>
            </div>
            <form id="companyForm">
                <input type="hidden" id="companyId" name="company_id">
                <div class="form-group">
                    <label class="form-label" for="companyName">Nom de l'entreprise</label>
                    <input type="text" class="form-control" id="companyName" name="name" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="companyDescription">Description</label>
                    <textarea class="form-control" id="companyDescription" name="description" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label" for="companyLocation">Localisation</label>
                    <input type="text" class="form-control" id="companyLocation" name="location">
                </div>
                <div class="form-group">
                    <label class="form-label" for="companyWebsite">Site web</label>
                    <input type="url" class="form-control" id="companyWebsite" name="website">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('companyModal')">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let currentTab = 'users';
        let currentPage = {
            users: 1,
            jobs: 1,
            companies: 1,
            applications: 1
        };
        const itemsPerPage = 10;
        function openTab(evt, tabName) {
            var i, tabcontent, tabbuttons;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }
            tabbuttons = document.getElementsByClassName("tab-button");
            for (i = 0; i < tabbuttons.length; i++) {
                tabbuttons[i].classList.remove("active");
            }
            document.getElementById(tabName).classList.add("active");
            evt.currentTarget.classList.add("active");
            currentTab = tabName;
            loadData(tabName);
        }
        function loadData(type, page = 1) {
            currentPage[type] = page;
            const tableId = type + 'Table';
            document.getElementById(tableId).innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
            fetch(`../../api/admin/${type}.php?page=${page}&limit=${itemsPerPage}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                    }
                    return response.text();
                })
                .then(text => {
                    try {
                        const data = JSON.parse(text);
                        if (data.success) {
                            displayData(type, data);
                        } else {
                            document.getElementById(tableId).innerHTML = `<div class="error">❌ ${data.message}</div>`;
                        }
                    } catch (e) {
                        console.error('JSON Parse Error:', e);
                        console.error('Response text:', text);
                        document.getElementById(tableId).innerHTML = `<div class="error">❌ Erreur de format de réponse. Voir la console pour plus de détails.</div>`;
                    }
                })
                .catch(error => {
                    console.error('Fetch Error:', error);
                    document.getElementById(tableId).innerHTML = `<div class="error">❌ Erreur de connexion: ${error.message}</div>`;
                });
        }
        function displayData(type, data) {
            const tableId = type + 'Table';
            let html = '';
            if (type === 'users') {
                html = displayUsersTable(data);
            } else if (type === 'jobs') {
                html = displayJobsTable(data);
            } else if (type === 'companies') {
                html = displayCompaniesTable(data);
            } else if (type === 'applications') {
                html = displayApplicationsTable(data);
            }
            document.getElementById(tableId).innerHTML = html;
        }
        function displayUsersTable(data) {
            let html = '<table class="data-table"><thead><tr>';
            html += '<th>ID</th><th>Nom complet</th><th>Email</th><th>Téléphone</th><th>Rôle</th><th>Actions</th>';
            html += '</tr></thead><tbody>';
            data.users.forEach(user => {
                html += '<tr>';
                html += `<td>${user.user_id}</td>`;
                html += `<td>${user.first_name} ${user.last_name}</td>`;
                html += `<td>${user.email}</td>`;
                html += `<td>${user.phone || '-'}</td>`;
                html += `<td><span class="role-badge role-${user.role}">${user.role}</span></td>`;
                html += '<td class="actions">';
                html += `<button class="btn btn-warning btn-sm" onclick="editUser(${user.user_id})"><i class="fas fa-edit"></i></button>`;
                html += `<button class="btn btn-danger btn-sm" onclick="deleteUser(${user.user_id})"><i class="fas fa-trash"></i></button>`;
                html += '</td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
            html += generatePagination('users', data.total);
            return html;
        }
        function displayJobsTable(data) {
            let html = '<table class="data-table"><thead><tr>';
            html += '<th>ID</th><th>Titre</th><th>Entreprise</th><th>Location</th><th>Type</th><th>Actions</th>';
            html += '</tr></thead><tbody>';
            data.jobs.forEach(job => {
                html += '<tr>';
                html += `<td>${job.job_id}</td>`;
                html += `<td>${job.title}</td>`;
                html += `<td>${job.company_name || '-'}</td>`;
                html += `<td>${job.location}</td>`;
                html += `<td>${job.employment_type}</td>`;
                html += '<td class="actions">';
                html += `<button class="btn btn-warning btn-sm" onclick="editJob(${job.job_id})"><i class="fas fa-edit"></i></button>`;
                html += `<button class="btn btn-danger btn-sm" onclick="deleteJob(${job.job_id})"><i class="fas fa-trash"></i></button>`;
                html += '</td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
            html += generatePagination('jobs', data.total);
            return html;
        }
        function displayCompaniesTable(data) {
            let html = '<table class="data-table"><thead><tr>';
            html += '<th>ID</th><th>Nom</th><th>Location</th><th>Site web</th><th>Actions</th>';
            html += '</tr></thead><tbody>';
            data.companies.forEach(company => {
                html += '<tr>';
                html += `<td>${company.company_id}</td>`;
                html += `<td>${company.name}</td>`;
                html += `<td>${company.location || '-'}</td>`;
                html += `<td>${company.website ? `<a href="${company.website}" target="_blank">${company.website}</a>` : '-'}</td>`;
                html += '<td class="actions">';
                html += `<button class="btn btn-warning btn-sm" onclick="editCompany(${company.company_id})"><i class="fas fa-edit"></i></button>`;
                html += `<button class="btn btn-danger btn-sm" onclick="deleteCompany(${company.company_id})"><i class="fas fa-trash"></i></button>`;
                html += '</td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
            html += generatePagination('companies', data.total);
            return html;
        }
        function displayApplicationsTable(data) {
            let html = '<table class="data-table"><thead><tr>';
            html += '<th>ID</th><th>Candidat</th><th>Offre</th><th>Entreprise</th><th>Date</th><th>Actions</th>';
            html += '</tr></thead><tbody>';
            data.applications.forEach(app => {
                html += '<tr>';
                html += `<td>${app.application_id}</td>`;
                html += `<td>${app.user_name}</td>`;
                html += `<td>${app.job_title}</td>`;
                html += `<td>${app.company_name}</td>`;
                html += `<td>${formatDate(app.application_date)}</td>`;
                html += '<td class="actions">';
                html += `<button class="btn btn-danger btn-sm" onclick="deleteApplication(${app.application_id})"><i class="fas fa-trash"></i></button>`;
                html += '</td>';
                html += '</tr>';
            });
            html += '</tbody></table>';
            html += generatePagination('applications', data.total);
            return html;
        }
        function generatePagination(type, total) {
            const totalPages = Math.ceil(total / itemsPerPage);
            const current = currentPage[type];
            if (totalPages <= 1) return '';
            let html = '<div class="pagination">';
            if (current > 1) {
                html += `<button class="page-btn" onclick="loadData('${type}', ${current - 1})">« Précédent</button>`;
            }
            for (let i = Math.max(1, current - 2); i <= Math.min(totalPages, current + 2); i++) {
                html += `<button class="page-btn ${i === current ? 'active' : ''}" onclick="loadData('${type}', ${i})">${i}</button>`;
            }
            if (current < totalPages) {
                html += `<button class="page-btn" onclick="loadData('${type}', ${current + 1})">Suivant »</button>`;
            }
            html += '</div>';
            return html;
        }
        function openCreateUserModal() {
            document.getElementById('userModalTitle').textContent = 'Nouvel utilisateur';
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('password').required = true;
            document.getElementById('userModal').style.display = 'block';
        }
        function openCreateJobModal() {
            document.getElementById('jobModalTitle').textContent = 'Nouvelle offre d\'emploi';
            document.getElementById('jobForm').reset();
            document.getElementById('jobId').value = '';
            loadCompaniesForSelect();
            document.getElementById('jobModal').style.display = 'block';
        }
        function openCreateCompanyModal() {
            document.getElementById('companyModalTitle').textContent = 'Nouvelle entreprise';
            document.getElementById('companyForm').reset();
            document.getElementById('companyId').value = '';
            document.getElementById('companyModal').style.display = 'block';
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
        function editUser(id) {
            fetch(`../../api/admin/users.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.user;
                        document.getElementById('userModalTitle').textContent = 'Modifier l\'utilisateur';
                        document.getElementById('userId').value = user.user_id;
                        document.getElementById('firstName').value = user.first_name;
                        document.getElementById('lastName').value = user.last_name;
                        document.getElementById('email').value = user.email;
                        document.getElementById('phone').value = user.phone || '';
                        document.getElementById('role').value = user.role;
                        document.getElementById('passwordGroup').style.display = 'none';
                        document.getElementById('password').required = false;
                        document.getElementById('userModal').style.display = 'block';
                    }
                });
        }
        function editJob(id) {
            fetch(`../../api/admin/jobs.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const job = data.job;
                        document.getElementById('jobModalTitle').textContent = 'Modifier l\'offre';
                        document.getElementById('jobId').value = job.job_id;
                        document.getElementById('jobTitle').value = job.title;
                        document.getElementById('jobDescription').value = job.description;
                        document.getElementById('jobLocation').value = job.location;
                        document.getElementById('jobSalary').value = job.salary || '';
                        document.getElementById('jobType').value = job.employment_type;
                        loadCompaniesForSelect(job.company_id);
                        document.getElementById('jobModal').style.display = 'block';
                    }
                });
        }
        function editCompany(id) {
            fetch(`../../api/admin/companies.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const company = data.company;
                        document.getElementById('companyModalTitle').textContent = 'Modifier l\'entreprise';
                        document.getElementById('companyId').value = company.company_id;
                        document.getElementById('companyName').value = company.name;
                        document.getElementById('companyDescription').value = company.description || '';
                        document.getElementById('companyLocation').value = company.location || '';
                        document.getElementById('companyWebsite').value = company.website || '';
                        document.getElementById('companyModal').style.display = 'block';
                    }
                });
        }
        function deleteUser(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                fetch(`../../api/admin/users.php`, {
                    method: 'DELETE',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadData('users', currentPage.users);
                        showNotification('Utilisateur supprimé avec succès', 'success');
                    } else {
                        showNotification('Erreur lors de la suppression', 'error');
                    }
                });
            }
        }
        function deleteJob(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')) {
                fetch(`../../api/admin/jobs.php`, {
                    method: 'DELETE',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadData('jobs', currentPage.jobs);
                        showNotification('Offre supprimée avec succès', 'success');
                    } else {
                        showNotification('Erreur lors de la suppression', 'error');
                    }
                });
            }
        }
        function deleteCompany(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')) {
                fetch(`../../api/admin/companies.php`, {
                    method: 'DELETE',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadData('companies', currentPage.companies);
                        showNotification('Entreprise supprimée avec succès', 'success');
                    } else {
                        showNotification('Erreur lors de la suppression', 'error');
                    }
                });
            }
        }
        function deleteApplication(id) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette candidature ?')) {
                fetch(`../../api/admin/applications.php`, {
                    method: 'DELETE',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({id: id})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadData('applications', currentPage.applications);
                        showNotification('Candidature supprimée avec succès', 'success');
                    } else {
                        showNotification('Erreur lors de la suppression', 'error');
                    }
                });
            }
        }
        function loadCompaniesForSelect(selectedId = null) {
            fetch('../../api/admin/companies.php?all=1')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById('jobCompany');
                        select.innerHTML = '<option value="">Sélectionner une entreprise</option>';
                        data.companies.forEach(company => {
                            const option = document.createElement('option');
                            option.value = company.company_id;
                            option.textContent = company.name;
                            if (selectedId && company.company_id == selectedId) {
                                option.selected = true;
                            }
                            select.appendChild(option);
                        });
                    }
                });
        }
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('fr-FR');
        }
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = type;
            notification.textContent = message;
            notification.style.position = 'fixed';
            notification.style.top = '20px';
            notification.style.right = '20px';
            notification.style.zIndex = '9999';
            notification.style.padding = '1rem';
            notification.style.borderRadius = '8px';
            document.body.appendChild(notification);
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 3000);
        }
        function logout() {
            fetch('../../api/logout.php', {method: 'POST'})
                .then(() => {
                    window.location.href = '../login.php';
                });
        }
        document.getElementById('userForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const method = data.user_id ? 'PUT' : 'POST';
            fetch('../../api/admin/users.php', {
                method: method,
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeModal('userModal');
                    loadData('users', currentPage.users);
                    showNotification('Utilisateur enregistré avec succès', 'success');
                } else {
                    showNotification(result.message, 'error');
                }
            });
        });
        document.getElementById('jobForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const method = data.job_id ? 'PUT' : 'POST';
            fetch('../../api/admin/jobs.php', {
                method: method,
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeModal('jobModal');
                    loadData('jobs', currentPage.jobs);
                    showNotification('Offre enregistrée avec succès', 'success');
                } else {
                    showNotification(result.message, 'error');
                }
            });
        });
        document.getElementById('companyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            const method = data.company_id ? 'PUT' : 'POST';
            fetch('../../api/admin/companies.php', {
                method: method,
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    closeModal('companyModal');
                    loadData('companies', currentPage.companies);
                    showNotification('Entreprise enregistrée avec succès', 'success');
                } else {
                    showNotification(result.message, 'error');
                }
            });
        });
        window.onclick = function(event) {
            const modals = ['userModal', 'jobModal', 'companyModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            loadData('users');
        });
    </script>
</body>
</html>