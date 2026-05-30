let images = [
    'assets/images/slide1.png',
    'assets/images/slide2.png',
    'assets/images/slide3.png',
    'assets/images/slide4.png'
];
let current = 0;

function showSlide(index) {
    let slides = document.querySelectorAll('.slider img');
    if (slides.length === 0) return;
    current = (index + slides.length) % slides.length;
    slides.forEach((slide, i) => {
        slide.classList.toggle('active', i === current);
    });
}

function nextSlide() {
    showSlide(current + 1);
}

function prevSlide() {
    showSlide(current - 1);
}

document.addEventListener('DOMContentLoaded', () => {
    let sliderHtml = `
        <div class="slider">
            ${images.map(src => `<img src="${src}" alt="Слайд">`).join('')}
            <button class="prev" type="button">❮</button>
            <button class="next" type="button">❯</button>
        </div>
    `;
    document.querySelectorAll('.slider-placeholder').forEach(placeholder => {
        placeholder.innerHTML = sliderHtml;
        const sliderElement = placeholder.querySelector('.slider');
        if (!sliderElement) return;

        showSlide(0);
        const prevButton = sliderElement.querySelector('.prev');
        const nextButton = sliderElement.querySelector('.next');
        if (prevButton) prevButton.addEventListener('click', prevSlide);
        if (nextButton) nextButton.addEventListener('click', nextSlide);

        setInterval(nextSlide, 3000);
    });
});