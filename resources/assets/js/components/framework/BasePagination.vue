<template>
    <div class="wrap-pagination">        
        <div>
          &nbsp; &nbsp; Go to: &nbsp;
          <input ref="pageInput" class="pag-input pag-text" @input="dbInput" type="text" maxlength="3"> : {{ pageCount }}
          &nbsp; &nbsp; Rows: &nbsp;
          <select class="pag-input pag-select">
            <option value="10">10</option>
            <option value="10">20</option>
            <option value="10">50</option>
            <option value="10">100</option>
            <option value="10">200</option>
          </select>         
        </div>
        <button @click="dbBack" class="btn btn-sm btn-light">
          <i class="material-icons">keyboard_arrow_left</i>
        </button>
        <button @click="dbForward" class="btn btn-sm btn-light">
          <i class="material-icons">keyboard_arrow_right</i>
        </button>
    </div>    
</template>

<script>
import _ from "lodash";

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

  created() {
    this.dbBack = _.debounce(this.onBack, 500);
    this.dbInput = _.debounce(this.onInput, 500);
    this.dbForward = _.debounce(this.onForward, 500);
  },

  mounted() {
    this.$refs.pageInput.value = this.page.toString();
  },

  methods: {
    onInput(event) {
      let value = parseInt(event.target.value);
      if (Number.isInteger(value) && value > 0 && value <= this.pageCount) {
        this.page = value;
        this.$emit("input", this.page);
      } else {
        event.target.value = this.page.toString();
        this.$emit("input", this.page);
      }
    },

    onForward() {
      this.page = this.page < this.pageCount ? this.page + 1 : this.page;
      this.$refs.pageInput.value = this.page.toString();
      this.$emit("input", this.page);
    },

    onBack() {
      this.page = this.page > 1 ? this.page - 1 : this.page;
      this.$refs.pageInput.value = this.page.toString();
      this.$emit("input", this.page);
    }
  }
};
</script>

<style scoped>
.btn {
  line-height: 1;
  display: flex;
  padding: 0.25rem 0.25rem;
}

.btn i {
  font-size: 1.2rem;
}

div {
  padding: 2px 4px;
  font-size: 12px;
  display: flex;
  line-height: 1.8;
}

.pag-input {
  height: 20px;
  margin-top: 2px;
  margin-right: 3px;
  border-radius: 2px;
  background-color: transparent;
  border: 1px solid #0000004d;
}

.pag-text {
  width: 35px;
  padding: 5px;
}

.pag-select {
  width: auto;
  padding: 0px;
}
</style>
