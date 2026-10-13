(() => {
  const form = document.querySelector('#registrationForm');

  if (form) {
    const checkboxes = [...form.querySelectorAll('input[name="specialties[]"]')];
    const count = document.querySelector('#selectionCount');
    const error = document.querySelector('#specialtyError');

    const syncSpecialties = () => {
      const selected = checkboxes.filter(input => input.checked).length;
      if (count) count.textContent = `${selected} kiválasztva`;
      checkboxes.forEach(input => input.closest('.nts-specialty-option')?.classList.toggle('is-selected', input.checked));
      if (selected > 0 && error) error.textContent = '';
    };
    checkboxes.forEach(input => input.addEventListener('change', syncSpecialties));
    syncSpecialties();

    form.querySelectorAll('input[type="radio"]').forEach(input => {
      input.addEventListener('change', () => {
        form.querySelectorAll(`input[name="${CSS.escape(input.name)}"]`).forEach(radio => {
          radio.closest('.nts-choice-option')?.classList.toggle('is-selected', radio.checked);
        });
      });
    });

    form.addEventListener('submit', event => {
      let valid = true;
      const fullName = form.querySelector('#full_name');
      const schoolName = form.querySelector('#school_name');
      [fullName, schoolName].forEach(input => {
        const okay = input.value.trim().length >= 2;
        input.setAttribute('aria-invalid', String(!okay));
        if (!okay) valid = false;
      });

      if (checkboxes.length && !checkboxes.some(input => input.checked)) {
        valid = false;
        if (error) error.textContent = 'Jelölj meg legalább egy szakmát.';
      }
      if (!form.querySelector('input[name="accommodation"]:checked') || !form.querySelector('input[name="is_graduating"]:checked')) valid = false;
      if (!form.querySelector('input[name="privacy"]:checked')) valid = false;

      if (!valid) {
        event.preventDefault();
        const firstInvalid = form.querySelector('[aria-invalid="true"]');
        if (firstInvalid) firstInvalid.focus();
        else if (error && error.textContent) error.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  }


  const successNotice = document.querySelector('.nts-notice-success');
  if (successNotice) {
    window.setTimeout(() => {
      successNotice.classList.add('is-dismissing');
      window.setTimeout(() => successNotice.remove(), 400);
    }, 6500);
  }

  const simpleTip = document.querySelector('#simpleTip');
  if (simpleTip) {
    const tips = [
      'Nem kell már most tudnod a választ – a nyílt nap pont a felfedezésről szól.',
      'Gondold át, mit szeretsz csinálni: alkotni, szerelni, számolni vagy programozni.',
      'Kérdezz bátran a tanároktól és a diákoktól – ők is voltak egyszer a helyedben.',
      'Több szakma is érdekelhet. Jelölj meg nyugodtan többet is!',
      'Figyeld meg, milyen feladatokat végeznek a szakmában a mindennapokban.'
    ];
    let tipIndex = 0;
    window.setInterval(() => {
      tipIndex = (tipIndex + 1) % tips.length;
      simpleTip.classList.add('is-changing');
      window.setTimeout(() => {
        simpleTip.textContent = tips[tipIndex];
        simpleTip.classList.remove('is-changing');
      }, 180);
    }, 6500);
  }
})();
