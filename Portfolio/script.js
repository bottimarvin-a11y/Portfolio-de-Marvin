// Search functionality
const searchInput = document.getElementById('searchInput');
const projectCards = document.querySelectorAll('.project-card');

searchInput.addEventListener('input', (e) => {
  const searchTerm = e.target.value.toLowerCase();

  projectCards.forEach(card => {
    const title = card.querySelector('.project-title').textContent.toLowerCase();
    if (title.includes(searchTerm)) {
      card.style.display = 'flex';
    } else {
      card.style.display = 'none';
    }
  });
});

// Filter functionality
const filterTags = document.querySelectorAll('.tag');

filterTags.forEach(tag => {
  tag.addEventListener('click', () => {
    // Remove active class from all tags
    filterTags.forEach(t => t.classList.remove('active'));
    // Add active class to clicked tag
    tag.classList.add('active');

    const filter = tag.getAttribute('data-filter');

    projectCards.forEach(card => {
      if (filter === 'all') {
        card.style.display = 'flex';
      } else {
        const category = card.getAttribute('data-category');
        if (category === filter) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      }
    });

    // Clear search when filtering
    searchInput.value = '';
  });
});

// Keyboard shortcut for search (Ctrl+K)
document.addEventListener('keydown', (e) => {
  if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
    e.preventDefault();
    searchInput.focus();
    document.body.classList.add('searching');
  }
});

searchInput.addEventListener('blur', () => {
  document.body.classList.remove('searching');
});

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
    }
  });
});

// Custom Neon Star Cursor with Trail
const cursorStar = document.createElement('div');
cursorStar.className = 'cursor-star';
document.body.appendChild(cursorStar);

let mouseX = 0;
let mouseY = 0;
let cursorX = 0;
let cursorY = 0;
const delay = 0.1;

// Track mouse position
document.addEventListener('mousemove', (e) => {
  mouseX = e.clientX;
  mouseY = e.clientY;

  // Create trail effect
  createTrail(e.clientX, e.clientY);
});

// Smooth cursor follow animation
function animateCursor() {
  cursorX += (mouseX - cursorX) * delay;
  cursorY += (mouseY - cursorY) * delay;

  cursorStar.style.left = cursorX + 'px';
  cursorStar.style.top = cursorY + 'px';

  requestAnimationFrame(animateCursor);
}

animateCursor();

// Create trail particles
let lastTrailTime = 0;
const trailDelay = 30; // milliseconds between trail particles

function createTrail(x, y) {
  const now = Date.now();
  if (now - lastTrailTime < trailDelay) return;
  lastTrailTime = now;

  const trail = document.createElement('div');
  trail.className = 'cursor-trail';
  trail.style.left = x + 'px';
  trail.style.top = y + 'px';
  document.body.appendChild(trail);

  // Remove trail after animation
  setTimeout(() => {
    trail.remove();
  }, 600);
}

// Change cursor color on hover over interactive elements
const interactiveElements = document.querySelectorAll('a, button, input, .tag');
interactiveElements.forEach(element => {
  element.addEventListener('mouseenter', () => {
    cursorStar.style.transform = 'translate(-50%, -50%) scale(1.3)';
    cursorStar.classList.add('hover');
  });

  element.addEventListener('mouseleave', () => {
    cursorStar.style.transform = 'translate(-50%, -50%) scale(1)';
    cursorStar.classList.remove('hover');
  });
});

// ==========================================
// AI ASSISTANT CHAT FUNCTIONALITY
// ==========================================

const chatButton = document.getElementById('chatButton');
const chatWindow = document.getElementById('chatWindow');
const chatClose = document.getElementById('chatClose');
const chatMessages = document.getElementById('chatMessages');
const chatInput = document.getElementById('chatInput');
const chatSend = document.getElementById('chatSend');
const chatTyping = document.getElementById('chatTyping');
const quickActions = document.querySelectorAll('.quick-action');
const chatNotification = document.querySelector('.chat-notification');

let chatOpened = false;
let messageHistory = [];

// Toggle chat window
function toggleChat() {
  chatWindow.classList.toggle('active');
  chatOpened = !chatOpened;

  if (chatOpened && messageHistory.length === 0) {
    // Show welcome message on first open
    setTimeout(() => {
      addMessage('assistant', 'Bonjour ! 👋 Je suis votre assistant virtuel. Comment puis-je vous aider à explorer le portfolio de Marvin ?');
    }, 500);

    // Hide notification badge
    if (chatNotification) {
      chatNotification.style.display = 'none';
    }
  }

  if (chatOpened) {
    chatInput.focus();
  }
}

chatButton.addEventListener('click', toggleChat);
chatClose.addEventListener('click', toggleChat);

// Add message to chat
function addMessage(sender, text) {
  const messageDiv = document.createElement('div');
  messageDiv.className = `message ${sender}`;

  const avatar = document.createElement('div');
  avatar.className = 'message-avatar';
  avatar.innerHTML = sender === 'assistant' ? '🤖' : '👤';

  const content = document.createElement('div');
  content.className = 'message-content';
  content.innerHTML = text;

  messageDiv.appendChild(avatar);
  messageDiv.appendChild(content);
  chatMessages.appendChild(messageDiv);

  // Store in history
  messageHistory.push({ sender, text });

  // Scroll to bottom
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Show typing indicator
function showTyping() {
  chatTyping.style.display = 'flex';
  chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Hide typing indicator
function hideTyping() {
  chatTyping.style.display = 'none';
}

// ==========================================
// COMPREHENSIVE AI KNOWLEDGE BASE
// ==========================================

const knowledgeBase = {
  // Geography Knowledge
  geography: {
    'france': { capital: 'Paris', continent: 'Europe', info: 'La France est connue pour sa culture, sa gastronomie et ses monuments historiques comme la Tour Eiffel.' },
    'espagne': { capital: 'Madrid', continent: 'Europe', info: "L'Espagne est célèbre pour le flamenco, la paella et l'architecture de Gaudí." },
    'italie': { capital: 'Rome', continent: 'Europe', info: "L'Italie est le berceau de la Renaissance et abrite le Vatican." },
    'allemagne': { capital: 'Berlin', continent: 'Europe', info: "L'Allemagne est la plus grande économie d'Europe." },
    'royaume-uni': { capital: 'Londres', continent: 'Europe', info: 'Le Royaume-Uni comprend l\'Angleterre, l\'Écosse, le Pays de Galles et l\'Irlande du Nord.' },
    'états-unis': { capital: 'Washington D.C.', continent: 'Amérique du Nord', info: 'Les États-Unis sont la première puissance mondiale.' },
    'chine': { capital: 'Pékin', continent: 'Asie', info: 'La Chine est le pays le plus peuplé du monde avec plus de 1,4 milliard d\'habitants.' },
    'japon': { capital: 'Tokyo', continent: 'Asie', info: 'Le Japon est connu pour sa technologie avancée et sa culture traditionnelle.' },
    'brésil': { capital: 'Brasília', continent: 'Amérique du Sud', info: 'Le Brésil est le plus grand pays d\'Amérique du Sud.' },
    'canada': { capital: 'Ottawa', continent: 'Amérique du Nord', info: 'Le Canada est le deuxième plus grand pays du monde par superficie.' },
    'australie': { capital: 'Canberra', continent: 'Océanie', info: 'L\'Australie est à la fois un pays et un continent.' },
    'russie': { capital: 'Moscou', continent: 'Europe/Asie', info: 'La Russie est le plus grand pays du monde par superficie.' },
    'inde': { capital: 'New Delhi', continent: 'Asie', info: 'L\'Inde est la plus grande démocratie du monde.' },
    'mexique': { capital: 'Mexico', continent: 'Amérique du Nord', info: 'Le Mexique est connu pour sa cuisine épicée et ses civilisations anciennes.' },
    'égypte': { capital: 'Le Caire', continent: 'Afrique', info: 'L\'Égypte abrite les célèbres pyramides de Gizeh.' }
  },

  // History Knowledge
  history: {
    'christophe colomb': 'Christophe Colomb a découvert l\'Amérique en 1492 en cherchant une route vers les Indes.',
    'napoléon': 'Napoléon Bonaparte fut empereur des Français de 1804 à 1815 et a marqué l\'histoire européenne.',
    'révolution française': 'La Révolution française a commencé en 1789 et a bouleversé la monarchie française.',
    'première guerre mondiale': 'La Première Guerre mondiale (1914-1918) a opposé les Alliés aux Puissances centrales.',
    'seconde guerre mondiale': 'La Seconde Guerre mondiale (1939-1945) fut le conflit le plus meurtrier de l\'histoire.',
    'renaissance': 'La Renaissance (14e-17e siècle) fut une période de renouveau culturel et artistique en Europe.',
    'jules césar': 'Jules César fut un général et homme d\'État romain qui a conquis la Gaule.',
    'louis xiv': 'Louis XIV, le Roi-Soleil, régna sur la France pendant 72 ans (1643-1715).',
    'jeanne d\'arc': 'Jeanne d\'Arc a libéré Orléans en 1429 pendant la guerre de Cent Ans.'
  },

  // Science Knowledge
  science: {
    'photosynthèse': 'La photosynthèse est le processus par lequel les plantes convertissent la lumière du soleil, l\'eau et le CO2 en glucose et oxygène. C\'est essentiel pour la vie sur Terre ! 🌱',
    'gravité': 'La gravité est la force qui attire les objets vers le centre de la Terre. Elle a été découverte par Isaac Newton.',
    'atome': 'Un atome est la plus petite unité de matière, composé d\'un noyau (protons et neutrons) et d\'électrons.',
    'adn': 'L\'ADN (acide désoxyribonucléique) contient l\'information génétique de tous les êtres vivants.',
    'système solaire': 'Le système solaire comprend le Soleil et 8 planètes : Mercure, Vénus, Terre, Mars, Jupiter, Saturne, Uranus et Neptune.',
    'électricité': 'L\'électricité est le mouvement d\'électrons à travers un conducteur. Elle alimente nos appareils modernes.',
    'évolution': 'La théorie de l\'évolution, développée par Charles Darwin, explique comment les espèces changent au fil du temps.',
    'big bang': 'Le Big Bang est la théorie scientifique qui explique l\'origine de l\'univers il y a environ 13,8 milliards d\'années.',
    'eau': 'L\'eau (H2O) est composée de deux atomes d\'hydrogène et un atome d\'oxygène. Elle est essentielle à la vie.'
  },

  // Mathematics
  math: {
    'pi': 'Pi (π) est environ égal à 3.14159... C\'est le rapport entre la circonférence d\'un cercle et son diamètre.',
    'pythagore': 'Le théorème de Pythagore : a² + b² = c² (dans un triangle rectangle).',
    'fibonacci': 'La suite de Fibonacci : 0, 1, 1, 2, 3, 5, 8, 13, 21... Chaque nombre est la somme des deux précédents.',
    'nombre premier': 'Un nombre premier n\'est divisible que par 1 et lui-même. Exemples : 2, 3, 5, 7, 11, 13...',
    'zéro': 'Le zéro a été inventé en Inde et révolutionné les mathématiques. Il représente l\'absence de quantité.'
  },

  // Culture & Arts
  culture: {
    'joconde': 'La Joconde (Mona Lisa) a été peinte par Léonard de Vinci entre 1503 et 1519. Elle est exposée au Louvre à Paris.',
    'van gogh': 'Vincent van Gogh était un peintre néerlandais post-impressionniste célèbre pour "La Nuit étoilée".',
    'picasso': 'Pablo Picasso était un peintre espagnol, cofondateur du cubisme et l\'un des artistes les plus influents du 20e siècle.',
    'beethoven': 'Ludwig van Beethoven était un compositeur allemand célèbre pour ses 9 symphonies, dont la "Symphonie n°9".',
    'mozart': 'Wolfgang Amadeus Mozart était un compositeur autrichien prodige qui a composé plus de 600 œuvres.',
    'shakespeare': 'William Shakespeare était un dramaturge anglais, auteur de "Roméo et Juliette", "Hamlet" et bien d\'autres.',
    'victor hugo': 'Victor Hugo était un écrivain français, auteur des "Misérables" et de "Notre-Dame de Paris".',
    'molière': 'Molière était un dramaturge et comédien français, auteur du "Malade imaginaire" et de "L\'Avare".'
  },

  // Technology
  technology: {
    'internet': 'Internet est un réseau mondial d\'ordinateurs connectés, créé dans les années 1960-1970.',
    'ordinateur': 'Un ordinateur est une machine électronique qui traite des données selon des instructions (programmes).',
    'intelligence artificielle': 'L\'IA est la capacité des machines à imiter l\'intelligence humaine : apprentissage, raisonnement, résolution de problèmes.',
    'blockchain': 'La blockchain est une technologie de stockage et de transmission d\'informations sécurisée et décentralisée.',
    'cloud': 'Le cloud computing permet d\'accéder à des ressources informatiques via Internet sans infrastructure locale.'
  }
};

// Get AI response based on user input
function getAIResponse(userMessage) {
  const msg = userMessage.toLowerCase();

  // ==========================================
  // PRIORITY 1: PORTFOLIO-SPECIFIC QUESTIONS
  // ==========================================

  if (msg.includes('projet') || msg.includes('tp') || msg.includes('travaux')) {
    return `Je peux vous montrer les projets de Marvin ! 🚀<br><br>
            Il a réalisé plusieurs TPs (TP1 à TP10) couvrant HTML, CSS, JavaScript et plus encore.<br><br>
            Il a aussi travaillé sur des projets de bases de données comme le <strong>MCD Militaire</strong> et des <strong>scripts SQL</strong>.<br><br>
            Utilisez les boutons ci-dessous ou cliquez sur un projet dans la grille pour en savoir plus !`;
  }

  if (msg.includes('certif') || msg.includes('diplôme') || msg.includes('formation')) {
    return `Marvin possède plusieurs certifications professionnelles ! 🎓<br><br>
            Vous pouvez consulter toutes ses certifications en cliquant sur le bouton "Certifications" ci-dessous ou en visitant la section dédiée du portfolio.`;
  }

  if (msg.includes('compétence') || msg.includes('skill') || msg.includes('technologie') && !msg.includes('c\'est quoi')) {
    return `Marvin maîtrise plusieurs technologies ! 💡<br><br>
            <strong>Frontend:</strong> HTML5, CSS3, JavaScript<br>
            <strong>Base de données:</strong> SQL, Merise, MCD<br>
            <strong>Outils:</strong> Git, VS Code<br><br>
            Ses projets démontrent une expertise en développement web et en conception de bases de données.`;
  }

  if (msg.includes('contact') || msg.includes('email') || msg.includes('joindre')) {
    return `Pour contacter Marvin, vous pouvez : 📧<br><br>
            • Consulter la section contact du portfolio<br>
            • Envoyer un message via le formulaire<br>
            • Connecter sur les réseaux professionnels<br><br>
            N'hésitez pas à le contacter pour toute opportunité ou collaboration !`;
  }

  if ((msg.includes('qui') || msg.includes('à propos')) && (msg.includes('marvin') || msg.includes('tu es') || msg.includes('toi'))) {
    return `Marvin est un développeur passionné par les technologies web ! 🌟<br><br>
            Son portfolio présente ses compétences en développement frontend, backend et bases de données.<br><br>
            Explorez ses projets pour découvrir son expertise et sa créativité !`;
  }

  // ==========================================
  // PRIORITY 2: GEOGRAPHY QUESTIONS
  // ==========================================

  if (msg.includes('capitale') || msg.includes('capital')) {
    for (let country in knowledgeBase.geography) {
      if (msg.includes(country)) {
        const data = knowledgeBase.geography[country];
        return `La capitale de ${country.charAt(0).toUpperCase() + country.slice(1)} est <strong>${data.capital}</strong> ! 🌍<br><br>
                ${data.info}<br><br>
                Ce pays se trouve en ${data.continent}.`;
      }
    }
    return `Pour connaître la capitale d'un pays, demandez-moi par exemple : "Quelle est la capitale de la France ?" 🌍`;
  }

  if (msg.includes('pays') || msg.includes('continent')) {
    return `Je connais de nombreux pays ! 🌍<br><br>
            Demandez-moi par exemple :<br>
            • "Quelle est la capitale de la France ?"<br>
            • "Parle-moi du Japon"<br>
            • "Où se trouve le Brésil ?"`;
  }

  // ==========================================
  // PRIORITY 3: HISTORY QUESTIONS
  // ==========================================

  if (msg.includes('découvert') && msg.includes('amérique')) {
    return `${knowledgeBase.history['christophe colomb']} 🌎<br><br>
            Il pensait avoir atteint les Indes, d'où le nom "Indiens" donné aux peuples autochtones.`;
  }

  if (msg.includes('napoléon')) {
    return `${knowledgeBase.history['napoléon']} ⚔️<br><br>
            Il a mené de nombreuses campagnes militaires et a réformé le système juridique français avec le Code Napoléon.`;
  }

  if (msg.includes('révolution française')) {
    return `${knowledgeBase.history['révolution française']} 🇫🇷<br><br>
            Elle a conduit à la chute de la monarchie et à l'établissement de la République.`;
  }

  if (msg.includes('guerre mondiale')) {
    if (msg.includes('première') || msg.includes('1')) {
      return `${knowledgeBase.history['première guerre mondiale']} ⚔️`;
    } else if (msg.includes('seconde') || msg.includes('2') || msg.includes('deuxième')) {
      return `${knowledgeBase.history['seconde guerre mondiale']} 🕊️`;
    }
  }

  // ==========================================
  // PRIORITY 4: SCIENCE QUESTIONS
  // ==========================================

  if (msg.includes('photosynthèse')) {
    return knowledgeBase.science['photosynthèse'];
  }

  if (msg.includes('gravité') || msg.includes('pesanteur')) {
    return `${knowledgeBase.science['gravité']} 🍎<br><br>
            La célèbre histoire raconte qu'il a eu cette révélation en voyant une pomme tomber d'un arbre !`;
  }

  if (msg.includes('atome')) {
    return `${knowledgeBase.science['atome']} ⚛️<br><br>
            Tout ce qui nous entoure est fait d'atomes !`;
  }

  if (msg.includes('adn') || msg.includes('génétique')) {
    return `${knowledgeBase.science['adn']} 🧬<br><br>
            Votre ADN est unique et vous différencie de tous les autres êtres humains !`;
  }

  if (msg.includes('système solaire') || msg.includes('planète')) {
    return `${knowledgeBase.science['système solaire']} 🪐<br><br>
            Pluton était considérée comme la 9e planète jusqu'en 2006, où elle a été reclassée comme planète naine.`;
  }

  if (msg.includes('big bang') || msg.includes('univers')) {
    return `${knowledgeBase.science['big bang']} 🌌<br><br>
            L'univers continue de s'étendre encore aujourd'hui !`;
  }

  if (msg.includes('évolution') || msg.includes('darwin')) {
    return `${knowledgeBase.science['évolution']} 🦎<br><br>
            Cette théorie a révolutionné notre compréhension de la biologie !`;
  }

  // ==========================================
  // PRIORITY 5: MATHEMATICS
  // ==========================================

  if (msg.includes('pi') && !msg.includes('api')) {
    return `${knowledgeBase.math['pi']} 🥧<br><br>
            Pi est un nombre irrationnel, ce qui signifie que ses décimales continuent à l'infini sans se répéter !`;
  }

  if (msg.includes('pythagore')) {
    return `${knowledgeBase.math['pythagore']} 📐<br><br>
            Ce théorème est fondamental en géométrie et est utilisé partout en architecture et ingénierie !`;
  }

  if (msg.includes('fibonacci')) {
    return `${knowledgeBase.math['fibonacci']} 🌀<br><br>
            Cette suite apparaît dans la nature : coquillages, fleurs, galaxies !`;
  }

  // Simple calculations
  if (msg.match(/combien font|calcule|×|x|\+|-|÷|\//) && msg.match(/\d+/)) {
    const numbers = msg.match(/\d+/g);
    if (numbers && numbers.length >= 2) {
      const a = parseInt(numbers[0]);
      const b = parseInt(numbers[1]);
      let result, operation;

      if (msg.includes('×') || msg.includes('x') || msg.includes('fois') || msg.includes('multiplié')) {
        result = a * b;
        operation = 'multiplication';
      } else if (msg.includes('+') || msg.includes('plus')) {
        result = a + b;
        operation = 'addition';
      } else if (msg.includes('-') || msg.includes('moins')) {
        result = a - b;
        operation = 'soustraction';
      } else if (msg.includes('÷') || msg.includes('/') || msg.includes('divisé')) {
        result = a / b;
        operation = 'division';
      }

      if (result !== undefined) {
        return `Le résultat de cette ${operation} est <strong>${result}</strong> ! 🔢<br><br>
                ${a} ${msg.includes('×') || msg.includes('x') ? '×' : msg.includes('+') ? '+' : msg.includes('-') ? '-' : '÷'} ${b} = ${result}`;
      }
    }
  }

  // ==========================================
  // PRIORITY 6: CULTURE & ARTS
  // ==========================================

  if (msg.includes('joconde') || msg.includes('mona lisa')) {
    return `${knowledgeBase.culture['joconde']} 🖼️<br><br>
            Son sourire énigmatique fascine le monde entier depuis des siècles !`;
  }

  if (msg.includes('van gogh')) {
    return `${knowledgeBase.culture['van gogh']} 🎨<br><br>
            Malgré sa vie difficile, il a créé plus de 2000 œuvres d'art !`;
  }

  if (msg.includes('picasso')) {
    return `${knowledgeBase.culture['picasso']} 🎨<br><br>
            Son nom complet comportait 23 mots !`;
  }

  if (msg.includes('beethoven')) {
    return `${knowledgeBase.culture['beethoven']} 🎵<br><br>
            Il a continué à composer même après être devenu sourd !`;
  }

  if (msg.includes('mozart')) {
    return `${knowledgeBase.culture['mozart']} 🎼<br><br>
            Il a commencé à composer à l'âge de 5 ans !`;
  }

  if (msg.includes('shakespeare')) {
    return `${knowledgeBase.culture['shakespeare']} 📚<br><br>
            Il a inventé plus de 1700 mots en anglais !`;
  }

  if (msg.includes('peint') || msg.includes('peintre')) {
    if (msg.includes('joconde')) {
      return `La Joconde a été peinte par <strong>Léonard de Vinci</strong> ! 🎨<br><br>
              ${knowledgeBase.culture['joconde']}`;
    }
  }

  // ==========================================
  // PRIORITY 7: TECHNOLOGY
  // ==========================================

  if (msg.includes('internet') && !msg.includes('site')) {
    return `${knowledgeBase.technology['internet']} 🌐<br><br>
            Aujourd'hui, plus de 5 milliards de personnes utilisent Internet !`;
  }

  if (msg.includes('ordinateur') && !msg.includes('marvin')) {
    return `${knowledgeBase.technology['ordinateur']} 💻<br><br>
            Le premier ordinateur électronique (ENIAC) pesait 30 tonnes !`;
  }

  if (msg.includes('intelligence artificielle') || (msg.includes('ia') && !msg.includes('assistant'))) {
    return `${knowledgeBase.technology['intelligence artificielle']} 🤖<br><br>
            Je suis moi-même une forme d'IA créée pour vous aider !`;
  }

  // ==========================================
  // PRIORITY 8: CONVERSATIONAL
  // ==========================================

  if (msg.includes('blague') || msg.includes('joke') || msg.includes('rigole')) {
    const jokes = [
      "Pourquoi les plongeurs plongent-ils toujours en arrière et jamais en avant ? 🤔<br>Parce que sinon ils tombent dans le bateau ! 😄",
      "Qu'est-ce qu'un crocodile qui surveille la pharmacie ? 🐊<br>Un Lacoste garde ! 😂",
      "Pourquoi les poissons n'aiment pas jouer au tennis ? 🐟<br>Parce qu'ils ont peur du filet ! 🎾",
      "Comment appelle-t-on un chat tombé dans un pot de peinture le jour de Noël ? 🎨<br>Un chat-peint de Noël ! 🎄",
      "Qu'est-ce qu'un ordinateur qui chante ? 💻<br>Un Dell ! 🎵"
    ];
    return jokes[Math.floor(Math.random() * jokes.length)];
  }

  if (msg.includes('merci') || msg.includes('thanks')) {
    return `De rien ! 😊 N'hésitez pas si vous avez d'autres questions, que ce soit sur le portfolio de Marvin ou sur n'importe quel sujet !`;
  }

  if (msg.includes('bonjour') || msg.includes('salut') || msg.includes('hello') || msg.includes('hi')) {
    return `Bonjour ! 👋 Ravi de vous voir !<br><br>
            Je peux vous aider à découvrir le portfolio de Marvin, ou répondre à vos questions sur la géographie, l'histoire, les sciences, et bien plus encore !<br><br>
            Que souhaitez-vous savoir ?`;
  }

  if (msg.includes('comment tu vas') || msg.includes('ça va')) {
    return `Je vais très bien, merci ! 😊<br><br>
            Je suis là pour répondre à vos questions. Comment puis-je vous aider aujourd'hui ?`;
  }

  if (msg.includes('qui es-tu') || msg.includes('qui tu es')) {
    return `Je suis un assistant IA créé pour vous aider ! 🤖<br><br>
            Je peux vous guider dans le portfolio de Marvin et répondre à vos questions sur de nombreux sujets : géographie, histoire, sciences, culture, mathématiques...<br><br>
            N'hésitez pas à me poser vos questions !`;
  }

  if (msg.includes('aide') || msg.includes('help')) {
    return `Je suis là pour vous aider ! 😊<br><br>
            <strong>Sur le portfolio :</strong><br>
            • Les projets de Marvin<br>
            • Ses certifications<br>
            • Ses compétences<br>
            • Comment le contacter<br><br>
            <strong>Connaissances générales :</strong><br>
            • Géographie (capitales, pays)<br>
            • Histoire (événements, personnages)<br>
            • Sciences (physique, biologie)<br>
            • Mathématiques (calculs)<br>
            • Culture (arts, musique, littérature)<br><br>
            Posez-moi n'importe quelle question !`;
  }

  // ==========================================
  // DEFAULT RESPONSE
  // ==========================================

  return `Hmm, c'est une question intéressante ! 🤔<br><br>
          Je peux vous aider avec :<br>
          • Le <strong>portfolio de Marvin</strong> (projets, certifications, compétences)<br>
          • La <strong>géographie</strong> (capitales, pays)<br>
          • L'<strong>histoire</strong> (événements, personnages)<br>
          • Les <strong>sciences</strong> (physique, biologie, astronomie)<br>
          • Les <strong>mathématiques</strong> (calculs, théorèmes)<br>
          • La <strong>culture</strong> (arts, musique, littérature)<br><br>
          Reformulez votre question ou essayez un autre sujet ! 😊`;
}

// Handle sending message
function sendMessage() {
  const message = chatInput.value.trim();

  if (message === '') return;

  // Add user message
  addMessage('user', message);
  chatInput.value = '';

  // Show typing indicator
  showTyping();

  // Simulate AI thinking time
  setTimeout(() => {
    hideTyping();
    const response = getAIResponse(message);
    addMessage('assistant', response);
  }, 1000 + Math.random() * 1000); // Random delay between 1-2 seconds
}

// Send message on button click
chatSend.addEventListener('click', sendMessage);

// Send message on Enter key
chatInput.addEventListener('keypress', (e) => {
  if (e.key === 'Enter') {
    sendMessage();
  }
});

// Quick action buttons
quickActions.forEach(action => {
  action.addEventListener('click', () => {
    const actionType = action.getAttribute('data-action');

    let userMessage = '';
    let response = '';

    switch (actionType) {
      case 'projets':
        userMessage = 'Quels sont tes projets ?';
        response = `Voici les projets de Marvin ! 🚀<br><br>
                    <strong>TPs (Travaux Pratiques):</strong><br>
                    • TP1 à TP10 - HTML, CSS, JavaScript<br>
                    • Calculatrice interactive<br>
                    • Projets de design responsive<br><br>
                    <strong>Bases de Données:</strong><br>
                    • MCD Militaire (Merise)<br>
                    • Scripts SQL avancés<br><br>
                    Cliquez sur un projet dans la grille pour le découvrir !`;
        break;

      case 'certifications':
        userMessage = 'Montre-moi les certifications';
        response = `Marvin a obtenu plusieurs certifications ! 🎓<br><br>
                    Consultez la section "Certifications" pour voir tous ses diplômes et formations professionnelles.<br><br>
                    <a href="Mes_Certif.html" style="color: var(--cyan); text-decoration: underline;">Voir les certifications →</a>`;
        break;

      case 'competences':
        userMessage = 'Quelles sont tes compétences ?';
        response = `Compétences techniques de Marvin : 💡<br><br>
                    <strong>🎨 Frontend:</strong> HTML5, CSS3, JavaScript ES6+<br>
                    <strong>🗄️ Bases de données:</strong> SQL, Merise, MCD/MLD<br>
                    <strong>⚙️ Outils:</strong> Git, VS Code, DevTools<br>
                    <strong>🎯 Méthodologies:</strong> Design responsive, UX/UI<br><br>
                    Explorez ses projets pour voir ces compétences en action !`;
        break;

      case 'contact':
        userMessage = 'Comment contacter Marvin ?';
        response = `Pour contacter Marvin : 📧<br><br>
                    Vous pouvez le joindre via le formulaire de contact du portfolio ou sur les réseaux professionnels.<br><br>
                    Il est ouvert aux opportunités de collaboration et aux projets intéressants !`;
        break;
    }

    // Add user message
    addMessage('user', userMessage);

    // Show typing and response
    showTyping();
    setTimeout(() => {
      hideTyping();
      addMessage('assistant', response);
    }, 800);
  });
});

