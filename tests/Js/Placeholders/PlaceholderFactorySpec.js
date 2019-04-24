import PlaceholderFactory from "../../../resources/js/ckeditor/plugins/placeholder_select/PlaceholderFactory";
import Placeholder from "../../../resources/js/ckeditor/plugins/placeholder_select/Placeholder";
import IterablePlaceholder from "../../../resources/js/ckeditor/plugins/placeholder_select/IterablePlaceholder";


describe("Placeholder factory test", function () {
    it("should create instances of placeholders", function () {

        let data = {"name": "test", "type": "single", "placeholders": ["test.title", "test.name"]},
            placeholders = PlaceholderFactory.build(data);

        expect(placeholders.length).toBe(2);
        expect(placeholders[0] instanceof Placeholder).toBe(true);
    });
});

describe("Placeholder factory test", function () {
    it("should create instances of iterable placeholders", function () {

        let data = {"name": "users", "type": "iterable", "placeholders": ["user.name", "user.email", "user.password"]},
            placeholders = PlaceholderFactory.build(data);

        expect(placeholders.length).toBe(4);
        expect(placeholders[0] instanceof IterablePlaceholder).toBe(true);
        expect(placeholders[1] instanceof Placeholder).toBe(true);
    });
});