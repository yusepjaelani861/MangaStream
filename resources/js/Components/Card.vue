<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import moment from 'moment';

</script>

<script>
export default {
    props: ['manga'],
    data() {
        return {
            // data: data
            images: this.manga.relationships.filter(relationship => relationship.type === 'cover_art')[0].attributes.fileName,
            updated_at: (new Date(this.manga.attributes.updatedAt)).toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric'
            }),
        }
    },
    mounted() {
        //
    },
}
</script>
<template>
    <div id="anime" class="w-full p-1">
        <Link :href="'/manga/' + manga.id">
        <div class="bg-gray-700 text-gray-300 rounded-lg shadow-lg w-full h-full">
            <div class="flex flex-col">
                <div class="h-[142px] relative">
                    <img :src="'https://i0.wp.com/mangadex.org/covers/' + manga.id + '/' + images" alt="image" class="rounded-t-lg w-full h-full object-cover">
                    <!-- Absolute love button -->
                    <div class="absolute top-0 right-0 m-2">
                        <button class="bg-gray-300 rounded-full p-1">
                            <svg viewBox="0 0 10 10" fill="none" class="h-5 w-5 p-[2px]"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.75 3.4375C8.75 2.40208 7.87542 1.5625 6.79667 1.5625C5.99042 1.5625 5.29792 2.03167 5 2.70125C4.70208 2.03167 4.00958 1.5625 3.20292 1.5625C2.125 1.5625 1.25 2.40208 1.25 3.4375C1.25 6.44583 5 8.4375 5 8.4375C5 8.4375 8.75 6.44583 8.75 3.4375Z"
                                    fill="#EF4444" stroke="#EF4444" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                        </button>
                    </div>
                </div>
                <div class="border-b flex justify-between items-center p-1 mb-2">
                    <div class="flex">
                        <svg viewBox="0 0 16 16" fill="none" class="mr-1 w-5 h-5" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.65311 2.33265C7.6813 2.26387 7.72932 2.20503 7.79105 2.16362C7.85279 2.12221 7.92544 2.1001 7.99978 2.1001C8.07411 2.1001 8.14676 2.12221 8.2085 2.16362C8.27023 2.20503 8.31825 2.26387 8.34644 2.33265L9.76311 5.73998C9.78963 5.80374 9.83322 5.85894 9.88909 5.89952C9.94496 5.9401 10.0109 5.96448 10.0798 5.96998L13.7584 6.26465C14.0911 6.29131 14.2258 6.70665 13.9724 6.92331L11.1698 9.32465C11.1174 9.36944 11.0784 9.42778 11.057 9.49328C11.0356 9.55878 11.0326 9.62891 11.0484 9.69598L11.9051 13.286C11.9223 13.358 11.9178 13.4335 11.8921 13.503C11.8665 13.5724 11.8208 13.6327 11.7609 13.6763C11.7009 13.7198 11.6295 13.7445 11.5555 13.7475C11.4815 13.7504 11.4083 13.7313 11.3451 13.6926L8.19511 11.7693C8.13627 11.7335 8.06869 11.7145 7.99978 11.7145C7.93086 11.7145 7.86328 11.7335 7.80444 11.7693L4.65444 13.6933C4.59128 13.732 4.51808 13.7511 4.44408 13.7481C4.37008 13.7452 4.29861 13.7204 4.23869 13.6769C4.17877 13.6334 4.13308 13.5731 4.10741 13.5036C4.08174 13.4342 4.07722 13.3587 4.09444 13.2866L4.95111 9.69598C4.967 9.62891 4.96408 9.55876 4.94267 9.49325C4.92126 9.42773 4.8822 9.36939 4.82978 9.32465L2.02711 6.92331C1.97098 6.87505 1.93038 6.81128 1.91041 6.74C1.89043 6.66873 1.89198 6.59314 1.91485 6.52274C1.93772 6.45235 1.9809 6.39028 2.03895 6.34436C2.097 6.29844 2.16734 6.2707 2.24111 6.26465L5.91978 5.96998C5.98861 5.96448 6.05459 5.9401 6.11046 5.89952C6.16633 5.85894 6.20992 5.80374 6.23644 5.73998L7.65311 2.33331V2.33265Z"
                                fill="#FDE047" stroke="#FDE047" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p class="font-medium">9,5</p>
                    </div>
                    <div class="flex items-center">
                        <button class="bg-gray-300 rounded-full p-3 mr-1"></button>
                        <p class="text-white font-semibold">{{ manga.type.toUpperCase() }}</p>
                    </div>
                </div>
                <div class="p-2 mb-2">
                    <h1 class="text-base md:text-sm font-bold mb-4">{{ manga.attributes.title.en }}</h1>
                    <div id="chapter" class="w-full h-[40px] overflow-auto">
                        <div v-if="manga.attributes.latestUploadedChapter != ''">
                            <Link :href="'/manga/' + manga.id + '/' + manga.attributes.latestUploadedChapter">
                            <div class="bg-gray-600 rounded-md p-2 px-4 mr-1 mb-1 flex justify-between">
                                <p class="text-base">{{ manga.attributes.lastChapter == '' ? 'Newest Chapter' : 'Chapter ' + manga.attributes.lastChapter }}</p>
                                <p class="text-base">{{ moment(manga.attributes.updatedAt).locale('en').fromNow() }}</p>
                            </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </Link>
    </div>
</template>