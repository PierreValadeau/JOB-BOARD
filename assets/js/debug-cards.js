// Script de test pour les boutons "En savoir plus"
console.log('🔍 Script de test chargé...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 DOM chargé, initialisation des tests...');
    
    // Test 1: Vérifier que les boutons existent
    const learnMoreButtons = document.querySelectorAll('.btn-learn-more');
    console.log(`📊 Trouvé ${learnMoreButtons.length} boutons "En savoir plus"`);
    
    // Test 2: Vérifier les cartes d'emploi
    const jobCards = document.querySelectorAll('.job-card');
    console.log(`🃏 Trouvé ${jobCards.length} cartes d'emploi`);
    
    // Test 3: Ajouter les événements de clic avec debug
    learnMoreButtons.forEach((button, index) => {
        console.log(`🔧 Configuration du bouton ${index + 1}...`);
        
        button.addEventListener('click', function(e) {
            console.log(`🎯 Clic détecté sur le bouton ${index + 1}!`);
            e.preventDefault();
            e.stopPropagation();
            
            const jobCard = this.closest('.job-card');
            if (jobCard) {
                console.log('📋 Carte d\'emploi trouvée, basculement...');
                testToggleExpansion(jobCard, this, index + 1);
            } else {
                console.log('❌ Aucune carte d\'emploi trouvée!');
            }
            
            return false;
        });
    });
});

function testToggleExpansion(jobCard, button, cardNumber) {
    console.log(`🔄 Basculement de la carte ${cardNumber}...`);
    
    const isExpanded = jobCard.classList.contains('expanded');
    console.log(`📏 État actuel: ${isExpanded ? 'étendue' : 'réduite'}`);
    
    if (isExpanded) {
        console.log('📉 Réduction de la carte...');
        jobCard.classList.remove('expanded');
        button.textContent = 'En savoir plus';
        
        const expandedContent = jobCard.querySelector('.job-expanded-content');
        if (expandedContent) {
            expandedContent.style.display = 'none';
        }
    } else {
        console.log('📈 Extension de la carte...');
        
        // Fermer les autres cartes
        document.querySelectorAll('.job-card.expanded').forEach(card => {
            if (card !== jobCard) {
                card.classList.remove('expanded');
                const otherButton = card.querySelector('.btn-learn-more');
                if (otherButton) otherButton.textContent = 'En savoir plus';
                
                const otherContent = card.querySelector('.job-expanded-content');
                if (otherContent) otherContent.style.display = 'none';
            }
        });
        
        // Ouvrir la carte actuelle
        jobCard.classList.add('expanded');
        button.textContent = 'Réduire';
        
        // Remplir et afficher le contenu
        testPopulateContent(jobCard);
        
        const expandedContent = jobCard.querySelector('.job-expanded-content');
        if (expandedContent) {
            expandedContent.style.display = 'block';
            console.log('✅ Contenu étendu affiché!');
        } else {
            console.log('❌ Contenu étendu non trouvé!');
        }
    }
}

function testPopulateContent(jobCard) {
    console.log('📝 Remplissage du contenu étendu...');
    
    const data = {
        description: jobCard.getAttribute('data-full-description'),
        salary: jobCard.getAttribute('data-full-salary'),
        workingTime: jobCard.getAttribute('data-working-time'),
        location: jobCard.getAttribute('data-precise-location'),
        company: jobCard.getAttribute('data-company-info'),
        benefits: jobCard.getAttribute('data-benefits')
    };
    
    console.log('📊 Données récupérées:', data);
    
    // Remplir les éléments
    const elements = {
        '.expanded-description': data.description,
        '.expanded-salary': data.salary,
        '.expanded-working-time': data.workingTime,
        '.expanded-location': data.location,
        '.expanded-company': data.company,
        '.expanded-benefits': data.benefits
    };
    
    for (const [selector, content] of Object.entries(elements)) {
        const element = jobCard.querySelector(selector);
        if (element) {
            element.textContent = content || 'Information non disponible';
            console.log(`✅ ${selector} rempli`);
        } else {
            console.log(`❌ ${selector} non trouvé`);
        }
    }
}