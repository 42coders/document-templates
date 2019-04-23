import PlaceholderBase from './PlaceholderBase'
import Placeholder from './Placeholder'

export default class IterablePlaceholder extends PlaceholderBase {

    getPlaceholder() {
        return '{% for ' + this.data + ' %} <br/> {% endfor %}'
    }

    getWidgets() {
    }
}