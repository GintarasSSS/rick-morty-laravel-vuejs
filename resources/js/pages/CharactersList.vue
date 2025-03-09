<template>
    <div>
        <TheSpinner v-if="loading"/>
        <div v-else>
            <div v-if="characters.length">
                <div class="row">
                    <div
                        v-for="character in characters"
                        :key="character.id"
                        class="col-md-6 col-12 g-3"
                    >
                        <ListCard
                            :character="character"
                        />
                    </div>
                </div>

                <BPagination
                    v-if="rows"
                    v-model="currentPage"
                    :total-rows="rows"
                    :per-page="perPage"
                    first-number
                    last-number
                    align="center"
                    class="mt-3"
                    @update:model-value="handlePageChange"
                />
            </div>
            <div v-else>
                <h2>No Characters found.</h2>
            </div>
        </div>
    </div>
</template>

<script>
    import { ref, computed, onMounted, watch } from "vue";
    import { useStore } from "vuex";
    import { useRoute, useRouter } from "vue-router";

    import TheSpinner from "../components/layout/TheSpinner.vue";
    import ListCard from "../components/layout/ListCard.vue";

    export default {
        name: "CharactersList",
        components: {
            TheSpinner,
            ListCard
        },
        setup() {
            const store = useStore();
            const router = useRouter();
            const route = useRoute();

            const currentPage = ref(route.query.page || 1);
            const perPage = ref(20);
            const loading = ref(true);

            const showPagination = computed(() => store.getters['characters/pagesCount'] > 1);
            const rows = computed(() => store.getters['characters/rowsCount']);
            const characters = computed(() => store.getters['characters/charactersList']);

            const loadCharacters = () => {
                loading.value = true;
                store
                    .dispatch("characters/loadCharacters", { page: currentPage.value })
                    .finally(() => { loading.value = false; });
            }

            const handlePageChange = (page) => {
                currentPage.value = page;

                router.push({
                    query: { ...route.query, page: page.toString() }
                });
            }

            watch(currentPage, () => {
                loadCharacters();
            });

            watch(route, (newRoute) => {
                if (newRoute.query.page) {
                    currentPage.value = parseInt(newRoute.query.page);
                } else {
                    currentPage.value = 1;
                }
            });

            onMounted(() => {
                handlePageChange(route.query.page || 1);
                loadCharacters();
            });

            return {
                currentPage,
                perPage,
                rows,
                showPagination,
                loading,
                characters,
                handlePageChange
            }
        }
    }
</script>
