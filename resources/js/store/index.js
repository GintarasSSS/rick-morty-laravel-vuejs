import { createStore } from "vuex";
import characters from "./modules/characters/index.js";

export default createStore({
    modules: {
        characters
    }
});
