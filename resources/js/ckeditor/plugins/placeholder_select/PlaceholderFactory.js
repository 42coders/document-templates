import Placeholder from "./Placeholder";
import IterablePlaceholder from "./IterablePlaceholder";

export default class PlaceholderFactory{

    static build(data) {
        var placeholders = [];

        switch (data.type) {
            case 'iterable':
                var name = data.placeholders[0].split('.')[0] + ' in ' + data.name;
                placeholders.push(new IterablePlaceholder(name));
                break;
        }
        data.placeholders.forEach((placeholder, index) => {
            placeholders.push(new Placeholder(placeholder));
        });

        return placeholders;
    }
}