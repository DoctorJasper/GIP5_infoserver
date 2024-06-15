document.addEventListener('DOMContentLoaded', function() {
    const videoList = document.querySelector('.video-list');
    const videoItems = document.querySelectorAll('.video-item');

    const totalWidth = Array.from(videoItems).reduce((total, item) => {
        return total + item.offsetWidth + parseInt(getComputedStyle(item).marginRight);
    }, 0);

    videoList.style.animationDuration = `${totalWidth / 100}px`;

    videoList.addEventListener('animationiteration', () => {
        videoList.appendChild(videoList.firstElementChild);
    });
});