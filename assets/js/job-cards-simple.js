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
        const descriptionMore = card.querySelector('.job-description-more');
        const isExpanded = card.classList.contains('expanded');
        
        console.log('Toggle card - isExpanded:', isExpanded);
        console.log('Description more element found:', !!descriptionMore);
        
        if (isExpanded) {
            // Réduire - masquer la suite
            card.classList.remove('expanded');
            button.textContent = 'En savoir plus';
            if (descriptionMore) {
                descriptionMore.style.display = 'none';
            }
            console.log('Card fermée');
        } else {
            // Fermer les autres cartes d'abord
            document.querySelectorAll('.job-card.expanded').forEach(function(otherCard) {
                if (otherCard !== card) {
                    otherCard.classList.remove('expanded');
                    const otherButton = otherCard.querySelector('.btn-learn-more');
                    const otherContent = otherCard.querySelector('.job-description-more');
                    if (otherButton) otherButton.textContent = 'En savoir plus';
                    if (otherContent) otherContent.style.display = 'none';
                }
            });
            
            // Remplir le contenu et l'afficher
            if (descriptionMore) {
                fillDescriptionMore(card, descriptionMore);
                // Forcer l'affichage
                descriptionMore.style.display = 'block';
            }
            
            // Ouvrir la carte actuelle
            card.classList.add('expanded');
            button.textContent = 'Réduire';
            console.log('Card ouverte avec contenu visible');
        }
    }

    function fillDescriptionMore(card, descriptionMoreElement) {
        // Définir des continuités naturelles pour chaque poste
        const cardTexts = {
            'Développeur Full Stack': ' utilisant les dernières technologies. Vous travaillerez sur des projets variés incluant le développement d\'APIs REST, l\'intégration de bases de données et la création d\'interfaces utilisateur modernes. Nous recherchons une personne passionnée par le code propre, les bonnes pratiques et l\'innovation technologique.',
            'Chef de Projet Digital': '. Vous serez responsable de la planification, du suivi et de la livraison de projets web et mobile complexes. Votre mission inclut la coordination des équipes techniques, la gestion des budgets et la relation client. Vous maîtrisez les méthodologies agiles et avez une excellente capacité de communication.',
            'Commercial B2B': '. Vous serez responsable du développement commercial sur la région PACA, de la prospection à la signature de contrats. Votre mission consiste à identifier de nouveaux clients, présenter nos solutions, négocier et fidéliser un portefeuille clients de qualité.'
        };
        
        // Trouver le titre du poste
        const jobTitle = card.querySelector('.job-title').textContent.trim();
        console.log('Titre du poste:', jobTitle);
        
        // Récupérer le texte de continuation
        let continuationText = cardTexts[jobTitle] || '. Suite de la description non disponible pour ce poste.';
        
        // Afficher la suite avec la même police
        descriptionMoreElement.textContent = continuationText;
        console.log('Continuation ajoutée:', continuationText);
    }
})();