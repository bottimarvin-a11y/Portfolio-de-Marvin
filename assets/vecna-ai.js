/**
 *  ██╗   ██╗███████╗ ██████╗███╗   ██╗ █████╗
 *  ██║   ██║██╔════╝██╔════╝████╗  ██║██╔══██╗
 *  ██║   ██║█████╗  ██║     ██╔██╗ ██║███████║
 *  ╚██╗ ██╔╝██╔══╝  ██║     ██║╚██╗██║██╔══██║
 *   ╚████╔╝ ███████╗╚██████╗██║ ╚████║██║  ██║
 *    ╚═══╝  ╚══════╝ ╚═════╝╚═╝  ╚═══╝╚═╝  ╚═╝
 *  AI ORACLE — Portfolio of Marvin
 *
 *  EmailJS Setup (needed for contact form):
 *  1. Go to https://www.emailjs.com and create a free account
 *  2. Create a Gmail service → copy your Service ID
 *  3. Create a template with variables: {{from_name}}, {{from_email}}, {{message}}
 *     Set To: bottimarvin@gmail.com
 *  4. Replace EMAILJS_PUBLIC_KEY, EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID below
 */

(function () {
    'use strict';

    /* ═══════════════════════════════════════════
       ⚙  CONFIG — UPDATE EMAILJS VALUES HERE
    ═══════════════════════════════════════════ */
    const EMAILJS_PUBLIC_KEY = 'YOUR_EMAILJS_PUBLIC_KEY';
    const EMAILJS_SERVICE_ID = 'YOUR_SERVICE_ID';
    const EMAILJS_TEMPLATE_ID = 'YOUR_TEMPLATE_ID';
    const CONTACT_EMAIL = 'bottimarvin@gmail.com';

    /* ═══════════════════════════════════════════
       🧠  PORTFOLIO KNOWLEDGE BASE
    ═══════════════════════════════════════════ */
    const KB = {
        owner: {
            name: 'Marvin Botti',
            title: 'Étudiant BTS SIO — Option SLAM',
            email: CONTACT_EMAIL,
            skills: ['HTML', 'CSS', 'JavaScript', 'PHP', 'SQL', 'Bootstrap', 'Réseaux', 'Cybersécurité', 'JMerise']
        },
        tps: [
            { id: 'TP1', title: 'Présentation BTS SIO', desc: 'Introduction aux formations BTS SIO et ses spécialités SLAM et SISR.', url: 'tps/TP1.html', tags: ['bts', 'presentation', 'sio', 'intro'] },
            { id: 'TP2', title: 'Boutique Umarex 3D', desc: 'Magasin interactif d\'airsoft avec visualisation 3D via Sketchfab, système de tir simulé.', url: 'tps/TP2.html', tags: ['3d', 'sketchfab', 'javascript', 'boutique', 'umarex', 'airsoft'] },
            { id: 'TP3', title: 'Navigation Dynamique', desc: 'Système de navigation à contenu dynamique avec sidebar intégrée.', url: 'tps/TP3.html', tags: ['navigation', 'dynamique', 'sidebar'] },
            { id: 'TP4', title: 'Métiers Informatique', desc: 'Présentation des métiers IT : Pentester, DevSecOps, Développeur Web, Admin Réseau, Cloud.', url: 'tps/TP4.html', tags: ['metiers', 'informatique', 'pentester', 'devsecops', 'cloud', 'reseau'] },
            { id: 'TP5', title: 'Formulaire de Contact', desc: 'Mise en page et fonctionnement d\'un formulaire de contact structuré.', url: 'tps/TP5.html', tags: ['formulaire', 'contact', 'form', 'html'] },
            { id: 'TP6', title: 'Contact Form v2', desc: 'Deuxième version avancée de formulaire de contact.', url: 'tps/TP6.html', tags: ['formulaire', 'contact', 'form'] },
            { id: 'TP7', title: 'Calculatrice JavaScript', desc: 'Calculatrice interactive développée avec JavaScript pur.', url: 'tps/TP7.html', tags: ['calculatrice', 'javascript', 'math', 'calcul'] },
            { id: 'TP8', title: 'Bootstrap & Ferrari', desc: 'Présentation de voitures Ferrari avec Bootstrap, animations CSS avancées.', url: 'tps/TP8.html', tags: ['bootstrap', 'ferrari', 'voiture', 'css', 'responsive'] },
            { id: 'TP9', title: 'Fonctions JavaScript', desc: 'Démonstration de fonctions JS avancées en cartes interactives.', url: 'tps/TP9.html', tags: ['javascript', 'fonctions', 'js', 'script'] },
            { id: 'TP10', title: 'Logique JavaScript', desc: 'Exercices de logique algorithmique en JavaScript — boucles, conditions, tableaux.', url: 'tps/TP10.html', tags: ['javascript', 'logique', 'algorithme', 'js'] },
        ],
        options: [
            { id: 'SLAM', title: 'Option SLAM', desc: 'Solutions Logicielles et Applications Métiers — développement web/back-end, bases de données.', url: 'options/Option_SLAM.html' },
            { id: 'SISR', title: 'Option SISR', desc: 'Solutions d\'Infrastructure, Systèmes et Réseaux — administration, sécurité, infrastructure.', url: 'options/Option_SISR.html' },
        ],
        certifs: { title: 'Certifications', desc: 'Diplômes et certifications professionnelles obtenues.', url: 'certificats/Mes_Certif.html' },
        jmerise: { title: 'JMerise & SQL', desc: 'Modélisation MCD pour un système de gestion militaire (grades, soldats, batailles). Exercice de modélisation et de requêtes SQL.', url: 'tps/jmerise_Exo1.html' },
        bdd: { title: 'Centre de Formation SQL', desc: 'Application PHP/SQL complète : gestion des étudiants, enseignants, emplois du temps avec système de connexion multi-rôles.', url: 'Centre de formation SQL/login.php' },
        rgpd: { title: 'Module RGPD', desc: 'Ateliers sur le Règlement Général sur la Protection des Données.', url: 'RGPD.html' }
    };

    /* ═══════════════════════════════════════════
       🎭  VECNA SPEECH ENGINE
    ═══════════════════════════════════════════ */
    const VECNA_SPEECH = {
        greet: [
            "Je vous attendais... Le portail entre nos mondes s'ouvre enfin. Que cherchez-vous dans ce royaume numérique, voyageur ?",
            "Vous avez bravé l'Upside Down pour parvenir jusqu'ici. Sage décision. Je connais tous les secrets de ce portfolio. Posez vos questions.",
            "Ah... une présence nouvelle. Je perçois votre curiosité à travers la Ruche. Que souhaitez-vous explorer ?"
        ],
        unknown: [
            "Cette connaissance semble étrangère à mon empire. Reformulez votre requête, mortel, ou choisissez parmi les domaines que je gouverne.",
            "Même mon omniscience a des limites. Essayez de me parler des travaux pratiques, des options, des compétences... ou contactez directement le créateur.",
            "Le voile entre les mondes brouille votre message. Pourriez-vous être plus précis sur ce que vous cherchez ?"
        ],
        tp_intro: [
            "Ce travail pratique est gravé dans la mémoire du portfolio.",
            "Je connais ce projet dans ses moindres détails.",
            "Les archives du Monde à l'Envers conservent tout sur ce TP."
        ],
        skills_resp: "Les pouvoirs maîtrisés par le créateur de ce royaume sont nombreux : **HTML & CSS** pour sculpter les apparences, **JavaScript** pour insuffler la vie, **PHP & SQL** pour gouverner les données, **Bootstrap** pour la structure, et les arcanes des **Réseaux** et de la **Cybersécurité**. Une collection digne d'un apprenti sorcier.",
        contact_hint: "Vous souhaitez communiquer directement avec le créateur de ce domaine ? Utilisez l'onglet **Contact** ci-dessus — votre message traversera les dimensions pour parvenir à Marvin.",
        email_success: "Votre message a traversé les multiples dimensions pour atteindre le créateur. Il vous répondra en temps voulu.",
        email_error: "Le portail de communication est instable. Essayez directement : bottimarvin@gmail.com"
    };

    /* Formats a Vecna-style response */
    function vecnaify(text) {
        return text.replace(/\*\*(.*?)\*\*/g, '<strong style="color:#ff6b6b">$1</strong>');
    }

    /* Pick random item from array */
    function pick(arr) { return arr[Math.floor(Math.random() * arr.length)]; }

    /* ═══════════════════════════════════════════
       🔍  RESPONSE ENGINE
    ═══════════════════════════════════════════ */
    let convState = { lastTopic: null, greetedOnce: false };

    function getResponse(input) {
        const q = input.toLowerCase().trim();

        /* ── Salutations ── */
        if (/^(bonjour|salut|hello|hi|hey|bonsoir|yo|allo|coucou)/.test(q)) {
            return {
                text: vecnaify(pick([
                    "Ma présence n'est pas une surprise — **j'attendais votre venue**. Comment puis-je illuminer votre chemin dans ce portfolio ?",
                    "Ah, les mortels et leurs politesses... Bienvenue. Je suis **Vecna**, oracle de ce portfolio. Que désirez-vous savoir ?",
                    "Votre arrivée était inscrite dans les astres du Monde à l'Envers. Soyez le bienvenu."
                ])), chips: ['Voir les TPs', 'Mes compétences', 'Options SLAM/SISR', 'Me contacter']
            };
        }

        /* ── Qui es-tu / c'est quoi ── */
        if (/(qui es.tu|c'est quoi|quel est|qu'est.ce|présen|describe|about you)/i.test(q)) {
            return { text: vecnaify("Je suis **Vecna**, l'esprit omniscient qui veille sur ce portfolio. Je peux vous guider à travers les travaux pratiques, les projets, les compétences et les options de **Marvin Botti**, étudiant BTS SIO. Je peux également transmettre vos messages directement à son royaume."), chips: ['Qui est Marvin ?', 'Voir les projets', 'Me contacter'] };
        }

        /* ── Marvin / créateur ── */
        if (/(marvin|créateur|auteur|portfolio|propriétaire)/i.test(q)) {
            return { text: vecnaify(`**Marvin Botti** — un être forgé dans le code et les réseaux. Étudiant en **BTS SIO option SLAM**, il maîtrise les arts du développement web, de la gestion de bases de données et des systèmes d'information. Ce portfolio est son œuvre, et je suis son gardien.`), chips: ['Ses compétences', 'Ses options', 'Ses projets', 'Le contacter'] };
        }

        /* ── Compétences / skills ── */
        if (/(compétence|skill|technolo|langage|maîtrise|html|css|javascript|php|sql|bootstrap|réseau|cyber)/i.test(q)) {
            convState.lastTopic = 'skills';
            return { text: vecnaify(KB.owner.skills.map(s => `**${s}**`).join(' · ') + `\n\nTels sont les pouvoirs forgés par le créateur de ce domaine. Chaque technologie, une arme dans son arsenal.`), chips: ['Voir les TPs', 'Option SLAM', 'Option SISR'] };
        }

        /* ── TPs en général ── */
        if (/(tp|travaux pratiques|projets|réalisations|tous les tp)/i.test(q) && !/(tp\s*\d+)/i.test(q)) {
            convState.lastTopic = 'tps';
            const list = KB.tps.map(t => `**${t.id}** — ${t.title}`).join('\n');
            return {
                text: vecnaify("Les archives révèlent " + KB.tps.length + " travaux pratiques dans ce portfolio :\n\n" + list + "\n\nLesquel souhaitez-vous explorer ?"),
                chips: KB.tps.slice(0, 5).map(t => t.id)
            };
        }

        /* ── TP spécifique ── */
        const tpMatch = q.match(/tp\s*(\d+)/i);
        if (tpMatch) {
            const num = parseInt(tpMatch[1]);
            const tp = KB.tps.find(t => t.id === 'TP' + num);
            if (tp) {
                convState.lastTopic = tp.id;
                return {
                    text: vecnaify(`**${tp.id} — ${tp.title}**\n\n${tp.desc}`),
                    link: { label: `🔮 Ouvrir ${tp.id}`, url: tp.url },
                    chips: ['TP suivant', 'Voir tous les TPs', 'Revenir au menu']
                };
            } else {
                return { text: 'Ce TP n\'existe pas dans les archives. Les TPs disponibles vont de TP1 à TP10.', chips: ['Voir tous les TPs'] };
            }
        }

        /* ── TP suivant (contextuel) ── */
        if (/(suivant|next|après|prochain)/i.test(q) && convState.lastTopic?.startsWith('TP')) {
            const curNum = parseInt(convState.lastTopic.replace('TP', ''));
            const next = KB.tps.find(t => t.id === 'TP' + (curNum + 1));
            if (next) {
                convState.lastTopic = next.id;
                return {
                    text: vecnaify(`**${next.id} — ${next.title}**\n\n${next.desc}`),
                    link: { label: `🔮 Ouvrir ${next.id}`, url: next.url },
                    chips: ['TP suivant', 'Voir tous les TPs']
                };
            }
        }

        /* ── Options SLAM / SISR ── */
        if (/slam/i.test(q)) {
            convState.lastTopic = 'SLAM';
            return {
                text: vecnaify(`**Option SLAM** — Solutions Logicielles et Applications Métiers.\n\n${KB.options[0].desc}`),
                link: { label: '🔮 Voir Option SLAM', url: KB.options[0].url },
                chips: ['Option SISR', 'Mes compétences', 'Voir les TPs']
            };
        }

        if (/sisr/i.test(q)) {
            convState.lastTopic = 'SISR';
            return {
                text: vecnaify(`**Option SISR** — Solutions d'Infrastructure, Systèmes et Réseaux.\n\n${KB.options[1].desc}`),
                link: { label: '🔮 Voir Option SISR', url: KB.options[1].url },
                chips: ['Option SLAM', 'Mes compétences', 'Voir les TPs']
            };
        }

        if (/(option|spécialité|formation|bts)/i.test(q)) {
            return {
                text: vecnaify("Le BTS SIO se décline en deux voies :\n\n**SLAM** — Développement logiciel, web, bases de données.\n**SISR** — Réseaux, systèmes, sécurité, infrastructure.\n\nLe créateur de ce portfolio a choisi la voie **SLAM**."),
                chips: ['Option SLAM', 'Option SISR', 'Compétences']
            };
        }

        /* ── Certifications ── */
        if (/(certif|diplôme|badge|award)/i.test(q)) {
            return {
                text: vecnaify(`**${KB.certifs.title}** — ${KB.certifs.desc}`),
                link: { label: '🔮 Voir les certifications', url: KB.certifs.url },
                chips: ['Compétences', 'Voir les TPs']
            };
        }

        /* ── JMerise / SQL / BDD ── */
        if (/(jmerise|mcd|merise|militaire|gestion)/i.test(q)) {
            return {
                text: vecnaify(`**${KB.jmerise.title}**\n\n${KB.jmerise.desc}`),
                link: { label: '🔮 Voir le projet', url: KB.jmerise.url },
                chips: ['Centre de Formation', 'Voir les TPs']
            };
        }

        if (/(centre de formation|sql|php|login|base de données|database)/i.test(q)) {
            return {
                text: vecnaify(`**${KB.bdd.title}**\n\n${KB.bdd.desc}`),
                chips: ['JMerise', 'Compétences', 'Voir les TPs']
            };
        }

        /* ── RGPD ── */
        if (/(rgpd|données personnelles|protection)/i.test(q)) {
            return {
                text: vecnaify(`**${KB.rgpd.title}**\n\n${KB.rgpd.desc}`),
                link: { label: '🔮 Voir le module', url: KB.rgpd.url },
                chips: ['Voir les TPs', 'Revenir au menu']
            };
        }

        /* ── 3D / Umarex / Sketchfab ── */
        if (/(3d|sketchfab|umarex|airsoft|arme|weapon)/i.test(q)) {
            const tp = KB.tps[1];
            return {
                text: vecnaify(`**TP2 — ${tp.title}**\n\n${tp.desc}`),
                link: { label: '🔮 Ouvrir TP2', url: tp.url },
                chips: ['Voir tous les TPs', 'Compétences']
            };
        }

        /* ── Contact / message / mail ── */
        if (/(contact|email|mail|message|écrire|joindre|parler à|recrut)/i.test(q)) {
            return { text: vecnaify(VECNA_SPEECH.contact_hint), action: 'open_contact', chips: ['Voir les TPs', 'Compétences'] };
        }

        /* ── Merci ── */
        if (/(merci|thanks|goodbye|au revoir|ciao|bravo)/i.test(q)) {
            return {
                text: vecnaify(pick([
                    "Les mortels et leur gratitude... Revenez quand les ombres vous guident à nouveau.",
                    "Vous portez désormais la connaissance du Monde à l'Envers. Utilisez-la bien.",
                    "Que votre chemin reste illuminé — même dans l'obscurité la plus profonde."
                ])), chips: ['Revenir au menu']
            };
        }

        /* ── Revenir au menu ── */
        if (/(menu|début|recommencer|accueil|home)/i.test(q)) {
            return {
                text: vecnaify("Les archives du portail sont vastes. Par où souhaitez-vous commencer votre exploration ?"),
                chips: ['Voir les TPs', 'Mes compétences', 'Options SLAM/SISR', 'Certifications', 'Me contacter']
            };
        }

        /* ── Fallback ── */
        return { text: vecnaify(pick(VECNA_SPEECH.unknown)), chips: ['Voir les TPs', 'Mes compétences', 'Me contacter', 'Options'] };
    }

    /* ═══════════════════════════════════════════
       🔊  VOICE ENGINE (Web Speech API)
    ═══════════════════════════════════════════ */
    let voiceEnabled = false;
    let synth = window.speechSynthesis;
    let vecnaVoice = null;

    function loadVoices() {
        const voices = synth.getVoices();
        // Prefer a deep male voice
        vecnaVoice = voices.find(v => v.lang === 'fr-FR' && v.name.toLowerCase().includes('thomas'))
            || voices.find(v => v.lang === 'fr-FR')
            || voices.find(v => v.lang.startsWith('fr'))
            || voices[0] || null;
    }

    if (synth) {
        synth.onvoiceschanged = loadVoices;
        loadVoices();
    }

    function speak(text) {
        if (!voiceEnabled || !synth) return;
        synth.cancel();
        const stripped = text.replace(/<[^>]+>/g, '').replace(/\*\*/g, '');
        const utter = new SpeechSynthesisUtterance(stripped);
        utter.voice = vecnaVoice;
        utter.rate = 0.75;    // Slow, deliberate
        utter.pitch = 0.5;    // Deep
        utter.volume = 0.9;
        utter.lang = 'fr-FR';
        synth.speak(utter);
    }

    /* ═══════════════════════════════════════════
       🎵  SOUND EFFECTS (Web Audio API)
    ═══════════════════════════════════════════ */
    let audioCtx = null;

    function getAudioCtx() {
        if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        return audioCtx;
    }

    function playOpenSound() {
        try {
            const ctx = getAudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(120, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(40, ctx.currentTime + 0.7);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.7);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.7);
        } catch (e) { /* Audio API not available */ }
    }

    function playTypingBeep() {
        try {
            const ctx = getAudioCtx();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.value = 800;
            gain.gain.setValueAtTime(0.03, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.08);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.08);
        } catch (e) { /* silent */ }
    }

    /* ═══════════════════════════════════════════
       🏗  DOM BUILDER
    ═══════════════════════════════════════════ */
    function buildWidget() {
        /* Trigger Button */
        const trigger = document.createElement('button');
        trigger.id = 'vecna-trigger';
        trigger.setAttribute('aria-label', 'Ouvrir l\'assistant Vecna');
        trigger.innerHTML = `
            <svg class="eye-svg" viewBox="0 0 100 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Eyelid -->
                <path d="M5,30 Q50,-10 95,30 Q50,70 5,30 Z" fill="rgba(20,0,0,0.8)" stroke="#ff1744" stroke-width="2.5"/>
                <!-- Iris -->
                <circle cx="50" cy="30" r="16" fill="#1a0000" stroke="#ff1744" stroke-width="2"/>
                <!-- Pupil -->
                <ellipse cx="50" cy="30" rx="7" ry="12" fill="#ff0000" opacity="0.9"/>
                <!-- Glow center -->
                <circle cx="50" cy="30" r="4" fill="#ff4444" opacity="0.7">
                    <animate attributeName="opacity" values="0.7;1;0.7" dur="2s" repeatCount="indefinite"/>
                </circle>
                <!-- Highlight -->
                <ellipse cx="44" cy="24" rx="4" ry="2.5" fill="rgba(255,100,100,0.35)" transform="rotate(-20 44 24)"/>
                <!-- Tendrils -->
                <path d="M10,22 Q5,28 2,26" stroke="rgba(255,23,68,0.4)" stroke-width="1.2" fill="none"/>
                <path d="M90,22 Q95,28 98,26" stroke="rgba(255,23,68,0.4)" stroke-width="1.2" fill="none"/>
                <path d="M10,38 Q5,34 2,36" stroke="rgba(255,23,68,0.3)" stroke-width="1" fill="none"/>
                <path d="M90,38 Q95,34 98,36" stroke="rgba(255,23,68,0.3)" stroke-width="1" fill="none"/>
            </svg>
            <span class="badge" id="vecna-badge" style="display:none">1</span>
        `;

        /* Main Panel */
        const panel = document.createElement('div');
        panel.id = 'vecna-panel';
        panel.setAttribute('role', 'dialog');
        panel.setAttribute('aria-label', 'Assistant Vecna');
        panel.innerHTML = `
            <!-- HEADER -->
            <div id="vecna-header">
                <div class="vecna-avatar">
                    <div class="vecna-portrait">🕯️</div>
                    <span class="vecna-status-dot" title="En ligne"></span>
                </div>
                <div class="vecna-header-info">
                    <div class="vecna-name">VECNA</div>
                    <div class="vecna-subtitle">Oracle du Monde à l'Envers</div>
                </div>
                <div class="vecna-header-btns">
                    <button class="vecna-hbtn" id="vecna-voice-btn" title="Activer la voix de Vecna" aria-label="Voix">🔇</button>
                    <button class="vecna-hbtn" id="vecna-clear-btn" title="Réinitialiser la conversation" aria-label="Réinitialiser">↺</button>
                    <button class="vecna-hbtn" id="vecna-close" title="Fermer" aria-label="Fermer">✕</button>
                </div>
            </div>

            <!-- MARQUEE -->
            <div class="vecna-marquee">
                <span class="vecna-marquee-inner">
                    ✦ ORACLE DU MONDE À L'ENVERS ✦ PORTFOLIO DE MARVIN BOTTI ✦ BTS SIO — OPTION SLAM ✦ PORTAIL ACTIF ✦
                </span>
            </div>

            <!-- TABS -->
            <div class="vecna-tabs">
                <button class="vecna-tab active" data-tab="chat">💬 Dialogue</button>
                <button class="vecna-tab" data-tab="contact">📨 Contact</button>
            </div>

            <!-- CHAT SECTION -->
            <div class="vecna-section active" id="vecna-chat-section">
                <div id="vecna-messages" role="log" aria-live="polite" aria-label="Messages"></div>
                <div class="vecna-chips" id="vecna-chips"></div>
                <div id="vecna-footer">
                    <textarea id="vecna-input" placeholder="Posez votre question à Vecna..." rows="1" aria-label="Votre message"></textarea>
                    <button id="vecna-send" aria-label="Envoyer">➤</button>
                </div>
            </div>

            <!-- CONTACT SECTION -->
            <div class="vecna-section" id="vecna-contact-section">
                <div id="vecna-contact-form">
                    <div class="vecna-form-title">📨 Contacter Marvin</div>
                    <div class="vecna-form-desc">Votre message traversera les dimensions pour atteindre directement <strong style="color:#ff6b6b">bottimarvin@gmail.com</strong></div>
                    <div class="vecna-form-group">
                        <label for="vcf-name">Votre Nom</label>
                        <input type="text" id="vcf-name" placeholder="Ex: Eleven Hopper" autocomplete="name">
                    </div>
                    <div class="vecna-form-group">
                        <label for="vcf-email">Votre Email</label>
                        <input type="email" id="vcf-email" placeholder="votre@email.com" autocomplete="email">
                    </div>
                    <div class="vecna-form-group">
                        <label for="vcf-subject">Sujet</label>
                        <input type="text" id="vcf-subject" placeholder="Objet de votre message">
                    </div>
                    <div class="vecna-form-group">
                        <label for="vcf-msg">Message</label>
                        <textarea id="vcf-msg" placeholder="Votre message..."></textarea>
                    </div>
                    <button class="vecna-submit" id="vcf-submit">🔮 Envoyer le message</button>
                    <div id="vcf-feedback" style="display:none" class="vecna-feedback"></div>
                    <p style="text-align:center; font-size:11px; color:#666; margin-top:8px;">
                        Ou directement : <a href="mailto:${CONTACT_EMAIL}" style="color:#ff4444">${CONTACT_EMAIL}</a>
                    </p>
                </div>
            </div>
        `;

        document.body.appendChild(trigger);
        document.body.appendChild(panel);
        return { trigger, panel };
    }

    /* ═══════════════════════════════════════════
       💬  CHAT FUNCTIONS
    ═══════════════════════════════════════════ */
    let isOpen = false;

    function addMessage(text, sender = 'vecna', extra = {}) {
        const messages = document.getElementById('vecna-messages');
        const msg = document.createElement('div');
        msg.className = `vmsg ${sender}`;

        const avatar = document.createElement('div');
        avatar.className = 'vmsg-avatar';
        avatar.textContent = sender === 'vecna' ? '🕯️' : '👤';

        const bubble = document.createElement('div');
        bubble.className = 'vmsg-bubble';
        bubble.innerHTML = text.replace(/\n/g, '<br>');

        // Optional link button
        if (extra.link) {
            const lnk = document.createElement('a');
            lnk.href = extra.link.url;
            lnk.textContent = extra.link.label;
            lnk.style.cssText = 'display:inline-block;margin-top:10px;padding:7px 14px;background:rgba(183,28,28,0.2);border:1px solid rgba(255,23,68,0.4);color:#ff4444;text-decoration:none;border-radius:6px;font-size:12px;font-weight:600;letter-spacing:0.5px;transition:all 0.2s';
            lnk.addEventListener('mouseenter', () => { lnk.style.background = 'rgba(183,28,28,0.4)'; });
            lnk.addEventListener('mouseleave', () => { lnk.style.background = 'rgba(183,28,28,0.2)'; });
            bubble.appendChild(document.createElement('br'));
            bubble.appendChild(lnk);
        }

        msg.appendChild(avatar);
        msg.appendChild(bubble);
        messages.appendChild(msg);
        messages.scrollTop = messages.scrollHeight;

        // Speak the message if voice is on
        if (sender === 'vecna') speak(text);

        return msg;
    }

    function showTyping() {
        const messages = document.getElementById('vecna-messages');
        const typing = document.createElement('div');
        typing.className = 'vecna-typing';
        typing.id = 'vecna-typing-indicator';
        typing.innerHTML = `
            <div class="vmsg-avatar">🕯️</div>
            <div class="typing-dots"><span></span><span></span><span></span></div>
        `;
        messages.appendChild(typing);
        messages.scrollTop = messages.scrollHeight;
        return typing;
    }

    function removeTyping() {
        const t = document.getElementById('vecna-typing-indicator');
        if (t) t.remove();
    }

    function setChips(chips) {
        const container = document.getElementById('vecna-chips');
        container.innerHTML = '';
        if (!chips?.length) return;
        chips.forEach(label => {
            const chip = document.createElement('button');
            chip.className = 'vecna-chip';
            chip.textContent = label;
            chip.addEventListener('click', () => handleSend(label));
            container.appendChild(chip);
        });
    }

    function handleSend(text) {
        const input = document.getElementById('vecna-input');
        const q = (text || input.value || '').trim();
        if (!q) return;
        if (!text) { input.value = ''; input.style.height = '40px'; }

        addMessage(q, 'user');
        setChips([]);
        const typing = showTyping();

        // Simulate Vecna thinking
        const delay = 600 + Math.random() * 800;
        setTimeout(() => {
            removeTyping();
            const resp = getResponse(q);

            addMessage(resp.text, 'vecna', { link: resp.link || null });
            setChips(resp.chips || []);

            // Open contact tab if action requested
            if (resp.action === 'open_contact') {
                setTimeout(() => switchTab('contact'), 800);
            }
        }, delay);
    }

    function sendGreeting() {
        if (convState.greetedOnce) return;
        convState.greetedOnce = true;
        setTimeout(() => {
            showTyping();
            setTimeout(() => {
                removeTyping();
                addMessage(vecnaify(pick(VECNA_SPEECH.greet)), 'vecna');
                setChips(['Voir les TPs', 'Mes compétences', 'Options SLAM/SISR', 'Me contacter']);
            }, 1200);
        }, 400);
    }

    function clearChat() {
        const m = document.getElementById('vecna-messages');
        m.innerHTML = '';
        setChips([]);
        convState.greetedOnce = false;
        sendGreeting();
    }

    /* ═══════════════════════════════════════════
       📑  TAB SWITCHING
    ═══════════════════════════════════════════ */
    function switchTab(tab) {
        document.querySelectorAll('.vecna-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
        document.getElementById('vecna-chat-section').classList.toggle('active', tab === 'chat');
        document.getElementById('vecna-contact-section').classList.toggle('active', tab === 'contact');
    }

    /* ═══════════════════════════════════════════
       📧  EMAIL (EmailJS or fallback mailto)
    ═══════════════════════════════════════════ */
    async function sendContactForm() {
        const name = document.getElementById('vcf-name').value.trim();
        const email = document.getElementById('vcf-email').value.trim();
        const subject = document.getElementById('vcf-subject').value.trim();
        const msg = document.getElementById('vcf-msg').value.trim();
        const feedback = document.getElementById('vcf-feedback');
        const btn = document.getElementById('vcf-submit');

        if (!name || !email || !msg) {
            feedback.style.display = 'block';
            feedback.className = 'vecna-feedback error';
            feedback.textContent = '⚠ Veuillez remplir les champs Nom, Email et Message.';
            return;
        }

        btn.disabled = true;
        btn.textContent = '⏳ Envoi en cours...';
        feedback.style.display = 'none';

        // Try EmailJS if configured
        if (EMAILJS_PUBLIC_KEY !== 'YOUR_EMAILJS_PUBLIC_KEY' && window.emailjs) {
            try {
                await window.emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, {
                    from_name: name,
                    from_email: email,
                    subject: subject || 'Message depuis le Portfolio',
                    message: msg,
                    to_email: CONTACT_EMAIL
                }, EMAILJS_PUBLIC_KEY);
                feedback.style.display = 'block';
                feedback.className = 'vecna-feedback success';
                feedback.textContent = '✅ ' + VECNA_SPEECH.email_success;
                ['vcf-name', 'vcf-email', 'vcf-subject', 'vcf-msg'].forEach(id => document.getElementById(id).value = '');
            } catch (err) {
                console.error('EmailJS error:', err);
                fallbackMailto(name, email, subject, msg);
            }
        } else {
            // Fallback: mailto
            fallbackMailto(name, email, subject, msg);
        }

        btn.disabled = false;
        btn.textContent = '🔮 Envoyer le message';
    }

    function fallbackMailto(name, email, subject, msg) {
        const body = `De: ${name} (${email})\n\n${msg}`;
        const mailto = `mailto:${CONTACT_EMAIL}?subject=${encodeURIComponent(subject || 'Message Portfolio')}&body=${encodeURIComponent(body)}`;
        window.open(mailto, '_blank');
        const feedback = document.getElementById('vcf-feedback');
        feedback.style.display = 'block';
        feedback.className = 'vecna-feedback success';
        feedback.textContent = '📨 Votre client mail s\'est ouvert. Si ce n\'est pas le cas, écrivez directement à ' + CONTACT_EMAIL;
    }

    /* ═══════════════════════════════════════════
       🎬  OPEN / CLOSE PANEL
    ═══════════════════════════════════════════ */
    function openPanel() {
        const panel = document.getElementById('vecna-panel');
        panel.classList.add('open');
        isOpen = true;
        playOpenSound();
        sendGreeting();
        // Hide badge
        const badge = document.getElementById('vecna-badge');
        if (badge) badge.style.display = 'none';
        // Focus input
        setTimeout(() => {
            const input = document.getElementById('vecna-input');
            if (input) input.focus();
        }, 400);
    }

    function closePanel() {
        const panel = document.getElementById('vecna-panel');
        panel.classList.remove('open');
        isOpen = false;
        if (synth) synth.cancel();
    }

    function togglePanel() {
        isOpen ? closePanel() : openPanel();
    }

    /* ═══════════════════════════════════════════
       🚀  INIT
    ═══════════════════════════════════════════ */
    function init() {
        const { trigger, panel } = buildWidget();

        // Trigger button
        trigger.addEventListener('click', togglePanel);

        // Close button
        panel.querySelector('#vecna-close').addEventListener('click', closePanel);

        // Clear button
        panel.querySelector('#vecna-clear-btn').addEventListener('click', clearChat);

        // Voice button
        panel.querySelector('#vecna-voice-btn').addEventListener('click', function () {
            voiceEnabled = !voiceEnabled;
            this.textContent = voiceEnabled ? '🔊' : '🔇';
            this.classList.toggle('active', voiceEnabled);
            this.title = voiceEnabled ? 'Désactiver la voix' : 'Activer la voix de Vecna';
            if (!voiceEnabled && synth) synth.cancel();
        });

        // Tabs
        panel.querySelectorAll('.vecna-tab').forEach(tab => {
            tab.addEventListener('click', () => switchTab(tab.dataset.tab));
        });

        // Send button
        panel.querySelector('#vecna-send').addEventListener('click', () => handleSend());

        // Input: Enter to send, Shift+Enter for newline, auto-resize
        const input = panel.querySelector('#vecna-input');
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                handleSend();
            }
            playTypingBeep();
        });
        input.addEventListener('input', () => {
            input.style.height = '40px';
            input.style.height = Math.min(input.scrollHeight, 100) + 'px';
        });

        // Contact form
        panel.querySelector('#vcf-submit').addEventListener('click', sendContactForm);

        // Close on outside click
        document.addEventListener('click', (e) => {
            if (isOpen && !panel.contains(e.target) && !trigger.contains(e.target)) {
                closePanel();
            }
        });

        // Show badge after 5 seconds if not opened
        setTimeout(() => {
            if (!isOpen) {
                const badge = document.getElementById('vecna-badge');
                if (badge) badge.style.display = 'flex';
            }
        }, 5000);

        // Load EmailJS if key configured
        if (EMAILJS_PUBLIC_KEY !== 'YOUR_EMAILJS_PUBLIC_KEY') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js';
            script.onload = () => { window.emailjs.init({ publicKey: EMAILJS_PUBLIC_KEY }); };
            document.head.appendChild(script);
        }
    }

    // Wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
