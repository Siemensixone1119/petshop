import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    proxy: {
      '/petshop-api': {
        target: 'http://petshop-api',
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/petshop-api/, ''),
      },
    },
  },
})