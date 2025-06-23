<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import miscImg from '../assets/misc-img.png';

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
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200 text-center">
                <div class="misc-avatar w-full text-center mb-8">
                    <img
                        :src="miscImg"
                        alt="Imagen Miscelánea"
                        class="max-w-[550px] min-h-[300px] mx-auto w-full h-auto object-contain"
                    />
                </div>

                <h1 class="mt-8 text-2xl font-medium text-gray-900">
                    ¡Bienvenido a la gestión simplificada!
                </h1>

                <p class="mt-6 text-gray-500 leading-relaxed">
                    Esta es su plataforma centralizada para organizar y controlar sus productos y categorías de manera eficiente.
                    Diseñada para ser intuitiva y potente, le permite mantener su inventario al día y sus datos claros,
                    ayudándole a tomar las mejores decisiones con facilidad.
                </p>
            </div>
        </div>
    </div>
</template>

<style lang="css">
.relative {
    position: relative;
}

.sm\:flex {
    display: flex;
}

.sm\:justify-center {
    justify-content: center;
}

.sm\:items-center {
    align-items: center;
}

.min-h-screen {
    min-height: 100vh;
}

.selection\:bg-indigo-500::selection {
    background-color: #6366f1;
}

.selection\:text-white::selection {
    color: #fff;
}

.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45133 0.53759 2.45133 1.22676C2.45133 1.91374 1.91374 2.45133 1.22676 2.45133C0.53759 2.45133 0 1.91374 0 1.22676C0 0.53759 0.53759 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45133 0.53759 2.45133 1.22676C2.45133 1.91374 1.91374 2.45133 1.22676 2.45133C0.53759 2.45133 0 1.91374 0 1.22676C0 0.53759 0.53759 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>