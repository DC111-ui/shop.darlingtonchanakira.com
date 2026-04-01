document.addEventListener('DOMContentLoaded', () => {
  const reveals = document.querySelectorAll('[data-reveal]');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.16 });

  reveals.forEach((el) => observer.observe(el));

  document.addEventListener('click', (event) => {
    const trigger = event.target.closest('.add_to_cart_button, .single_add_to_cart_button');
    if (!trigger) return;

    trigger.classList.add('loading');
    window.setTimeout(() => trigger.classList.remove('loading'), 700);
  });
});
