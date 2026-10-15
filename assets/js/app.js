(() => {
  const menuButton = document.querySelector('.menu-toggle');
  const nav = document.querySelector('.main-nav');
  if (menuButton && nav) {
    menuButton.addEventListener('click', () => {
      const open = nav.classList.toggle('open');
      menuButton.setAttribute('aria-expanded', String(open));
      menuButton.innerHTML = `<i class="bi ${open ? 'bi-x-lg' : 'bi-list'}"></i>`;
    });
  }

  const form = document.querySelector('#application-form');
  if (!form) return;
  const steps = [...form.querySelectorAll('.form-step')];
  const navSteps = [...document.querySelectorAll('[data-step-nav]')];
  const fill = document.querySelector('.progress-fill');
  let current = 1;

  const showStep = (number, scroll = true) => {
    current = Math.min(steps.length, Math.max(1, number));
    steps.forEach((step, index) => {
      const active = index + 1 === current;
      step.classList.toggle('active', active);
      step.querySelectorAll('input,select,textarea,button').forEach((el) => {
        if (el.matches('[data-next],[data-prev]')) return;
        el.disabled = !active;
      });
    });
    navSteps.forEach((button, index) => {
      button.classList.toggle('active', index + 1 === current);
      button.classList.toggle('complete', index + 1 < current);
      button.setAttribute('aria-current', index + 1 === current ? 'step' : 'false');
    });
    if (fill) fill.style.width = `${((current - 1) / (steps.length - 1)) * 82}%`;
    if (scroll) steps[current - 1]?.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  const validateCurrent = () => {
    const active = steps[current - 1];
    const controls = [...active.querySelectorAll('input,select,textarea')].filter(el => !el.disabled && el.type !== 'checkbox' && el.type !== 'radio');
    for (const control of controls) {
      if (!control.checkValidity()) {
        control.reportValidity();
        control.focus();
        return false;
      }
    }
    const radios = [...active.querySelectorAll('input[type=radio][required]')];
    for (const radio of radios) {
      if (!active.querySelector(`input[name="${CSS.escape(radio.name)}"]:checked`)) {
        radio.reportValidity();
        return false;
      }
    }
    if (current === 4 && !active.querySelector('input[name="funding_use[]"]:checked')) {
      alert(document.documentElement.lang === 'en' ? 'Select at least one intended use of funds.' : 'Sélectionnez au moins une utilisation prévue du financement.');
      return false;
    }
    return true;
  };

  form.querySelectorAll('[data-next]').forEach(button => button.addEventListener('click', () => {
    if (validateCurrent()) showStep(current + 1);
  }));
  form.querySelectorAll('[data-prev]').forEach(button => button.addEventListener('click', () => showStep(current - 1)));
  form.addEventListener('submit', (event) => {
    if (!validateCurrent()) {
      event.preventDefault();
      return;
    }
    steps.forEach(step => step.querySelectorAll('input,select,textarea').forEach(el => { el.disabled = false; }));
  });
  navSteps.forEach(button => button.addEventListener('click', () => {
    const target = Number(button.dataset.stepNav);
    if (target <= current) showStep(target);
  }));

  const activityFields = document.querySelector('.activity-fields');
  const activityHint = document.querySelector('.activity-hint');
  const activityRadios = [...form.querySelectorAll('input[name="has_activity"]')];
  const syncActivity = () => {
    const hasActivity = form.querySelector('input[name="has_activity"]:checked')?.value === 'yes';
    activityFields?.toggleAttribute('hidden', !hasActivity);
    if (activityHint) activityHint.hidden = hasActivity;
    activityFields?.querySelectorAll('input,textarea').forEach(el => { el.disabled = !hasActivity; });
  };
  activityRadios.forEach(radio => radio.addEventListener('change', syncActivity));
  syncActivity();
  showStep(1, false);
})();
