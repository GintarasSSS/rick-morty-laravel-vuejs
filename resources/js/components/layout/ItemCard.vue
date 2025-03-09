<template>
    <BCard no-body class="overflow-hidden shadow">
        <BCardImg lazy :src="character.image" alt="Image" class="rounded-0" />
        <BCardBody>
            <BCardText>
                <strong class="d-block mb-2">{{ character.status }} - {{ character.species }}</strong>
                Last known location:
                <strong class="d-block mb-2">{{ character.location.name }}</strong>
                Origin:
                <strong class="d-block mb-3">{{ character.origin.name }}</strong>
                Episodes:
                <ul v-if="character.episodes_extended" >
                    <li v-for="episode in character.episodes_extended" :key="episode.id">
                        <strong>{{ episode.name }}</strong>
                    </li>
                </ul>
            </BCardText>
        </BCardBody>

    </BCard>
</template>

<script>
    export default {
        name: "ItemCard",
        props: {
            character: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>
    img {
        max-height: 50vh;
        object-fit: contain;
        background-color: #2b2f32;
    }
</style>
