const fs = require('fs');
const { PeerServer } = require('peer');

const peerServer = PeerServer({
	  port: 3001,
	  ssl: {
		      key: fs.readFileSync('localhost.key'),
		      cert: fs.readFileSync('localhost.crt')
		    }
});
