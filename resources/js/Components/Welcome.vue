<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import AppSearchHeaderBg from '../assets/app-search-header-bg.png';

defineProps({
    canLogin: {
        type: Boolean,
        default: false,
    },
    canRegister: {
        type: Boolean,
        default: false,
    },
    canRegisterAdmin: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 selection:bg-indigo-500 selection:text-white">
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-end z-10">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:outline-red-500">
                Inicio
            </Link>

            <template v-else>
                <Link :href="route('login')" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:outline-red-500">
                    Iniciar Sesión
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:outline-red-500">
                    Registrarse
                </Link>

                <Link v-if="canRegisterAdmin" :href="route('admin.register')" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:outline-red-500">
                    Registrar Administrador
                </Link>
            </template>
        </div>

        <div>
            <div
                class="p-6 lg:p-8 border-b border-gray-200 welcome-header-bg text-center"
                :style="`background: url(${AppSearchHeaderBg});`"
            >
                <h1 class="mt-8 text-2xl font-medium text-gray-900">
                    ¡Bienvenido a su aplicación de Gestión de Productos y Categorías!
                </h1>

                <p class="mt-6 text-gray-700 leading-relaxed">
    Esta aplicación le permite gestionar eficientemente sus productos y categorizarlos de forma organizada. Ofrece una base sólida y un entorno de desarrollo simple, potente y agradable, para que pueda enfocarse en lo que más importa: la optimización de su inventario y la mejora de la organización.
</p>
            </div>

            <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                <div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 10.5h12M8.25 14.25h12m-12 4.5h12M3.75 6.75h.008v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 10.5h.008v.008H3.75V10.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 14.25h.008v.008H3.75V14.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 18h.008v.008H3.75V18zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                        <h2 class="ms-3 text-xl font-semibold text-gray-900">
                            <Link :href="route('products.index')">Gestión de Productos</Link>
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        Administre fácilmente sus productos con opciones para agregar, editar y eliminar. Mantenga un inventario preciso y detalles actualizados de cada artículo.
                    </p>

                    <p class="mt-4 text-sm">
                        <Link :href="route('products.index')" class="inline-flex items-center font-semibold text-indigo-700">
                            Explorar Productos

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 size-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                    </p>
                </div>

                <div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5V4.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 4.5v15A2.25 2.25 0 0118.75 21H5.25A2.25 2.25 0 013 18.75V7.5M3 7.5h18" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 10.5h12M6 14.5h12" />
                        </svg>
                        <h2 class="ms-3 text-xl font-semibold text-gray-900">
                            <Link :href="route('categories.index')">Organización por Categorías</Link>
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        Clasifique sus productos en categorías personalizadas para una mejor organización y navegación, facilitando la búsqueda y gestión de su inventario.
                    </p>

                    <p class="mt-4 text-sm">
                        <Link :href="route('categories.index')" class="inline-flex items-center font-semibold text-indigo-700">
                            Gestionar Categorías

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 size-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                    </p>
                </div>

                <div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M12 21.75l-4.5-4.5H3.75V6.75a2.25 2.25 0 012.25-2.25h12.5a2.25 2.25 0 012.25 2.25v10.5a2.25 2.25 0 01-2.25 2.25h-6.75z" />
                        </svg>
                        <h2 class="ms-3 text-xl font-semibold text-gray-900">
                            <Link :href="route('products.index')">Visualización de Datos</Link>
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        Acceda a visualizaciones claras y detalladas de sus datos de productos y categorías para una mejor comprensión y toma de decisiones.
                    </p>

                    <p class="mt-4 text-sm">
                        <Link :href="route('products.index')" class="inline-flex items-center font-semibold text-indigo-700">
                            Ver Datos

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 size-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                    </p>
                </div>

                <div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 stroke-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <h2 class="ms-3 text-xl font-semibold text-gray-900">
                            <Link :href="route('profile.show')">Seguridad del Acceso</Link>
                        </h2>
                    </div>

                    <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                        La seguridad es una prioridad. Su información y datos están protegidos con las últimas características de autenticación y verificación, incluyendo autenticación de dos factores.
                    </p>
                    <p class="mt-4 text-sm">
                        <Link :href="route('profile.show')" class="inline-flex items-center font-semibold text-indigo-700">
                            Configurar Seguridad

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 size-5 fill-indigo-500">
                                <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                            </svg>
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="css">
.welcome-header-bg {
    padding: 4rem !important;
    background-size: cover !important;
    background-position: center !important;
    color: white;
}

@media (max-width: 37.5rem) {
    .welcome-header-bg {
        padding: 1.5rem !important;
    }
}
</style>