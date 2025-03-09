import axios from "axios";

export default {
    async loadCharacters({ commit }, page) {
        try {
            const response = await axios.get("/api/characters?page=" + page.page);

            if (response.status !== 200 || response.data.status !== 'success') {
                throw new Error('Error fetching characters.');
            }

            commit('setCharacter', response.data.characters);
            commit('setRows', response.data.characters.info.count);
            commit('setPages', response.data.characters.info.pages);
        } catch (er) {
            console.error('Error fetching characters.');
        }
    }
};
