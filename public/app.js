const pages = [...document.querySelectorAll('[data-page]')];
const route = () => {
  const name = location.pathname.replace(/^\//, '').split('/')[0] || 'home';
  pages.forEach(page => page.hidden = page.dataset.page !== (['about', 'contact', 'artworks'].includes(name) ? name : 'home'));
  document.querySelectorAll('[data-route]').forEach(link => link.classList.toggle('active', link.getAttribute('href') === location.pathname || (name === 'home' && link.getAttribute('href') === '/')));
  window.scrollTo({ top: 0, behavior: 'instant' });
};
document.querySelectorAll('[data-route]').forEach(link => link.addEventListener('click', event => { event.preventDefault(); history.pushState({}, '', link.href); route(); }));
window.addEventListener('popstate', route); route();
document.getElementById('year').textContent = new Date().getFullYear();

document.querySelector('.menu-button').addEventListener('click', event => {
  const button = event.currentTarget; button.setAttribute('aria-expanded', button.getAttribute('aria-expanded') !== 'true'); document.querySelector('.site-header').classList.toggle('menu-open');
});
document.querySelectorAll('[data-filter]').forEach(button => button.addEventListener('click', () => {
  document.querySelectorAll('[data-filter]').forEach(item => item.classList.toggle('is-active', item === button));
  document.querySelectorAll('.art-card').forEach(card => card.hidden = button.dataset.filter !== 'all' && card.dataset.category !== button.dataset.filter);
}));

function escapeHtml(value) {
  const node = document.createElement('span');
  node.textContent = value || '';
  return node.innerHTML;
}

function artworkCard(artwork, featured = false) {
  return `<article class="${featured ? 'featured-card' : 'catalogue-card'}">
    <img src="${encodeURI(artwork.image)}" alt="${escapeHtml(artwork.title)} by Shanaka Kulathunga">
    <div><p>${artwork.status === 'available' ? 'Available' : 'Sold'} · ${escapeHtml(artwork.year)}</p><h2>${escapeHtml(artwork.title)}</h2><small>${escapeHtml(artwork.medium)}<br>${escapeHtml(artwork.size)}<br>${escapeHtml(artwork.id)}</small></div>
  </article>`;
}

const featuredArtworkIds = new Set(['SHAK-04', 'SHAK-14', 'SHAK-24', 'SHAK-41']);
const featuredGrid = document.getElementById('featured-artworks');
featuredGrid.innerHTML = window.artworks.filter(artwork => featuredArtworkIds.has(artwork.id)).map(artwork => artworkCard(artwork, true)).join('');

const catalogueGrid = document.getElementById('catalogue-grid');
const artworkCount = document.getElementById('artwork-count');
function renderCatalogue(status) {
  const selected = window.artworks.filter(artwork => artwork.status === status);
  catalogueGrid.innerHTML = selected.map(artwork => artworkCard(artwork)).join('');
  artworkCount.textContent = `${selected.length} ${status === 'available' ? 'works currently available' : 'works in private collections'}`;
  document.querySelectorAll('[data-artwork-filter]').forEach(button => {
    const active = button.dataset.artworkFilter === status;
    button.classList.toggle('is-active', active);
    button.setAttribute('aria-selected', String(active));
  });
}
document.querySelectorAll('[data-artwork-filter]').forEach(button => button.addEventListener('click', () => renderCatalogue(button.dataset.artworkFilter)));
renderCatalogue('available');

async function sendForm(form, endpoint) {
  const message = form.querySelector('.form-message');
  const button = form.querySelector('button'); button.disabled = true; message.textContent = 'Sending…';
  try {
    const response = await fetch(endpoint, { method: 'POST', body: new FormData(form) });
    const data = await response.json();
    if (!response.ok) throw new Error(data.message || 'Please try again.');
    form.reset(); message.textContent = data.message || 'Thank you — we will be in touch.';
  } catch (error) { message.textContent = error.message; }
  finally { button.disabled = false; }
}
document.querySelectorAll('[data-contact-form]').forEach(form => form.addEventListener('submit', event => { event.preventDefault(); sendForm(event.currentTarget, '/api/contact.php'); }));
document.getElementById('newsletter-form').addEventListener('submit', event => { event.preventDefault(); sendForm(event.currentTarget, '/api/newsletter.php'); });
