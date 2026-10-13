import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { VitePWA } from 'vite-plugin-pwa'

// https://vitejs.dev/config/
export default defineConfig({
  base:'./',
  plugins: [
    vue(),
    VitePWA({
      registerType: 'autoUpdate', // Automatically updates the service worker
      injectRegister: 'auto', // Injects the service worker registration
      devOptions: {
        enabled: true, // Enables PWA in development mode
      },
      manifest: {
        name: 'Robot Lab App',
        short_name: 'LabApp',
        description: '',
        theme_color: '#000000',
        icons: [
          {
            src: 'web-app-manifest-192x192.png',
            sizes: '192x192',
            type: 'image/png',
          },
          {
            src: 'web-app-manifest-512x512.png',
            sizes: '512x512',
            type: 'image/png',
          },
        ],
      },
    }),
  ], server: {
    host: true
  },
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  build: {
    chunkSizeWarningLimit: 1600,
  },
})
