const express = require('express');
const path = require('path');

const app = express();
const publicDir = path.join(__dirname, 'public');

app.use(express.static(publicDir, { extensions: ['html'] }));
app.get('{*splat}', (_req, res) => res.sendFile(path.join(publicDir, 'index.html')));

const port = Number(process.env.PORT || 3000);
app.listen(port, () => console.log(`Shanaka portfolio is running on http://localhost:${port}`));
