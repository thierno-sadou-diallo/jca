import './bootstrap';

const navToggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-nav]');
const header = document.querySelector('[data-header]');

if (navToggle && nav) {
    navToggle.addEventListener('click', () => {
        const isOpen = nav.classList.toggle('is-open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
    });
}

if (header) {
    const setHeaderState = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 10);
    };
    setHeaderState();
    window.addEventListener('scroll', setHeaderState, { passive: true });
}

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    const field = button.closest('.password-field');
    const input = field?.querySelector('[data-password-input]');

    if (!input) {
        return;
    }

    button.addEventListener('click', () => {
        const shouldShow = input.type === 'password';
        input.type = shouldShow ? 'text' : 'password';
        button.setAttribute('aria-label', shouldShow ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
        button.setAttribute('aria-pressed', String(shouldShow));
    });
});

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.14 });

document.querySelectorAll('.reveal').forEach((element) => revealObserver.observe(element));

const searchablePages = [
    { title: 'Immigration', url: '/immigration', terms: 'visa residence permanente permis etude travail citoyennete asile famille statut' },
    { title: 'Recrutement international', url: '/recrutement-international', terms: 'cv emploi employeur talents offre recrutement travailleurs integration' },
    { title: 'Cooperation internationale', url: '/cooperation-internationale', terms: 'ong institution projet financement collectivites partenaires developpement' },
    { title: 'Developpement durable', url: '/developpement-durable', terms: 'femmes jeunes entrepreneuriat sante education inclusion resilience' },
    { title: 'Emplois', url: '/emplois', terms: 'offres candidature filtres cv diplomes contrat entreprise' },
    { title: 'Consultation', url: '/consultation', terms: 'rendez-vous paiement calendrier confirmation accompagnement strategie' },
    { title: 'Contact', url: '/contact', terms: 'telephone whatsapp messenger email horaires formulaire carte' },
    { title: 'FAQ', url: '/faq', terms: 'questions delais garanties visa entreprise cv' },
];

document.querySelectorAll('[data-site-search]').forEach((form) => {
    const input = form.querySelector('input');
    const results = form.querySelector('[data-search-results]');

    const render = () => {
        const query = input.value.trim().toLowerCase();
        if (!query) {
            results.classList.remove('is-visible');
            results.innerHTML = '';
            return;
        }

        const matches = searchablePages.filter((page) => {
            return `${page.title} ${page.terms}`.toLowerCase().includes(query);
        }).slice(0, 4);

        results.innerHTML = matches.length
            ? matches.map((page) => `<a href="${page.url}"><strong>${page.title}</strong><span> Ouvrir la page</span></a>`).join('')
            : '<span>Aucun resultat direct. Essayez immigration, CV, emploi ou partenariat.</span>';
        results.classList.add('is-visible');
    };

    input.addEventListener('input', render);
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        render();
    });
});

document.querySelectorAll('[data-lead-form]').forEach((form) => {
    const note = form.querySelector('[data-form-note]');
    const submitButton = form.querySelector('button[type="submit"]');
    const submitLabel = submitButton?.dataset.submitLabel || submitButton?.textContent || 'Envoyer';

    const setNote = (message, state = 'info') => {
        if (!note) {
            return;
        }

        note.textContent = message;
        note.dataset.state = state;
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;
        submitButton.textContent = 'Envoi en cours...';
        setNote('Transmission securisee de votre demande...', 'loading');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                const firstError = data.errors
                    ? Object.values(data.errors).flat()[0]
                    : 'Impossible d envoyer la demande pour le moment.';

                throw new Error(firstError);
            }

            setNote(`${data.message} Reference: ${data.reference}`, 'success');
            form.reset();
        } catch (error) {
            setNote(error.message || 'Une erreur est survenue. Verifiez les champs et reessayez.', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = submitLabel;
        }
    });
});

document.querySelectorAll('[data-appointment-picker]').forEach((form) => {
    const weekSelect = form.querySelector('[data-week-select]');
    const slotSelect = form.querySelector('[data-slot-select]');

    if (!weekSelect || !slotSelect) {
        return;
    }

    const allOptions = Array.from(slotSelect.options).map((option) => option.cloneNode(true));

    const renderSlots = () => {
        const week = weekSelect.value;
        const matchingOptions = allOptions.filter((option) => option.dataset.week === week);

        slotSelect.innerHTML = '';

        if (!matchingOptions.length) {
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = 'Aucun creneau disponible pour cette selection';
            slotSelect.appendChild(emptyOption);
            slotSelect.disabled = true;
            return;
        }

        matchingOptions.forEach((option) => slotSelect.appendChild(option.cloneNode(true)));
        slotSelect.disabled = false;
    };

    weekSelect.addEventListener('change', renderSlots);
    renderSlots();
});
