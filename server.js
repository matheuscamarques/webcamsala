const express = require('express')
const app = express()


const { v4: uuidV4 } = require('uuid')

const https = require('https');
const fs = require('fs');

// readFileSync function must use __dirname get current directory
// require use ./ refer to current directory.

const options = {
    key: fs.readFileSync('./localhost.key'),
    cert: fs.readFileSync('./localhost.crt')
};


// Create HTTPs server.

const server = https.createServer(options, app);
const io = require('socket.io')(server);

app.set('view engine', 'ejs')
app.use(express.static('public'))

app.get('/', (req, res) => {
    res.redirect(`/${uuidV4()}`)
})

app.get('/:room', (req, res) => {
    res.render('room', { roomId: req.params.room })
})

io.on('connection', socket => {
    socket.on('join-room', (roomId, userId) => {
        socket.join(roomId)
        socket.to(roomId).emit('user-connected', userId)

        socket.on('disconnect', () => {
            socket.to(roomId).emit('user-disconnected', userId)
        })
    })
})

server.listen(3000)