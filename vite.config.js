import { defineConfig } from 'vite';

export default defineConfig({
    base: '/',
    build: {
        rollupOptions: {
            input: {
                home: 'index.html',
                batch: 'batch.html',
                selection: 'selection.html',
                teams: 'team.html',
                resources: 'resources.html'
            }
        }
    },
});