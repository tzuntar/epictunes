const audio = new Audio();
audio.loop = false;
audio.autoplay = false;

let canvas, ctx, source, context, analyser, fbc_array, bars, bar_x, bar_width, bar_height;

function initMp3Player() {
    context = new AudioContext();
    analyser = context.createAnalyser();
    canvas = document.getElementById('analyzer-render');
    ctx = canvas.getContext('2d');
    source = context.createMediaElementSource(audio);
    source.connect(analyser);
    analyser.connect(context.destination);
    frameLooper();
}

function frameLooper() {
    window.requestAnimationFrame(frameLooper);
    fbc_array = new Uint8Array(analyser.frequencyBinCount);
    analyser.getByteFrequencyData(fbc_array);
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = '#FFF';
    bars = 100;
    for (let i = 0; i < bars; i++) {
        bar_x = i * 3;
        bar_width = 2;
        bar_height = -(fbc_array[i] / 2);
        ctx.fillRect(bar_x, canvas.height, bar_width, bar_height);
    }
}

function initAudioPlayer(mp3path) {
    let playButton, muteButton, seekSlider, volumeSlider,
        seeking = false, current_time, duration_time;
    audio.src = mp3path;
    playButton = document.getElementById("playButton");
    muteButton = document.getElementById("mute-button");
    seekSlider = document.getElementById("seek-slider");
    volumeSlider = document.getElementById("volume-slider");
    current_time = document.getElementById("current-time");
    duration_time = document.getElementById("duration");

    playButton.addEventListener('click', playPause);
    muteButton.addEventListener('click', mute);
    seekSlider.addEventListener('mousedown', (event) => {
        seeking = true;
        seek(event);
    });
    seekSlider.addEventListener('mousemove', (event) => seek(event));
    seekSlider.addEventListener('mouseup', () => seeking = false);
    volumeSlider.addEventListener("mousemove", setVolume);
    audio.addEventListener('timeupdate', () => seekTimeUpdate());

    function playPause() {
        if (context == null)
            initMp3Player();
        if (audio.paused) {
            audio.play();
            //playButton.style.background = "url(images/pause.jpg) no-repeat";
        } else {
            audio.pause();
            //playButton.style.background = "url(images/play.jpg) no-repeat";
        }
    }

    function mute() {
        audio.muted = !audio.muted;
        if (audio.muted) {
            //muteButton.style.background = "url(images/speaker1.jpg) no-repeat";
        } else {
            //muteButton.style.background = "url(images/mute1.jpg) no-repeat";
        }
    }

    function seek(event) {
        if (!seeking) return;
        seekSlider.value = event.clientX - seekSlider.offsetLeft;
        audio.currentTime = audio.duration * (seekSlider.value / 500);
    }

    function setVolume() {
        audio.volume = volumeSlider.value / 100;
    }

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
    }
}