
document.addEventListener('DOMContentLoaded', function() {
    const videoList = document.querySelector('.video-list');
    const videoItems = document.querySelectorAll('.video-item');
    const animationDuration = 10000; // 10 seconds in milliseconds
    let animationInterval;

    // Function to start the scrolling animation
    function startScrollAnimation() {
        // Calculate total width of the video list
        const totalWidth = Array.from(videoItems).reduce((total, item) => {
            return total + item.offsetWidth + parseInt(getComputedStyle(item).marginRight);
        }, 0);

        // Set animation duration dynamically based on total width
        videoList.style.animation = `scrollVideos ${totalWidth / 100}px linear`;

        // Start animation after 2 seconds
        setTimeout(function() {
            videoList.style.animationPlayState = 'running';
        }, 2000);

        // Restart animation when it ends
        animationInterval = setInterval(function() {
            videoList.style.animationPlayState = 'paused'; // Pause animation briefly
            setTimeout(function() {
                videoList.style.animationPlayState = 'running'; // Resume animation
            }, 100); // Short pause to ensure smooth restart
        }, animationDuration);
    }

    // Call the function to start the animation
    startScrollAnimation();
});