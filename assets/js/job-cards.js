// Script simple pour les boutons "En savoir plus" - Version compacte
(function() {
    'use strict';
    
    // Attendre que le DOM soit chargé
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initButtons);
    } else {
        initButtons();
    }
    
    function initButtons() {
        console.log('🚀 Initialisation des boutons "En savoir plus"...');
        
        // Sélectionner tous les boutons
        const buttons = document.querySelectorAll('.btn-learn-more');
        console.log(`Trouvé ${buttons.length} boutons`);
        
        // Ajouter l'événement à chaque bouton
        buttons.forEach(function(button, index) {
            button.addEventListener('click', function(event) {
                // Empêcher tout comportement par défaut
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();
                
                console.log(`Clic sur bouton ${index + 1}`);
                
                // Trouver la carte parente
                const card = button.closest('.job-card');
                if (card) {
                    toggleCard(card, button);
                } else {
                    console.error('Carte non trouvée');
                }
                
                return false;
            });
        });
    }
    
    function toggleCard(card, button) {
        const expandedContent = card.querySelector('.job-expanded-content');
        const isExpanded = card.classList.contains('expanded');
        
        if (isExpanded) {
            // Réduire
            card.classList.remove('expanded');
            button.textContent = 'En savoir plus';
            
            if (expandedContent) {
                expandedContent.style.display = 'none';
            }
        } else {
            // Fermer les autres cartes d'abord
            document.querySelectorAll('.job-card.expanded').forEach(function(otherCard) {
                if (otherCard !== card) {
                    otherCard.classList.remove('expanded');
                    const otherButton = otherCard.querySelector('.btn-learn-more');
                    const otherContent = otherCard.querySelector('.job-expanded-content');
                    if (otherButton) otherButton.textContent = 'En savoir plus';
                    if (otherContent) otherContent.style.display = 'none';
                }
            });
            
            // Ouvrir la carte actuelle
            card.classList.add('expanded');
            button.textContent = 'Réduire';
            
            if (expandedContent) {
                fillContentCompact(card);
                expandedContent.style.display = 'block';
            }
        }
    }
    
    function moveCardToTop(originalCard, callback) {
        const selectedRow = document.getElementById('selectedJobRow');
        const jobsContainer = document.querySelector('.jobs-container');
        const jobsGrid = document.getElementById('jobsGrid');
        
        // Cloner la carte
        const clonedCard = originalCard.cloneNode(true);
        
        // Marquer la carte originale
        originalCard.classList.add('selected-original');
        
        // Ajouter la classe au container
        jobsContainer.classList.add('has-selection');
        
        // Ajouter animation à la carte originale
        originalCard.classList.add('moving-up');
        
        setTimeout(function() {
            // Afficher la rangée du haut et y placer la carte clonée
            selectedRow.style.display = 'block';
            selectedRow.appendChild(clonedCard);
            selectedRow.classList.add('active');
            
            // Réattacher les événements à la carte clonée
            setupCardEvents(clonedCard);
            
            // Scroll vers le haut
            selectedRow.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
            
            if (callback) callback();
        }, 300);
    }
    
    function setupCardEvents(card) {
        const button = card.querySelector('.btn-learn-more');
        if (button) {
            // Nettoyer les anciens événements
            button.replaceWith(button.cloneNode(true));
            const newButton = card.querySelector('.btn-learn-more');
            
            newButton.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                // Si c'est la carte du haut, la remettre en bas
                if (card.closest('#selectedJobRow')) {
                    moveCardToBottom(card);
                }
                
                return false;
            });
        }
    }
    
    function moveCardToBottom(selectedCard) {
        const selectedRow = document.getElementById('selectedJobRow');
        const jobsContainer = document.querySelector('.jobs-container');
        
        // Trouver la carte originale correspondante
        const originalCards = document.querySelectorAll('#jobsGrid .job-card.selected-original');
        
        // Réinitialiser tout
        originalCards.forEach(function(originalCard) {
            originalCard.classList.remove('selected-original', 'moving-up', 'expanded');
            const originalButton = originalCard.querySelector('.btn-learn-more');
            if (originalButton) {
                originalButton.textContent = 'En savoir plus';
            }
            
            // Masquer le contenu étendu
            const originalContent = originalCard.querySelector('.job-expanded-content');
            if (originalContent) {
                originalContent.style.display = 'none';
                const sections = originalCard.querySelectorAll('.expanded-section');
                sections.forEach(function(section) {
                    section.classList.remove('animate');
                });
            }
        });
        
        // Cacher la rangée du haut
        selectedRow.classList.remove('active');
        setTimeout(function() {
            selectedRow.style.display = 'none';
            selectedRow.innerHTML = '';
            jobsContainer.classList.remove('has-selection');
        }, 500);
    }
    
    function collapseCard(card, button, expandedContent) {
        // Si c'est la carte du haut, la remettre en bas
        if (card.closest('#selectedJobRow')) {
            moveCardToBottom(card);
            return;
        }
        
        // Sinon, réduction normale
        card.classList.remove('expanded');
        button.textContent = 'En savoir plus';
        
        if (expandedContent) {
            // Supprimer les classes d'animation
            const sections = card.querySelectorAll('.expanded-section');
            sections.forEach(function(section) {
                section.classList.remove('animate');
            });
            
            expandedContent.classList.remove('has-scroll');
            
            setTimeout(function() {
                expandedContent.style.display = 'none';
            }, 300);
        }
    }
    
    function fillContentCompact(card) {
        // Récupérer les données essentielles seulement
        const salary = card.getAttribute('data-full-salary') || 'Non spécifié';
        const workingTime = card.getAttribute('data-working-time') || 'Non spécifié';
        const location = card.getAttribute('data-precise-location') || 'Non spécifié';
        const benefits = card.getAttribute('data-benefits') || 'Non spécifié';
        
        // Raccourcir les textes pour un affichage compact
        const shortSalary = salary.length > 50 ? salary.substring(0, 47) + '...' : salary;
        const shortWorkingTime = workingTime.length > 40 ? workingTime.substring(0, 37) + '...' : workingTime;
        const shortLocation = location.length > 40 ? location.substring(0, 37) + '...' : location;
        const shortBenefits = benefits.length > 50 ? benefits.substring(0, 47) + '...' : benefits;
        
        // Remplir les éléments
        const elements = [
            { selector: '.expanded-salary', content: shortSalary },
            { selector: '.expanded-working-time', content: shortWorkingTime },
            { selector: '.expanded-location', content: shortLocation },
            { selector: '.expanded-benefits', content: shortBenefits }
        ];
        
        elements.forEach(function(item) {
            const element = card.querySelector(item.selector);
            if (element) {
                element.textContent = item.content;
            }
        });
    }
})();