import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import AutoImport from 'unplugin-auto-import/vite';
import Components from 'unplugin-vue-components/vite';
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers';

export default defineConfig({
    plugins: [
        AutoImport({
            resolvers: [
                ElementPlusResolver({
                  importStyle: 'scss',
                }),
            ],
        }),
        Components({
          extensions: ['vue', 'md'],
          include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
          resolvers: [
            ElementPlusResolver({
              importStyle: 'scss',
            }),
          ],
        }),
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
      alias: {
        ziggy: 'vendor/tightenco/ziggy/dist/vue.es.js',
      },
    },
    server: {
      hmr: {
        host: 'sales-project.local',
      },
      https: {
          key: 'D:/laragon/etc/ssl/laragon.key',
          cert: 'D:/laragon/etc/ssl/laragon.crt',
      },
    },
    build: {
        rollupOptions: {
            output:{
                manualChunks(id) {
                    if (id.includes('node_modules')) {
                        return id.toString().split('node_modules/')[1].split('/')[0].toString();
                    }
                }
            }
        }
    }
});
