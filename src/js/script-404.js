// Add pathname to 404.html error message
const span404 = document.querySelector('.path-404');
const code404 = document.createElement('code');
code404.classList.add('bg-gray-300', 'p-1', 'rounded', 'text-xs', 'md:text-sm');
code404.textContent = window.location.pathname;
span404.append(code404);

// Realign text if it wraps
const p404 = span404.parentElement;
const pStyle = getComputedStyle(p404);
const pHeight = parseInt(pStyle.height);
const pLineHeight = parseInt(pStyle.lineHeight);
if (pHeight > pLineHeight) {
	p404.classList.toggle('text-center');
}
