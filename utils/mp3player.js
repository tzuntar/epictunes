const audio = new Audio();
audio.src = 'userdata/music/i_like_that.mp3';
audio.loop = false;
audio.autoplay = false;
//audio.play();

let canvas, ctx, source, context, analyser, fbc_array, bars, bar_x, bar_width, bar_height;

window.addEventListener('load', initMp3Player, false);

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

function initAudioPlayer() {
    let playButton, muteButton, seekSlider, volumeSlider, seeking = false, current_time, duration_time;
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
        if (audio.paused) {
            audio.play();
            //playButton.style.background = "url(images/pause.jpg) no-repeat";
        } else {
            audio.pause();
            //playButton.style.background = "url(images/play.jpg) no-repeat";
        }
    }

    function mute() {
        if (audio.muted) {
            audio.muted = false;
            //muteButton.style.background = "url(images/speaker1.jpg) no-repeat";
        } else {
            audio.muted = true;
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

window.addEventListener('load', initAudioPlayer);
