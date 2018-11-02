

const queryBuilder = {
    install(Vue, options) {
        Vue.prototype.$queryBuilder = function (page, perpage, operator, column, value, filters) {            
            let query = '?';
            query += page != null ? `page=${page}` : '';
            query += perpage != null ? `&perpage=${perpage}` : '';
            query += operator != undefined ? `&${operator}=${column},${value}` : '';            
            filters.forEach(item => {
                query += `&${item.opt}=${item.col},${item.val}`;
            });            
            return query;
        }
    }
}

export default queryBuilder;