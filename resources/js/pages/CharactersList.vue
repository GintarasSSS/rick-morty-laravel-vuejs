<template>
    <div>
        <TheSpinner v-if="loading"/>
        <div v-else>
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
    </div>
</template>

<script>
    import { ref, computed, onMounted, watch } from "vue";
    import { useStore } from "vuex";
    import { useRoute, useRouter } from "vue-router";

    import TheSpinner from "../components/layout/TheSpinner.vue";

    export default {
        name: "CharactersList",
        components: {
            TheSpinner
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
                loadCharacters();
                handlePageChange(1);
            });

            return {
                currentPage,
                perPage,
                rows,
                showPagination,
                loading,
                handlePageChange
            }
        }
    }
</script>
