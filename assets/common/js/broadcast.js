class Broadcast {

    constructor(selector, path) {
        this.path = path;
        this.$handle = $(selector);

        const a = this.getPackage();
        a && this.$handle.val(a);
        this.$handle.change(function () {
            localStorage.setItem(path, this.value);
        });
    }

    

    getPackage() {
        const c = localStorage.getItem(this.path);
        if (c != "" && typeof c == "string") {
            return c;
        }
        return false;
    }

    

    play(name) {
        const pack = this.getPackage();
        if (pack) {
            util.loadSound(`${this.path}/${pack}/${name}.mp3`);
        }
    }
}