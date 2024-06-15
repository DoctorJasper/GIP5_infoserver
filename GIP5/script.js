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

function openModal(videoElement) {
    const modal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');

    modalVideo.src = videoElement.src;
    modal.style.display = 'block';
}

function closeModal() {
    const modal = document.getElementById('videoModal');
    const modalVideo = document.getElementById('modalVideo');

    modal.style.display = 'none';
    modalVideo.src = ''; // Stop the video when the modal is closed
}

window.onclick = function(event) {
    const modal = document.getElementById('videoModal');
    if (event.target == modal) {
        closeModal();
    }
}
