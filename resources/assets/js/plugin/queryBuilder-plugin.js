

const queryBuilder = {
    install(Vue, options) {
        Vue.prototype.$queryBuilder = function (page, perpage, withThe, filters) {
            let query = '?';
            query += page ? `page=${page}` : '';
            query += perpage ? `&perPage=${perpage}` : '';
            query += withThe.length > 0 ? `${page ? "&" : ""}with=${withThe.reduce((acc, cur) => acc + "," + cur)}` : '';
            filters.forEach(item => {
                query += `&${item.opt}=${item.col}${item.col ? "," : ""}${item.val}`;
            });
            return query;
        }
    }
}

export default queryBuilder;