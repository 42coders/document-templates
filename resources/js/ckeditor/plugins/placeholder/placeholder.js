import Plugin from '@ckeditor/ckeditor5-core/src/plugin';

import PlaceholderEditing from './placeholderediting';
import PlaceholderUI from './placeholderui';

export default class Placeholder1 extends Plugin {
    static get requires() {
        return [ PlaceholderEditing, PlaceholderUI ];
    }
}