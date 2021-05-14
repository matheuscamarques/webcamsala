<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Display Webcam Stream</title>

    <style>
    #container {
        margin: 0px auto;

        border: 10px #333 solid;
    }

    #videoElement {

        background-color: #666;
    }
    </style>
</head>

<body>
    <div id="container">
        <video width="250" autoplay="true" id="myvideo">

        </video>
    </div>

    <div id="others">

    </div>

</body>

</html>

<script>
var video = document.querySelector("#myvideo");
if (navigator.mediaDevices.getUserMedia) {
    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(function(stream) {
            video.srcObject = stream;

        })
        .catch(function(err0r) {
            console.log("Something went wrong!", err0r);
        })
}
</script>





<script type="text/javascript">
const ws = new WebSocket('ws://localhost:4005');
const FPS = 3;
const MY_ID = Math.round(getRandomArbitrary(0, 35557));

ws.onopen = () => {
    //console.log(`Connected to ${WS_URL}`);
    setInterval(() => {
        ws.send(JSON.stringify({
            id: MY_ID,
            src: getFrame()
        }));
    }, 1000 / FPS);
}

ws.onmessage = function(msg) {

    msg = JSON.parse(msg.data);
    if (msg.id != MY_ID) {
        console.log(msg);
        if (document.getElementById("other-" + msg.id)) {
            let e = document.getElementById("other-" + msg.id);
            e.src = msg.src;
        } else {
            let pai = document.getElementById("others");
            var oImg = document.createElement("img");
            oImg.setAttribute('src', msg.src);
            oImg.setAttribute('id', "other-" + msg.id);
            oImg.setAttribute('width', '250px');
            pai.appendChild(oImg);
        }
    }
    //document.querySelector('#messages').innerHTML += `<div>${msg.data}</div>`;
};

const getFrame = () => {
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const data = canvas.toDataURL('image/png');
    return data;
}

function getRandomArbitrary(min, max) {
    return Math.random() * (max - min) + min;
}
</script>