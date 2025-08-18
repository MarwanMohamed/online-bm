function playNotificationSound(recordingId) {
  const audio = new Audio('/sound.wav');
  audio.play().then(() => {
    fetch('/update-last-checked/' + recordingId);
  }).catch(error => {
    console.error('Audio playback failed:', error);
  });
}


function checkForNewRecording() {
  fetch('/check-new-recording')
    .then(response => response.json())
    .then(data => {
      if (data.new_recording) {
        playNotificationSound(data.recording_id);
      }
    })
    .catch(error => console.error('Error:', error));
}

setInterval(checkForNewRecording, 5000);