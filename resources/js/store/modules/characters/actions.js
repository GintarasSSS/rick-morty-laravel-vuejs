import axios from "axios";

export default {
    async loadCharacters({ commit }, page) {
        try {
            const response = await axios.get("/api/characters?page=" + page.page);

            if (response.status !== 200 || response.data.status !== 'success') {
                throw new Error('Error fetching characters.');
            }

            commit('setCharacters', response.data.characters);
            commit('setRows', response.data.characters.info.count);
            commit('setPages', response.data.characters.info.pages);
        } catch (er) {
            console.error('Error fetching characters.');
        }
    },
    async loadCharacter({ commit }, id) {
        try {
            const response = await axios.get(`/api/characters/${id.id}`);

            if (response.status !== 200 || response.data.status !== 'success') {
                throw new Error('Error fetching character data.');
            }

            commit('setCharacter', response.data.character);
        } catch (er) {
            console.error('Error fetching character data.');
        }
    }
};
