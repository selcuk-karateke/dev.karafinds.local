import { GameObject } from './GameObject.js';

export class Obstacle extends GameObject {
    constructor(x, y, width, height) {
        super(x, y, null, width, height, 'red');
    }
}
