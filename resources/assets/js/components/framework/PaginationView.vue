<template>
    <div id="wrap-pagination" class="wrap-pagination">
        <ul class="pagination success no-gap">
            <li class="page-item service">
              <a class="page-link" href="#" @click.stop="handleClick">Prev</a>
            </li>
            <li v-for="(value, index) in pageList" 
                :key="index"                                  
                v-bind:class="classObj(value, index)">
                <a class="page-link" href="#" @click.stop="handleClick">{{ value }}</a>
            </li>
            <li class="page-item service">
              <a class="page-link" href="#" @click.stop="handleClick">Next</a>
            </li>        
        </ul>
    </div>    
</template>

<script>
export default {
  name: "PaginationView",
  data() {
    return {
      page: this.currentPage
    };
  },
  props: {
    baseUrl: {
      type: String,
      default: "#"
    },
    urlFormat: {
      type: String,
      default: "$base?page=$page"
    },
    pageCount: {
      type: Number,
      default: 5
    },
    currentPage: {
      type: Number,
      default: 1
    }
  },
  computed: {
    pageList: function() {
      let list = [];
      let count = this.pageCount;
      let current = this.page;

      if (current > 2) {
        list.push(1);
        list.push(2);
        if (current > 4) list.push("...");
      }

      if (current - 1 >= 1 && current != 3) list.push(current - 1);
      list.push(current);
      if (current + 1 <= count) list.push(current + 1);
      if (current + 2 == count) list.push(current + 2);

      if (current + 3 <= count) {
        if (current + 4 <= count) list.push("...");
        list.push(count - 1);
        list.push(count);
      }
      
      return list;
    }
  },
  watch: {
    page: function() {
      this.$emit("changePage", this.page);
    }
  },
  methods: {
    handleClick(e) {
      if (e.target.innerHTML == "Next") {
        this.page =
          this.page + 1 < this.pageCount ? this.page + 1 : this.pageCount;
        e.preventDefault();
        return;
      }
      if (e.target.innerHTML == "Prev") {
        this.page = this.page - 1 > 0 ? this.page - 1 : 1;
        e.preventDefault();
        return;
      }
      this.page =
        e.target.innerHTML != "..." ? parseInt(e.target.innerHTML) : this.page;
      e.preventDefault();
    },
    classObj(value, index) {
      return {
        active: parseInt(value) == this.page,
        "no-link": value == "...",
        "page-item": true
      };
    }
  }
};
</script>

<style scoped>
.wrap-pagination {
  position: absolute;
  bottom: 1px;
  right: 1px;
}
.wrap-pagination ul {
  margin: 0px;
}
</style>
