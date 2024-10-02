 function playNotificationSound() {
        const audio = new Audio('/sound.wav');
        audio.play();
    }

document.addEventListener('DOMContentLoaded', function () {
    Livewire.on('play-notification-sound', function () {
        playNotificationSound();
    });
});