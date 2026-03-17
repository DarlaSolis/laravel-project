const express = require('express');
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const cors = require('cors');

const app = express();
const port = 3000;

// Middleware
app.use(cors());
app.use(express.json());

// Initialize WhatsApp Client with LocalAuth to persist session
const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    }
});

let isClientReady = false;

// WhatsApp Client Event Listeners
client.on('qr', (qr) => {
    // Generate and display QR Code in terminal
    console.log('QR Code received, scan please:');
    qrcode.generate(qr, { small: true });
});

client.on('ready', () => {
    console.log('Client is ready!');
    isClientReady = true;
});

client.on('auth_failure', msg => {
    console.error('AUTHENTICATION FAILURE', msg);
});

client.on('disconnected', (reason) => {
    console.log('Client was logged out', reason);
    isClientReady = false;
});

// Initialize client
client.initialize();

// API Endpoint to send messages
app.post('/send-message', async (req, res) => {
    try {
        const { phone, message } = req.body;

        if (!phone || !message) {
            return res.status(400).json({ success: false, error: 'Phone and message are required' });
        }

        if (!isClientReady) {
            return res.status(503).json({ success: false, error: 'WhatsApp client is not ready' });
        }

        // whatsapp-web.js requires the phone number in the format: 5219991234567@c.us
        const chatId = `${phone}@c.us`;

        // Send the message
        await client.sendMessage(chatId, message);

        console.log(`Message sent to ${phone}`);
        return res.status(200).json({ success: true, message: 'Message sent successfully' });
    } catch (error) {
        console.error('Error sending message:', error);
        return res.status(500).json({ success: false, error: 'Failed to send message' });
    }
});

app.listen(port, () => {
    console.log(`WhatsApp Service listening at http://localhost:${port}`);
});
