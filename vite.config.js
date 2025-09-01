import { defineConfig } from 'vite';

export default defineConfig({
    base: '/',
    build: {
        rollupOptions: {
            input: {
                home: 'index.php',
                batch: 'batch.php',
                selection: 'selection.php',
                teams: 'team.php',
                resources: 'resources.php',
                contact: 'contact.php',
            }
        }
    },
});