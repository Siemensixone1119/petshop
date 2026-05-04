import axios from 'axios'

export const api = axios.create({
  baseURL: '/petshop-api',
  withCredentials: true,
})