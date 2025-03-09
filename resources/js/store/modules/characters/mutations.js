export default {
    setCharacters (state, characters) {
        state.characters = characters.results;
    },
    setRows (state, rows) {
        state.rows = rows;
    },
    setPages (state, pages) {
        state.pages = pages;
    },
    setCharacter (state, character) {
        state.character = character;
    }
};
