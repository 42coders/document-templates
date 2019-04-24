import PlaceholderBase from './PlaceholderBase'

export default class Placeholder extends PlaceholderBase {

    getPlaceholder() {
        return '{{' + this.data + '}}'
    }

    getWidgets() {
    }
}