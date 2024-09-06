const fadeElements = document.querySelectorAll('.projectitem');
fadeElements.forEach((el, index) => {
    el.style.setProperty('--delay', `${index * 0.5}s`);
});