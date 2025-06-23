import './bootstrap';
import '../css/app.css'; // Your global CSS

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // Ziggy for Laravel routes

// --- Vuetify Imports ---
import '@mdi/font/css/materialdesignicons.css'; // Material Design Icons CSS
import 'vuetify/styles'; // Vuetify's core CSS
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components'; // Important: Import components
import * as directives from 'vuetify/directives'; // Important: Import directives
import { aliases, mdi } from 'vuetify/iconsets/mdi'; // MDI icon aliases and sets

// Import Vuetify's Spanish locale
import { es } from 'vuetify/locale'; // <--- Import Spanish locale

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => title,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        // --- Initialize Vuetify INSIDE the setup method ---
        const vuetify = createVuetify({
            components, // Pass components to Vuetify
            directives, // Pass directives to Vuetify
            icons: {
                defaultSet: 'mdi',
                aliases,
                sets: {
                    mdi,
                },
            },
            // --- Add this section for i18n ---
            locale: {
                locale: 'es', // Set default locale to Spanish
                fallback: 'en', // Fallback to English if a string isn't found in Spanish
                messages: { es }, // Load Vuetify's default Spanish messages
                // Override or add custom messages for specific Vuetify strings
                t: (key, ...params) => {
                    // Check for the specific key for "Items per page"
                    if (key === '$vuetify.dataFooter.itemsPerPageText') {
                        return 'Elementos por página:'; // Your custom Spanish text
                    }
                    // You might want to translate other common data table texts too:
                    if (key === '$vuetify.dataFooter.nextPage') {
                        return 'Siguiente página';
                    }
                    if (key === '$vuetify.dataFooter.prevPage') {
                        return 'Página anterior';
                    }
                    // This handles the "1-5 of 10" type text.
                    // Vuetify passes parameters for start index, end index, and total.
                    if (key === '$vuetify.dataFooter.pageText') {
                         return `${params[0]}-${params[1]} de ${params[2]}`;
                    }


                    // Fallback to default Vuetify Spanish translation if not explicitly overridden
                    // If 'es.messages[key]' is undefined, it will just return the original key string.
                    return es.messages[key] || key;
                },
            },
            // --- End i18n section ---
            // You can add your theme customizations here as before
            // theme: {
            //     defaultTheme: 'light',
            //     themes: {
            //         light: {
            //             colors: {
            //                 primary: '#1867C0',
            //                 secondary: '#5C6B7C',
            //             },
            //         },
            //     },
            // },
        });

        // --- Create the Vue App Instance and use plugins ---
        return createApp({ render: () => h(App, props) })
            .use(plugin)    // Inertia plugin
            .use(ZiggyVue)  // Ziggy plugin
            .use(vuetify)   // Vuetify plugin
            .mount(el);     // Mount the app to the Inertia element
    },
    progress: {
        color: '#4B5563',
    },
});