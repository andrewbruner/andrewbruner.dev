const header = document.querySelector('header');
const progressBar = document.querySelector('#progress-bar');
const navigationToggle = document.querySelector('#navigation-toggle');
const navigationContent = document.querySelector('#navigation-content');

document.addEventListener('scroll', () => {
	const scrollPosition = window.scrollY;
	const fullHeight = document.querySelector('html').scrollHeight;
	const viewHeight = window.innerHeight;
	const scroll = (scrollPosition / (fullHeight - viewHeight)) * 100
	progressBar.style.setProperty('--scroll', `${scroll}%`);

	if (scrollPosition > 10) {
		header.classList.remove('bg-gray-100');
		header.classList.add('bg-white');
		header.classList.add('shadow');
	} else {
		header.classList.remove('bg-white');
		header.classList.add('bg-gray-100');
		if (navigationContent.classList.contains('hidden')) {
			header.classList.remove('shadow');
		}
	}
});

navigationToggle.addEventListener('click', () => {
	navigationContent.classList.toggle('hidden');
	if (window.scrollY <= 10) {
		header.classList.toggle('shadow');
	}
});
