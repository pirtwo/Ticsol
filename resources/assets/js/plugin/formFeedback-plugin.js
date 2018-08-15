

const formFeedback = {
    install(Vue, options) {

        Vue.prototype.$formFeedback = function (invalidFeedbacks, validFeedbacks = {}) {

            $("input").removeClass("is-valid is-invalid");
            $("div.valid-feedback").remove();
            $("div.invalid-feedback").remove();
            try {
                if (validFeedbacks !== undefined) {
                    Object.keys(validFeedbacks).forEach(el => {
                        let id = "#" + el;
                        let name = `[name="${el}"]`;
                        let ref = $(id);

                        if (Object.keys(ref).length === 0)
                            ref = $(name);

                        if (Object.keys(ref).length !== 0) {
                            $(id).addClass("is-valid");
                            $(id).parent().append("<div class='valid-feedback'>" + validFeedbacks[el] + "</div>");
                        }
                    });
                }

                if (invalidFeedbacks !== undefined) {
                    Object.keys(invalidFeedbacks).forEach(el => {
                        let id = "#" + el;
                        let name = `[name="${el}"]`;
                        let ref = $(id);

                        if (Object.keys(ref).length === 0)
                            ref = $(name);

                        if (Object.keys(ref).length !== 0) {
                            $(ref).addClass("is-invalid");
                            $(ref).parent().append("<div class='invalid-feedback'>" + invalidFeedbacks[el] + "</div>");
                        }
                    });
                }
            } catch (e) {
                console.log(e);
            }
        }

    }
}

export default formFeedback;