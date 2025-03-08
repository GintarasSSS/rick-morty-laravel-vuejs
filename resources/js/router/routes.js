import CharactersList from "../pages/CharactersList.vue";
import TheCharacter from "../pages/TheCharacter.vue";
import NotFound from "../pages/NotFound.vue";

const routes = [
    { name: 'home', path: '/', redirect: '/characters' },
    { name: 'characters', path: '/characters', component: CharactersList },
    { name: 'character', path: '/characters/:id', component: TheCharacter },
    { name: 'notfound', path: '/:notFound(.*)', component: NotFound }
];

export default routes;
