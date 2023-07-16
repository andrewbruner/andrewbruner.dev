const email = 'YW5kcmV3YnJ1bmVyQGdtYWlsLmNvbQ==';
const contacts = document.querySelectorAll('.contact');
contacts.forEach(contact => {
	contact.setAttribute('href', `mailto:${atob(email)}`)
});
