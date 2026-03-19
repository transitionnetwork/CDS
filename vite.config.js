import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'
import { viteStaticCopy } from 'vite-plugin-static-copy'
import path from 'path'

export default defineConfig({
  plugins: [
    tailwindcss(),
    viteStaticCopy({
      targets: [
        {
          src: 'assets/images/*',
          dest: 'img',
        },
        {
          src: 'assets/images/icons/*',
          dest: 'img/icons',
        },
      ],
    }),
  ],
  build: {
    outDir: 'dist',
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: path.resolve(__dirname, 'assets/styles/app.css'),
        main: path.resolve(__dirname, 'assets/scripts/main.js'),
        head: path.resolve(__dirname, 'assets/scripts/head.js'),
        'wp-admin': path.resolve(__dirname, 'assets/scripts/wp-admin.js'),
      },
      output: {
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name].js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.names?.[0]?.endsWith('.png') || assetInfo.names?.[0]?.endsWith('.jpg') || assetInfo.names?.[0]?.endsWith('.gif') || assetInfo.names?.[0]?.endsWith('.svg')) {
            return 'img/icons/[name].[ext]'
          }
          if (assetInfo.names?.[0]?.endsWith('.woff') || assetInfo.names?.[0]?.endsWith('.woff2')) {
            return 'css/[name].[ext]'
          }
          return 'css/[name].[ext]'
        },
      },
    },
  },
  resolve: {
    dedupe: ['leaflet'],
  },
  base: './',
})
