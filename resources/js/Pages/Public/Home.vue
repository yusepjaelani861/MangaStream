<script setup>
import Content from '@/Components/Content.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import Card from '@/Components/Card.vue';
import axios from 'axios';

</script>

<script>
export default {
    data() {
        return {
            data: [],
            manga: [],
            offset: 0,
            page: this.$page.url.split('?page=')[1] ?? 1,
            infinityloading: false,
        }
    },
    mounted() {
        console.log(this.page)

        window.onscroll = () => {
            var bottomOfWindow = document.documentElement.scrollTop + window.innerHeight === document.documentElement.offsetHeight;
            if (bottomOfWindow) {
                this.infinityloading = true;
                console.log('bottom')
                // this.manga = [];
                let params = {
                    'limit': 16,
                };
                // convert params to query string
                let query = Object.keys(params)
                    .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
                    .join('&');
                axios.get(`/api/manga?${query}&offset=${this.offset}`)
                    .then(response => {
                        // this.manga = response.data.data;
                        // this data push
                        this.manga.push(...response.data.data);
                        this.offset += response.data.limit;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }

        let params = {
            'limit': 16,
            'offset': this.page * 16,
        };
        // convert params to query string
        let query = Object.keys(params)
            .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
            .join('&');
        axios.get(`/api/manga?${query}`)
            .then(response => {
                this.manga = response.data.data;
                this.offset += response.data.limit;
                console.log(this.offset)
                // console.log(response.data.data)
                // console.log(this.data.data)
            })
            .catch(error => {
                console.log(error);
            });
    },
    methods: {
        prevPage() {
            let pages = this.page - 1;
            window.history.pushState({}, '', `/?page=${pages}`);
            this.fetchData();
        },
        nextPage() {
            this.page = this.page + 1;
            console.log(this.page)
            window.history.pushState({}, '', `=${this.page}`);
            // this.fetchData();
        },
        fetchData() {
            this.manga = [];
            let params = {
                'limit': 16,
            };
            // convert params to query string
            let query = Object.keys(params)
                .map(k => encodeURIComponent(k) + '=' + encodeURIComponent(params[k]))
                .join('&');
            axios.get(`/api/manga?${query}&offset=${this.offset}`)
                .then(response => {
                    this.manga = response.data.data;
                    this.offset += response.data.limit;
                })
                .catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>

<template>

    <Head>
        <title>Home</title>
    </Head>

    <Content>
        <div class="md:h-[370px] h-full flex flex-col justify-center object-cover mx-0 mt-10">
            <div class="bg-[url('/images/cover1.png');] p-5 md:h-[350px] w-screen h-full flex flex-col items-center">
                <div class="flex justify-between items-start bg-gray-600 bg-opacity-60 p-5">
                    <div class="mr-4 w-full">
                        <h1 class="text-white text-2xl font-bold mb-2">
                            En no Shita no Chikaramocha Bahasa Indonesia
                        </h1>
                        <div id="rating" class="flex items-center">
                            <div v-for="rate in 3" :v-key="rate" class="mr-1">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.65311 2.33265C7.6813 2.26387 7.72932 2.20503 7.79105 2.16362C7.85279 2.12221 7.92544 2.1001 7.99978 2.1001C8.07411 2.1001 8.14676 2.12221 8.2085 2.16362C8.27023 2.20503 8.31825 2.26387 8.34644 2.33265L9.76311 5.73998C9.78963 5.80374 9.83322 5.85894 9.88909 5.89952C9.94496 5.9401 10.0109 5.96448 10.0798 5.96998L13.7584 6.26465C14.0911 6.29131 14.2258 6.70665 13.9724 6.92331L11.1698 9.32465C11.1174 9.36944 11.0784 9.42778 11.057 9.49328C11.0356 9.55878 11.0326 9.62891 11.0484 9.69598L11.9051 13.286C11.9223 13.358 11.9178 13.4335 11.8921 13.503C11.8665 13.5724 11.8208 13.6327 11.7609 13.6763C11.7009 13.7198 11.6295 13.7445 11.5555 13.7475C11.4815 13.7504 11.4083 13.7313 11.3451 13.6926L8.19511 11.7693C8.13627 11.7335 8.06869 11.7145 7.99978 11.7145C7.93086 11.7145 7.86328 11.7335 7.80444 11.7693L4.65444 13.6933C4.59128 13.732 4.51808 13.7511 4.44408 13.7481C4.37008 13.7452 4.29861 13.7204 4.23869 13.6769C4.17877 13.6334 4.13308 13.5731 4.10741 13.5036C4.08174 13.4342 4.07722 13.3587 4.09444 13.2866L4.95111 9.69598C4.967 9.62891 4.96408 9.55876 4.94267 9.49325C4.92126 9.42773 4.8822 9.36939 4.82978 9.32465L2.02711 6.92331C1.97098 6.87505 1.93038 6.81128 1.91041 6.74C1.89043 6.66873 1.89198 6.59314 1.91485 6.52274C1.93772 6.45235 1.9809 6.39028 2.03895 6.34436C2.097 6.29844 2.16734 6.2707 2.24111 6.26465L5.91978 5.96998C5.98861 5.96448 6.05459 5.9401 6.11046 5.89952C6.16633 5.85894 6.20992 5.80374 6.23644 5.73998L7.65311 2.33331V2.33265Z"
                                        fill="#FDE047" stroke="#FDE047" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div v-for="rate in 2" :v-key="rate" class="mr-1">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6.65311 1.33265C6.6813 1.26387 6.72932 1.20503 6.79105 1.16362C6.85279 1.12221 6.92544 1.1001 6.99978 1.1001C7.07411 1.1001 7.14676 1.12221 7.2085 1.16362C7.27023 1.20503 7.31825 1.26387 7.34644 1.33265L8.76311 4.73998C8.78963 4.80374 8.83322 4.85894 8.88909 4.89952C8.94496 4.9401 9.01094 4.96448 9.07978 4.96998L12.7584 5.26465C13.0911 5.29131 13.2258 5.70665 12.9724 5.92331L10.1698 8.32465C10.1174 8.36944 10.0784 8.42778 10.057 8.49328C10.0356 8.55878 10.0326 8.62891 10.0484 8.69598L10.9051 12.286C10.9223 12.358 10.9178 12.4335 10.8921 12.503C10.8665 12.5724 10.8208 12.6327 10.7609 12.6763C10.7009 12.7198 10.6295 12.7445 10.5555 12.7475C10.4815 12.7504 10.4083 12.7313 10.3451 12.6926L7.19511 10.7693C7.13627 10.7335 7.06869 10.7145 6.99978 10.7145C6.93086 10.7145 6.86328 10.7335 6.80444 10.7693L3.65444 12.6933C3.59128 12.732 3.51808 12.7511 3.44408 12.7481C3.37008 12.7452 3.29861 12.7204 3.23869 12.6769C3.17877 12.6334 3.13308 12.5731 3.10741 12.5036C3.08174 12.4342 3.07722 12.3587 3.09444 12.2866L3.95111 8.69598C3.967 8.62891 3.96408 8.55876 3.94267 8.49325C3.92126 8.42773 3.8822 8.36939 3.82978 8.32465L1.02711 5.92331C0.970984 5.87505 0.930382 5.81128 0.910408 5.74C0.890434 5.66873 0.89198 5.59314 0.914852 5.52274C0.937724 5.45235 0.980901 5.39028 1.03895 5.34436C1.097 5.29844 1.16734 5.2707 1.24111 5.26465L4.91978 4.96998C4.98861 4.96448 5.05459 4.9401 5.11046 4.89952C5.16633 4.85894 5.20992 4.80374 5.23644 4.73998L6.65311 1.33331V1.33265Z"
                                        fill="#F3F4F6" stroke="#F3F4F6" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <p class="text-md text-gray-200 ml-1">9.5</p>
                        </div>
                        <p class="">
                            Sakuya, seorang Prajurit Monk yang tujuan hidupnya adalah untuk “membantu sang pahlawan
                            dari
                            balik bayang-bayang”, menerima kutukan setelah mengalahkan Raja Iblis, dan ditakdirkan
                            untuk
                            melawan sejumlah besar iblis di “Penjara Kematian” , tetapi setelah waktu terus berlalu,
                            dia
                            berhasil keluar dan menyadari bahwa dia telah kembali dalam keadaan remaja. Dia ingin
                            menjadi seseorang...
                        </p>
                        <div class="mt-8 h-full w-1/2 flex items-start justify-start"></div>
                        <button class="bg-indigo-500 hover:bg-indigo-700 rounded-full p-2 px-4 md:block hidden">
                            Baca Sekarang
                        </button>
                    </div>
                    <div class="h-[250px] w-[250px] md:block hidden">
                        <img src="/images/cover1.png" class="object-cover" />
                    </div>
                </div>
            </div>
            <!-- onclick carousel -->
            <div class="flex items-center -mt-10 justify-center">
                <button class="p-2 px-4">
                    <div class="rounded-full bg-white p-2">
                    </div>
                </button>
                <button class="p-2 px-4">
                    <div class="rounded-full bg-gray-500 p-2">
                    </div>
                </button>
                <button class="p-2 px-4">
                    <div class="rounded-full bg-white p-2">
                    </div>
                </button>
            </div>
        </div>

        <div id="content" class="md:container md:mx-auto md:px-4">
            <div class="flex flex-row justify-between items-center mt-10 px-5">
                <h1 class="md:text-3xl text-xl font-bold text-white">Latest Anime</h1>
                <Link href="/anime" class="text-white">See All</Link>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-4" v-if="manga.length > 0">
                <div v-for="manga in manga" :v-key="manga" class="w-full">
                    <Card :manga="manga" />
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

        <!-- Pagination -->
        <div class="flex justify-center mt-4" v-if="infinityloading == true">
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
        <!-- <div class="flex justify-center mt-4">
            <button @click="prevPage" v-if="page > 1"
                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-l">
                Prev
            </button>
            <button @click="nextPage"
                class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-r">
                Next
            </button>
        </div> -->
    </Content>
</template>