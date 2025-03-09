<template>
    <TheHeader />
    <div class="container-xl">
        <router-view/>
    </div>
</template>

<script>
    import TheHeader from "./components/layout/TheHeader.vue";

    export default {
        name: "App",
        components: {
            TheHeader
        }
    }
</script>
