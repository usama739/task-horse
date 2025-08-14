import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'

// https://vite.dev/config/
export default defineConfig({
  base: '/taskhorse/',
  plugins: [react(), tailwindcss()],
  // Build configuration
  build: {
    outDir: 'dist',
    assetsDir: 'assets',
    sourcemap: false
  },
  
  // Development server configuration
  server: {
    host: '0.0.0.0',
    port: 3000
  },
  
  // Preview server configuration (for production testing)
  preview: {
    host: '0.0.0.0',
    port: 3000
  }
})
