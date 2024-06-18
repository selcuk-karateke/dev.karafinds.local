export function getRandomDivisibleBy20(number) {
    let maxDivisible = Math.floor(number / 20);
    return Math.floor(Math.random() * (maxDivisible + 1)) * 20;
}
