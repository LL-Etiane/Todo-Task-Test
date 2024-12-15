import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'https://todotest.whats-bot.site/api',
  headers: {
    'Content-Type': 'application/json',
  },
});

export default apiClient;
