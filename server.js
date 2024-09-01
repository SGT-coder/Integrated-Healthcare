const express = require('express');
const bodyParser = require('body-parser');
const cors = require('cors');
const { Pool } = require('pg');

// Create a new Express application
const app = express();
const port = 3000;

// Middleware
app.use(bodyParser.json());
app.use(cors());

// Configure PostgreSQL connection
const pool = new Pool({
    user: 'postgres',
    host: 'localhost',
    database: 'ambu_request_list',
    password: 'Sam@2001',
    port: 5432,
});

// Endpoint to handle form submission
app.post('/submit-form', async (req, res) => {
    const { name, age, sex, location, phone } = req.body;

    try {
        const query = 'INSERT INTO UserData (name, age, sex, location, phone) VALUES ($1, $2, $3, $4, $5)';
        await pool.query(query, [name, age, sex, location, phone]);
        res.status(200).send('Request sent successfully');
    } catch (err) {
        console.error(err);
        res.status(500).send('Server error');
    }
});

// Start the server
app.listen(port, () => {
    console.log(`Server running on :${port}`);
});
