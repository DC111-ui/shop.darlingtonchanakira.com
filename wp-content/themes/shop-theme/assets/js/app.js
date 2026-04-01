document.addEventListener('click', (event) => {
  const trigger = event.target.closest('.add_to_cart_button, .single_add_to_cart_button');
  if (!trigger) return;

  trigger.classList.add('loading');
  setTimeout(() => trigger.classList.remove('loading'), 800);
});
