let images = [
    'assets/images/slide1.jpg',
    'assets/images/slide2.jpg',
    'assets/images/slide3.jpg',
    'assets/images/slide4.jpg'
];
let current = 0;

function showSlide(index) {
    let slides = document.querySelectorAll('.slider img');
    if (index >= slides.length) current = 0;
    if (index < 0) current = slides.length - 1;
    slides.forEach((slide, i) => {
        slide.classList.remove('active');
        if (i === current) slide.classList.add('active');
    });
}

function nextSlide() {
    current++;
    showSlide(current);
}

function prevSlide() {
    current--;
    showSlide(current);
}

document.addEventListener('DOMContentLoaded', () => {
    let sliderHtml = `
        <div class="slider">
            ${images.map(src => `<img src="${src}" alt="Слайд">`).join('')}
            <button class="prev" onclick="prevSlide()">❮</button>
            <button class="next" onclick="nextSlide()">❯</button>
        </div>
    `;
    document.querySelectorAll('.slider-placeholder').forEach(placeholder => {
        placeholder.innerHTML = sliderHtml;
        showSlide(0);
        setInterval(nextSlide, 3000);
    });
});