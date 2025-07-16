import axios from 'axios';
import { Clerk } from '@clerk/clerk-js';

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  withCredentials: true,
});

export default api;