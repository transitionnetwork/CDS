import { defineConfig, loadEnv } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import browserSync from 'vite-plugin-browser-sync';
import { resolve } from 'path';
import { cpSync } from 'fs';

export default defineConfig(({ mode }) => {
  const env = loadEnv(mode, process.cwd(), '');

  return {
    base: './',
    plugins: [
      tailwindcss(),
      mode === 'development' && browserSync({
        bs: {
          proxy: env.DEV_URL || 'https://cds.ddev.site',
          open: false,
          notify: false,
          files: [
            '**/*.php',
            'dist/**/*',
          ],
        },
      }),
      {
        name: 'copy-static-images',
        closeBundle() {
          cpSync(
            resolve(__dirname, 'assets/images'),
            resolve(__dirname, 'dist/img'),
            { recursive: true, filter: (src) => !src.endsWith('.DS_Store') }
          );
        },
      },
    ].filter(Boolean),

    build: {
      outDir: 'dist',
      manifest: false,
      rollupOptions: {
        input: {
          main: resolve(__dirname, 'assets/scripts/main.js'),
          head: resolve(__dirname, 'assets/scripts/head.js'),
          'wp-admin': resolve(__dirname, 'assets/scripts/wp-admin.js'),
        },
        output: {
          entryFileNames: 'js/[name].js',
          chunkFileNames: 'js/[name].js',
          assetFileNames: (assetInfo) => {
            const name = assetInfo.name || '';
            if (name.endsWith('.css')) return 'css/[name][extname]';
            if (/\.(woff2?|eot|ttf|otf)$/.test(name)) return 'fonts/[name][extname]';
            if (/\.(png|jpe?g|gif|svg|ico|webp)$/.test(name)) return 'img/[name][extname]';
            return 'assets/[name][extname]';
          },
        },
      },
    },
  };
});
