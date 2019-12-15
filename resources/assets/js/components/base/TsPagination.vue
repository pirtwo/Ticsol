<template>
  <div class="ts-pagination">
    <div class="d-flex align-items-center">
      <div class="ts-pagination__label">
        Go to:
      </div>
      <input
        ref="pageInput"
        class="ts-pagination__input"
        @input="dbInput"
        type="text"
        maxlength="3"
      >
      <div class="ts-pagination__label">
        : {{ pageCount }} &nbsp; &nbsp; Rows:
      </div>
      <select
        v-model="perPage"
        @change="perPageChange"
        class="ts-pagination__select"
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
      class="btn btn-sm ts-pagination__btn"
    >
      <i class="material-icons">keyboard_arrow_left</i>
    </button>
    <button
      @click="dbForward"
      class="btn btn-sm ts-pagination__btn"
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
      default: () => {
        return { page: 1, perPage: 10, pageCount: 1 };
      }
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
.ts-pagination {
  display: flex;
  align-items: stretch;
  margin: 0px 5px;
}

.ts-pagination__label {
  margin: 0px 3px;
}

.ts-pagination__input {
  width: 50px;
  height: 65%;
  padding: 5px;
}

.ts-pagination__btn {
  margin-left: 3px;
  font-size: inherit;
  display: flex;
  align-items: center;
}

.ts-pagination__btn i {
  font-size: inherit;
}
</style>
