const audio = new Audio();
audio.loop = false;
audio.autoplay = false;

let canvas, ctx, source, context, analyser, fbc_array, bars, bar_x, bar_width, bar_height;

function initMp3Player(rgbBarColor) {
    context = new AudioContext();
    analyser = context.createAnalyser();
    canvas = document.getElementById('analyzer-render');
    ctx = canvas.getContext('2d');
    source = context.createMediaElementSource(audio);
    source.connect(analyser);
    analyser.connect(context.destination);
    frameLooper(rgbBarColor);
}

function frameLooper(rgbBarColor) {
    window.requestAnimationFrame(frameLooper);
    fbc_array = new Uint8Array(analyser.frequencyBinCount);
    analyser.getByteFrequencyData(fbc_array);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = rgbBarColor;
    bars = 100;
    for (let i = 0; i < bars; i++) {
        bar_x = i * 3;
        bar_width = 2;
        bar_height = -(fbc_array[i] / 2);
        ctx.fillRect(bar_x, canvas.height, bar_width, bar_height);
***REMOVED***
}

function initAudioPlayer(mp3path, rgbBarColor) {
    let playButton, seekSlider, volumeSlider,
        seeking = false, current_time, duration_time;
    audio.src = mp3path;
    playButton = document.getElementById("playButton");
    seekSlider = document.getElementById("seek-slider");
    volumeSlider = document.getElementById("volume-slider");
    current_time = document.getElementById("current-time");
    duration_time = document.getElementById("duration");

    playButton.addEventListener('click', playPause);
    seekSlider.addEventListener('mousedown', (event) => {
        seeking = true;
        seek(event);
***REMOVED***);
    seekSlider.addEventListener('mousemove', (event) => seek(event));
    seekSlider.addEventListener('mouseup', () => seeking = false);
    volumeSlider.addEventListener("mousemove", setVolume);
    audio.addEventListener('timeupdate', () => seekTimeUpdate());
    const seekLinks = document.getElementsByClassName('seek-link');
    for (let e = 0; e < seekLinks.length; e++) {
        const timeSplit = seekLinks[e].innerHTML.split(':')
        const timeInSeconds = (parseInt(timeSplit[0]) * 60)
            + parseInt(timeSplit[1]);
        seekLinks[e].addEventListener('click', () => seekTo(timeInSeconds))
***REMOVED***

    function playPause() {
        if (context == null)
            initMp3Player(rgbBarColor);
        if (audio.paused) {
            audio.play();
            playButton.children[0].src = './assets/img/icons/pause.svg';
    ***REMOVED*** else {
            audio.pause();
            playButton.children[0].src = './assets/img/icons/play.svg';
    ***REMOVED***
***REMOVED***

    function seek(event) {
        if (!seeking) return;
        seekSlider.value = event.clientX - seekSlider.offsetLeft;
        audio.currentTime = audio.duration * (seekSlider.value / 500);
***REMOVED***

    function seekTo(timeInSeconds) {
        if (context == null)
            initMp3Player(rgbBarColor);
        audio.currentTime = timeInSeconds;
        seekSlider.value = (seekSlider.value / 500) / audio.duration;
***REMOVED***

    function setVolume() {
        audio.volume = volumeSlider.value / 100;
***REMOVED***

    function seekTimeUpdate() {
        seekSlider.value = audio.currentTime * (500 / audio.duration);
        let currentMin = Math.floor(audio.currentTime / 60),
            currentSec = Math.floor(audio.currentTime - currentMin * 60),
            durationMin = Math.floor(audio.duration / 60),
            durationSec = Math.floor(audio.duration - durationMin * 60);
        if (currentSec < 10) currentSec = '0' + currentSec;
        if (durationSec < 10) durationSec = '0' + durationSec;
        if (currentMin < 10) currentMin = '0' + currentMin;
        if (durationMin < 10) durationMin = '0' + durationMin;
        current_time.innerHTML = currentMin + ':' + currentSec;
        duration_time.innerHTML = durationMin + ':' + durationSec;
***REMOVED***
}
