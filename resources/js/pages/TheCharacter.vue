<template>
    <TheSpinner v-if="loading"/>
    <div v-else>
        <div v-if="Object.keys(character).length" class="pb-5">
            <h1 class="mb-3">{{ character.name }}</h1>
            <ItemCard :character="character" />
        </div>
        <div v-else>
            <h2>Character not found.</h2>
        </div>
    </div>
</template>

<script>
    import { computed, onMounted, ref } from "vue";
    import { useStore } from "vuex";
    import { useRoute } from "vue-router";

    import TheSpinner from "../components/layout/TheSpinner.vue";
    import ItemCard from "../components/layout/ItemCard.vue";

    export default {
        name: "TheCharacter",
        components: {
            TheSpinner,
            ItemCard
        },
        setup() {
            const store = useStore();
            const router = useRoute();

            const loading = ref(true);
            const character = computed(() => store.getters['characters/getCharacter']);

            onMounted(() => {
                store
                    .dispatch("characters/loadCharacter", { id: router.params.id })
                    .finally(() => { loading.value = false; });
            });

            return {
                loading,
                character
            }
        }
    }
</script>
