

const formFeedback = {
    install(Vue, options) {

        Vue.prototype.$formFeedback = function (invalidFeedbacks, validFeedbacks = {}) {
            
            $("input").removeClass("is-valid is-invalid");
            $("div.valid-feedback").remove();
            $("div.invalid-feedback").remove();

            Object.keys(validFeedbacks).forEach(el => {
                let id = "#" + el;                
                $(id).addClass("is-valid");
                $(id).parent().append("<div class='valid-feedback'>" + validFeedbacks[el] + "</div>");
            });

            Object.keys(invalidFeedbacks).forEach(el => {
                let id = "#" + el;                
                $(id).addClass("is-invalid");
                $(id).parent().append("<div class='invalid-feedback'>" + invalidFeedbacks[el] + "</div>");
            });
        }

    }
}

export default formFeedback;