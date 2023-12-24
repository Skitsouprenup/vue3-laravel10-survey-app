import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

import path from "path"
import { fileURLToPath } from "node:url"

const rootPath = fileURLToPath(import.meta.url)
const rootDirectory = path.dirname(rootPath)

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    host: 'localhost',
    port: 3000
  },
  resolve: {
    alias: [
      { find: '@', replacement: path.resolve(rootDirectory, 'src') },
    ],
  },
})
