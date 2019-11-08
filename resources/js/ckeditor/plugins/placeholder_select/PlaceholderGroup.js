import PlaceholderFactory from "./PlaceholderFactory";

export default class PlaceholderGroup {
    constructor(data) {
        this.name = data.name;
        this.placeholders = PlaceholderFactory.build(data);
    }

    getName() {
        return this.name
    }

    getPlaceholders() {
        return this.placeholders;
    }
}