<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import Footer from './Footer.vue';


</script>

<script>
export default {
    data() {
        return {
            results: [],
            app_name: import.meta.env.VITE_APP_NAME,
            sidebarOpen: false,
            query: null,
        };
    },
    methods: {
        toggleSidebar() {
            this.sidebarOpen = !this.sidebarOpen;
        },
    },
    components: { Link }
}
</script>

<template>
    <div id="navbar" class="fixed w-full bg-gray-800 p-4 z-30 mx-auto">

        <div id="sidebar-button" class="absolute top-0 left-0 p-4">
            <button class="bg-gray-800 text-white p-1 rounded-full block" @click="toggleSidebar">
                <svg class="w-6 h-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <div id="navbar-title" class="text-white px-10 text-2xl font-bold">
            <Link href="/">{{ app_name }}</Link>
            <!-- <h1 class="text-3xl px-12 md:px-4 font-bold text-white">VueStream</h1>  -->
        </div>

        <div id="navbar-links" class="absolute top-2 right-0 p-2">
            <div class="md:flex hidden flex-row justify-center items-center ">
                <div id="search" class="flex flex-row items-center">
                    <form :action="route('search')" method="GET">
                    <input type="text" name="title" class="bg-gray-700 text-white px-2 -mt-1 rounded-full" placeholder="Search..."
                        v-model="query">
                    </form>
                </div>
            </div>

            <!-- <div class="bg-gray-800 px-1 rounded-md mt-0 mx-1 h-full md:block hidden">
                <div v-if="results.length > 0 && query" class="px-1 mt-0 mx-1">
                    <div v-for="result in results" :key="result.id" id="post" class="w-full md:w-full p-1">
                        <Link :href="route('anime', result.slug)">
                        <div class="bg-gray-600 text-gray-300 rounded-lg shadow-lg w-64 p-2 md:h-[100px]"
                            v-if="results.length > 0">
                            <div class="flex flex-row">
                                <div class="w-1/4">
                                    <img :src="result.poster" class="rounded-lg w-full md:w-16 md:h-20">
                                </div>
                                <div class="w-full px-2">
                                    <div class="flex flex-col">
                                        <div class="text-md w-[150px] font-bold truncate" id="post-title">
                                            {{ result.name }}
                                        </div>
                                        <div class="text-xs w-[150px] whitespace-pre-wrap" id="post-title">
                                            {{ result.description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
    <Transition enter-to-class="opacity-100" enter-active-class="transition ease-out duration-100"
        enter-class="opacity-0" leave-active-class="transition ease-in duration-75 block md:hidden" leave-to-class="opacity-0"
        leave-class="opacity-100">
        <div id="sidebar" :class="!sidebarOpen ? '-translate-x-full ease-out' : 'translate-x-0 ease-in'"
            class="fixed z-20 inset-y-0 z-5 left-0 md:w-64 w-screen transition duration-300 transform bg-gray-800 overflow-y-auto">
            <div class="mt-20"></div>

            <div class="justify-center flex flex-col text-center items-center">
                <div class="w-screen md:w-full">
                    <div class="md:hidden flex flex-row justify-center items-center">
                        <div id="search" class="flex flex-row items-center">
                            <form :action="route('search')" method="GET">
                            <input type="text" name="title" class="bg-gray-700 text-white px-2 -mt-1 rounded-full w-screen m-4"
                                placeholder="Search..." v-model="query">
                            </form>
                        </div>
                    </div>

                    <!-- <div class="bg-gray-800 px-1 rounded-md mt-0 mx-1 h-full md:hidden block">
                        <div v-if="results.length > 0 && query" class="px-1 mt-0 mx-1">
                            <div v-for="result in results" :key="result.id" id="post" class="w-full md:w-full p-1">
                                <Link :href="route('anime', result.slug)">
                                <div class="bg-gray-600 text-gray-300 rounded-lg shadow-lg w-full md:w-64 p-2 md:h-[100px]"
                                    v-if="results.length > 0">
                                    <div class="flex flex-row">
                                        <div class="w-1/4">
                                            <img :src="result.poster" class="rounded-lg w-full md:w-16 md:h-20">
                                        </div>
                                        <div class="w-full px-2">
                                            <div class="flex flex-col">
                                                <div class="text-md w-full md:w-[150px] font-bold truncate" id="post-title">
                                                    {{ result.name }}
                                                </div>
                                                <div class="text-xs w-full md:w-[150px] whitespace-pre-wrap" id="post-title">
                                                    {{ result.description }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </Link>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="text-white mb-2 text-lg font-semibold hover:bg-gray-500 w-full py-1" :class="route().current('home') ? 'bg-gray-500' : ''">
                    <Link :href="route('home')" class="text-white">Home</Link>
                </div>
                <!-- <div class="text-white mb-2 text-lg font-semibold hover:bg-gray-500 w-full py-1">
                    <Link href="/login" class="text-white">Login</Link>
                </div> -->
            </div>
        </div>
    </Transition>

    <main :class="sidebarOpen ? 'md:ml-64' : 'md:ml-0'"
        class="md:transition-all md:duration-500 bg-gray-800 text-gray-300">
        <div class="pt-16 flex justify-center items-center">
            <!-- <div v-if="adsHeader" :class="adsHeader ? 'h-[90px]' : 'h-[90px]'" id="ads-header" class="md:w-[720px] w-screen flex justify-center items-center bg-[url('/ads/720x90v1.png')] object-scale-down md:mx-4">
                <p class="text-center text-white font-bold" v-html="adsHeader"></p>
            </div> -->
        </div>
        <slot />
    </main>
    <Footer />
</template>