#!/usr/bin/env node

import { readFileSync } from 'fs'
import { spawn } from 'child_process'

// Parse .env file
const envFile = readFileSync('.env', 'utf8')
const envVars = {}
envFile.split('\n').forEach(line => {
  const trimmed = line.trim()
  if (trimmed && !trimmed.startsWith('#')) {
    const [key, ...rest] = trimmed.split('=')
    if (key && rest.length) envVars[key] = rest.join('=')
  }
})

const proxy = envVars.DEV_URL || 'https://cds.ddev.site'
const port = envVars.BROWSERSYNC_PORT || '3000'

console.log('Starting development server...')
console.log(`Vite: Building assets in watch mode`)
console.log(`Browser-Sync: Proxying ${proxy} on port ${port}`)

// Start Vite build in watch mode
const vite = spawn('npx', ['vite', 'build', '--watch'], {
  stdio: 'inherit',
})

// Start Browser-Sync
const browserSync = spawn('npx', ['browser-sync', 'start',
  '--proxy', proxy,
  '--https',
  '--files', '**/*.php,dist/**/*.css,dist/**/*.js',
  '--ignore', 'node_modules',
  '--port', port,
  '--reload-delay', '200',
], {
  stdio: 'inherit',
  env: {
    ...process.env,
    NODE_TLS_REJECT_UNAUTHORIZED: '0',
    NODE_NO_WARNINGS: '1',
  },
})

process.on('SIGINT', () => {
  vite.kill('SIGTERM')
  browserSync.kill('SIGTERM')
  process.exit(0)
})

process.on('SIGTERM', () => {
  vite.kill('SIGTERM')
  browserSync.kill('SIGTERM')
  process.exit(0)
})
