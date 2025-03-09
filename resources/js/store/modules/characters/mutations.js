export default {
    setCharacter (state, characters) {
        state.characters = characters.results;
    },
    setRows (state, rows) {
        state.rows = rows;
    },
    setPages (state, pages) {
        state.pages = pages;
    }
};
