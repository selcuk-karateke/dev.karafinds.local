import { Game } from './src/game/Game.js';

const game = new Game();

// Event listener for using resources
document.getElementById('use-food').addEventListener('click', () => game.useResource('food'));
document.getElementById('use-water').addEventListener('click', () => game.useResource('water'));