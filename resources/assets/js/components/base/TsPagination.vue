<template>
  <div class="wrap-pagination">        
    <div>
      &nbsp; &nbsp; Go to: &nbsp;
      <input 
        ref="pageInput" 
        class="pag-input pag-text" 
        @input="dbInput" 
        type="text" 
        maxlength="3"
      > : {{ pageCount }}
      &nbsp; &nbsp; Rows: &nbsp;
      <select 
        v-model="perPage" 
        @change="perPageChange" 
        class="pag-input pag-select"
      >
        <option 
          value="10" 
          selected
        >
          10
        </option>
        <option value="20">
          20
        </option>
        <option value="50">
          50
        </option>
        <option value="100">
          100
        </option>
        <option value="200">
          200
        </option>
      </select>         
    </div>
    <button 
      @click="dbBack" 
      class="btn btn-sm btn-light"
    >
      <i class="material-icons">keyboard_arrow_left</i>
    </button>
    <button 
      @click="dbForward" 
      class="btn btn-sm btn-light"
    >
      <i class="material-icons">keyboard_arrow_right</i>
    </button>
  </div>    
</template>

<script>
import _ from "lodash";

export default {
  name: "TsPagination",

  data() {
    return {
      page: this.value.page,
      perPage: this.value.perPage
    };
  },

  props: {
    pageCount: {
      type: Number,
      default: 5
    },
    value: {
      type: Object,
      default:()=> {return{ page: 1, perPage: 10, pageCount: 1 }}
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
        this.$emit("input", {
          page: this.page,
          perPage: this.perPage,
          pageCount: this.pageCount
        });
      } else {
        event.target.value = this.page.toString();
        this.$emit("input", {
          page: this.page,
          perPage: this.perPage,
          pageCount: this.pageCount
        });
      }
    },

    onForward() {
      this.page = this.page < this.pageCount ? this.page + 1 : this.page;
      this.$refs.pageInput.value = this.page.toString();
      this.$emit("input", {
        page: this.page,
        perPage: this.perPage,
        pageCount: this.pageCount
      });
    },

    onBack() {
      this.page = this.page > 1 ? this.page - 1 : this.page;
      this.$refs.pageInput.value = this.page.toString();
      this.$emit("input", {
        page: this.page,
        perPage: this.perPage,
        pageCount: this.pageCount
      });
    },

    perPageChange() {      
      this.page = 1;
      this.$refs.pageInput.value = this.page.toString();
      this.$emit("input", {
        page: this.page,
        perPage: this.perPage,
        pageCount: this.pageCount
      });
    }
  }
};
</script>

<style scoped>
.btn {
  display: flex;
  line-height: 1;
  margin-right: 5px;
  padding: 0.25rem 0.25rem;
}

.btn i {
  font-size: 1.2rem;
}

div {
  display: flex;
  font-size: 12px;
  line-height: 1.8;  
  padding: 2px 4px;  
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
