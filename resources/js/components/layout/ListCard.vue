<template>
    <BCard no-body class="overflow-hidden shadow">
        <BRow class="g-0">
            <BCol md="6">
                <BCardImg lazy :src="character.image" alt="Image" class="rounded-0" />
            </BCol>
            <BCol md="6">
                <BCardBody :title="character.name">
                    <BCardText>
                        <strong class="d-block mb-2">{{ character.status }} - {{ character.species }}</strong>
                        Last known location:
                        <strong class="d-block mb-2">{{ character.location.name }}</strong>
                        Origin:
                        <strong class="d-block mb-3">{{ character.origin.name }}</strong>

                        <router-link :to="{ name: 'character', params: { id: character.id } }">
                            <BButton variant="primary">Character Details</BButton>
                        </router-link>
                    </BCardText>
                </BCardBody>
            </BCol>
        </BRow>
    </BCard>
</template>

<script>
    export default {
        name: "ListCard",
        props: {
            character: {
                type: Object,
                required: true
            }
        }
    }
</script>
