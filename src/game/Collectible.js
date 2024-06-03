import { GameObject } from './GameObject.js';

export class Collectible extends GameObject {
    constructor(x, y, width, height) {
        super(x, y, width, height, 'green');
    }
}
