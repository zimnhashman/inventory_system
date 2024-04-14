// axios.js
import axios from 'axios';

const apiClient = axios.create({
    baseURL: 'http://http://127.0.0.1:8000/api', // Replace with your Laravel API base URL
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
    },
});

export default apiClient;
