
function uuid() {
    return (Math.floor(Math.random() * 1000000)).toString(36);
}

export default uuid;