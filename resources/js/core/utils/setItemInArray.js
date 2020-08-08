const setItemInArray = (n, ins, arr) => [...arr.slice(0, n), ins, ...arr.slice(n)]

export default setItemInArray;
