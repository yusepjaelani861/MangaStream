<script setup>
import Content from '@/Components/Content.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import axios from 'axios';


defineProps({
    slug: {
        type: String,
        required: true
    },
    manga: {
        type: Object,
        required: true
    },
    url: {
        type: String,
        required: true
    },
})
</script>

<script>
export default {
    data() {
        return {
            images: "",
            image_url: "",
            author: "",
            release: "",
            totalChapter: "",
            chapter: [],
        };
    },
    mounted() {
        this.images = this.manga.relationships.filter(relationship => relationship.type === "cover_art")[0].attributes.fileName;
        // this.image_url = this.url + "/image/" + this.manga.id + "/" + this.images;
        this.image_url = 'https://i0.wp.com/mangadex.org/covers/' + this.manga.id + '/' + this.images;
        this.author = this.manga.relationships.filter(relationship => relationship.type === "author")[0].attributes.name || "Unknown";
        this.release = this.manga.attributes.publicationDemographic || "Unknown";
        this.totalChapter = "Unknown";

        setTimeout(() => {
            axios.get('/api/chapter?manga_id=' + this.manga.id)
                .then(response => {
                    this.chapter = response.data.data;
                })
                .catch(error => {
                    console.log(error);
                    throw error;
                })
        }, 500)
    },
    components: { Link }
}
</script>

<template>

    <Head>
        <title v-if="manga != ''">{{ manga.attributes.title.en }}</title>
    </Head>

    <Content>
        <div v-if="manga != ''"
            :class="manga == '' ? 'inset-0 bg-gray-900 bg-opacity-50 fixed flex justify-center items-center' : ''">

            <div class="md:h-[300px] h-[300px] flex justify-center mb-5 w-screen"
                :style="{ 'background-image': 'url(' + image_url + ')' }">
                <!-- <img :src="image_url" alt="image" class="object-fill w-full blur-sm z-10" /> -->
            </div>

            <div id="content" class="container mx-auto px-4 z-20">
                <div class="md:flex">
                    <div class="md:w-1/3 flex md:flex-col justify-between">
                        <div class="md:-mt-40 md:w-2/3 -mt-44 md:mx-16 mb-4 w-full">
                            <div class="flex md:flex-col">
                                <img :src="image_url" alt="image" class="object-cover mb-4" />
                                <div class="flex md:items-center items-end mb-4 px-2 justify-center w-full">
                                    <button id="bookmark"
                                        class="bg-indigo-500 hover:bg-indigo-700 rounded-md p-2 px-4 w-full">
                                        Bookmark
                                    </button>
                                    <button id="read" class="bg-red-500 hover:bg-red-700 rounded-md p-2 px-4 ml-2">
                                        <svg viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="w-6 h-6">
                                            <path
                                                d="M19.5 5.25C19.5 2.765 17.401 0.75 14.812 0.75C12.877 0.75 11.215 1.876 10.5 3.483C9.785 1.876 8.123 0.75 6.187 0.75C3.6 0.75 1.5 2.765 1.5 5.25C1.5 12.47 10.5 17.25 10.5 17.25C10.5 17.25 19.5 12.47 19.5 5.25Z"
                                                fill="white" stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="bg-slate-700 rounded-lg text-left px-4 w-full p-2 mb-4">
                                <p class="mb-1">Author: {{ author }}</p>
                                <p class="mb-1">Released: {{ release }}</p>
                                <p class="mb-1">Status: {{ manga.attributes.status }}</p>
                                <p class="mb-1">Total Chapter: {{ totalChapter }}</p>
                                <p class="mb-1">Type: {{ manga.type }}</p>
                                <p class="mb-1">Rating: {{ manga.attributes.contentRating }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:w-2/3 w-full">
                        <h1 class="md:text-3xl text-xl font-bold text-white">
                            {{ manga.attributes.title.en }}
                        </h1>
                        <div id="description" class="w-full mt-4 mb-4">
                            <p class="text-gray-300">
                                {{ manga.attributes.description.en }}
                            </p>
                        </div>
                        <div class="mt-2">
                            <h1 class="text-2xl font-semibold text-white mb-2">Chapters</h1>
                            <div v-if="chapter.length > 0" class="flex items-center mb-2">
                                <!-- Read first button -->
                                <Link :href="`/manga/${manga.id}/${chapter[0].id}`"
                                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md mr-2">
                                Read First
                                </Link>
                                <Link :href="'/manga/' + manga.id + '/' + manga.attributes.latestUploadedChapter"
                                    class="bg-slate-500 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded-md mr-2">
                                Read Last
                                </Link>
                            </div>
                            <!-- Search Chapter -->
                            <input type="text" placeholder="Search..."
                                class="bg-gray-600 text-white rounded-full mb-2 px-4 py-2 w-full md:w-1/3" />

                            <div v-if="chapter.length > 0">
                                <div id="chapter-list" class="w-full md:max-h-[500px] max-h-[300px] overflow-auto"
                                    v-for="ch in chapter" :key="chapter">
                                    <Link Link :href="'/manga/' + manga.id + '/' + ch.id">
                                    <button
                                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-md mb-2 w-full flex justify-between">
                                        <p>Chapter {{ ch.attributes.chapter }}</p>
                                        <p
                                            v-if="ch.relationships.filter((item) => item.type == 'scanlation_group')[0] != null">
                                            {{ ch.relationships.filter((item) => item.type ==
                                                    'scanlation_group')[0].attributes.name || 'Unknown'
                                            }}
                                        </p>
                                    </button>
                                    </Link>
                                </div>
                            </div>

                            <div v-else>
                                <div id="loader" class="flex justify-center">
                                    <svg aria-hidden="true"
                                        class="mr-2 w-16 h-16 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                            fill="currentColor" />
                                        <path
                                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                            fill="currentFill" />
                                    </svg>
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Content>
</template>