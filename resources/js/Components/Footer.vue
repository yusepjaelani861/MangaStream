<script setup>

</script>

<script>
export default {
    data() {
        return {
            app_name: import.meta.env.VITE_APP_NAME,
        };
    }
}
</script>

<template>
    <footer class="bg-gray-800 text-white text-center py-4">
        <div class="container mx-auto">

            <p> &copy; Copyright 2022 - {{app_name}}. All right reserved.</p>
        </div>
    </footer>
</template>