<script setup>
import Content from '@/Components/Content.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import axios from 'axios';


defineProps({
    slug: String,
    chapter: String,
    content: Object,
    manga: Object,
})
</script>

<script>
export default {
    data() {
        window.onscroll = () => {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            if (scrolled == 100) {
                if (this.nextChapter != "") {
                    window.location.href = "/manga/" + this.slug + "/" + this.nextChapter.id;
                }
            }
            document.getElementById("progress").style.width = scrolled + "%";
            // var bottomOfWindow = document.documentElement.scrollTop + window.innerHeight == document.documentElement.offsetHeight;
            // if (bottomOfWindow) {
            //     if (this.nextChapter != '') {
            //         window.location.href = '/manga/' + this.slug + '/' + this.nextChapter.id;
            //     }
            // }
        };
        return {
            images: "",
            nextChapter: "",
            listChapter: []
        };
    },
    mounted() {
        var avai = null;
        this.ch = this.chapter;
        this.availableChapter = avai;
        axios.get(`/api/server/${this.chapter}`)
            .then(response => {
                this.images = response.data;
                setTimeout(() => {
                    axios.get(`/api/chapter?manga_id=` + this.slug)
                        .then(response => {
                            var data = response.data.data;
                            this.listChapter = data;
                            var chapter = data.filter(chapter => chapter.id === this.chapter)[0];
                            var index = data.indexOf(chapter);
                            index += 1;
                            if (index > 0) {
                                this.nextChapter = data[index];
                            }
                            if (this.nextChapter == undefined) {
                                this.nextChapter = "";
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }, 500);
            })
            .catch(error => {
                console.log(error);
                this.images = null;
            });
        var disqus_config = function () {
            this.page.url = window.location.href;
            this.page.identifier = window.location.href;
        };
        (function () {
            var d = document, s = d.createElement("script");
            s.src = "https://mangastream-1.disqus.com/embed.js";
            s.setAttribute("data-timestamp", +new Date());
            (d.head || d.body).appendChild(s);
        })();
    },
    methods: {
        goToChapter() {
            window.location.href = "/manga/" + this.slug + "/" + this.ch;
        },
    },
    components: { Link }
}
</script>

<template>

    <Head>
        <title>{{ slug }}</title>
    </Head>

    <Content>
        <!-- Judul Manga -->
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col justify-center items-center p-4 border mx-4 mb-4 rounded-md border-white">
                <Link :href="'/manga/' + slug" class="text-white text-2xl font-bold">
                <h1 class="text-2xl font-bold mb-2 text-center">{{ manga.attributes.title.en }} - Chapter
                    {{ content.attributes.chapter }}</h1>
                </Link>
                <!-- List Chapter with select option -->
                <div class="flex flex-row justify-center items-center" v-if="listChapter.length > 0">
                    <select class="bg-gray-800 text-white border border-white rounded-md p-2 px-4 mx-2 w-[60vw]"
                        v-model="ch">
                        <option v-for="chapter in listChapter" :value="chapter.id">Chapter {{ chapter.attributes.chapter
                        }}</option>
                    </select>
                    <button class="bg-gray-800 text-white border border-white rounded-md p-2"
                        @click="goToChapter">Go</button>
                </div>
            </div>
            <div v-if="images != ''">
                <div v-for="(gambar, index) in images.chapter.dataSaver" :key="index">
                    <!-- <img :src="'/data-saver/' + images.baseUrl.replace('https://', '') + '/data-saver/' + images.chapter.hash + '/' + gambar"
                        class="w-full h-auto" /> -->
                    <img :src="'https://i0.wp.com/' + images.baseUrl.replace('https://', '') + '/data-saver/' + images.chapter.hash + '/' + gambar"
                        class="w-full h-auto" />
                </div>
            </div>

            <div v-else-if="images == null">
                <div class="flex justify-center items-center h-[85vh]">
                    <p class="text-2xl">Images Not Found</p>
                </div>
            </div>

            <div v-else>
                <div id="loader" class="flex flex-col items-center justify-center h-[85vh]">
                    <svg aria-hidden="true"
                        class="mr-2 w-16 h-16 mb-4 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                        viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                            fill="currentColor" />
                        <path
                            d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                            fill="currentFill" />
                    </svg>
                    <span class="sr-only">Loading...</span>
                    <p class="">Sedang memuat gambar</p>
                </div>
            </div>

            <div class="flex flex-col mt-5 justify-center items-center p-4 border mx-4 mb-4 rounded-md border-white">
                <Link :href="'/manga/' + slug" class="text-white text-2xl font-bold">
                <h1 class="text-2xl font-bold mb-2 text-center">{{ manga.attributes.title.en }} - Chapter
                    {{ content.attributes.chapter }}</h1>
                </Link>
                <!-- List Chapter with select option -->
                <div class="flex flex-row justify-center items-center" v-if="listChapter.length > 0">
                    <select class="bg-gray-800 text-white border border-white rounded-md p-2 px-4 mx-2 w-[60vw]"
                        v-model="ch">
                        <option v-for="chapter in listChapter" :value="chapter.id">Chapter {{ chapter.attributes.chapter
                        }}</option>
                    </select>
                    <button class="bg-gray-800 text-white border border-white rounded-md p-2"
                        @click="goToChapter">Go</button>
                </div>
            </div>

            <div id="next-chapter" v-if="nextChapter != ''" class="mt-5 flex justify-center items-center">
                <a :href="nextChapter.id != 'undefined' ? '/manga/' + slug + '/' + nextChapter.id : '#'"
                    class="flex justify-center">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Next Chapter
                    </button>
                </a>
            </div>
            <div v-else class="mt-5 flex justify-center items-center">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    This is the last chapter
                </button>
            </div>

            <div class="mt-5 justify-center items-center w-full px-5">
                <div id="disqus_thread" class="w-full"></div>
            </div>
        </div>
        <div v-if="images != ''" class="fixed bottom-0 left-0 h-3 bg-blue-600 z-50" style="" id="progress">
        </div>
    </Content>
</template>