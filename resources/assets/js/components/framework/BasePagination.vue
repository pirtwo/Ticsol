<template>
    <div class="wrap float-right">
        <button @click="onBack" class="btn btn-sm btn-light">
          <i class="material-icons">keyboard_arrow_left</i>
        </button>
        <div>{{ this.page }} : {{ pageCount }}</div>
        <button @click="onForward" class="btn btn-sm btn-light">
          <i class="material-icons">keyboard_arrow_right</i>
        </button>
    </div>    
</template>

<script>
export default {
  name: "PaginationView",

  data() {
    return {
      page: this.value
    };
  },

  props: {
    pageCount: {
      type: Number,
      default: 5
    },
    value: {
      type: Number,
      default: 1
    }
  },

  methods: {
    onForward() {
      console.log("forw");
      this.page = this.page < this.pageCount ? this.page + 1 : this.page;
      this.$emit("input", this.page);
    },

    onBack() {
      console.log("back");
      this.page = this.page > 1 ? this.page - 1 : this.page;
      this.$emit("input", this.page);
    }
  }
};
</script>

<style scoped>
.wrap {
}

.btn {
  line-height: 1;  
  display: inline-block;
  padding: 0.25rem 0.25rem;
}

.btn i {
  font-size: 1.2rem;
}

div {
  padding: 2px;
  font-size: 12px;
  display: inline-block;
}
</style>
