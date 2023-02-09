const notFound = document.querySelector('.not-found');
const code = document.createElement('code');
code.classList.add('bg-gray-300', 'p-1', 'rounded', 'text-xs', 'md:text-sm');
code.textContent = window.location.pathname;
notFound.append(code);
