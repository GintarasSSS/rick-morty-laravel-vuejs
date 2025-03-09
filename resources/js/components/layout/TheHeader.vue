<template>
    <header class="pt-3 pb-3 bg-white shadow-sm mb-5">
        <div class="container-xl">
            <router-link :to="{ name: 'characters' }" custom v-slot="{ navigate }">
                <BButton variant="outline-primary" @click="navigate">Home</BButton>
            </router-link>
        </div>
    </header>
</template>

<script>
    export default {
        name: "TheHeader"
    }
</script>
