<template>
  <div>
    <table :class="cssClass">
      <thead>
        <slot name="toolbar" />
        <th v-if="selection">
          <input
            type="checkbox"
            @click.self="onSelectAll"
          >
        </th>

        <th
          v-for="(header, index) in headers"
          :key="index"
          @click="toggleOrder(header, index, $event)"
        >
          <slot
            name="header"
            :item="header"
          >
            {{ header }}
            <!-- fallback content -->
          </slot>
          <i
            v-show="header.value === tableOrderby"
            class="material-icons"
          >{{ columnOrder === "asc" ? "arrow_upward" : "arrow_downward" }}</i>
        </th>
      </thead>

      <tbody>
        <tr v-show="records.length < 1">
          <td :colspan="headers.length + 1">
            No Record
          </td>
        </tr>
        <tr
          v-for="(row, index) in records"
          :key="index"
        >
          <td v-if="selection">
            <input
              type="checkbox"
              :value="row"
              @input="onSelect(index, $event)"
            >
          </td>
          <slot
            name="body"
            :item="row"
          >
            {{ row }}
          </slot>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "TsTable",
  props: {
    value: {
      type: [Array, Object],
      default: null
    },

    data: {
      type: Array,
      default: null
    },

    cssClass:{
      type: String,
      default: "table table-striped table-hover"
    },

    headers: {
      type: Array,
      default: null
    },

    selection: {
      type: Boolean,
      default: false
    },

    orderby: {
      type: String,
      default: null
    },

    order: {
      type: String,
      default: "asc",
      validator: value => {
        return ["asc", "des"].indexOf(value) !== -1;
      }
    }
  },

  data() {
    return {
      selects: [],
      columnOrder: this.order,
      columnOrderby: this.orderby ? this.headers.find(i => i.value === this.orderby).orderby : () => {
        return "";
      },
      tableOrderby: this.orderBy
    };
  },

  watch: {
    value: function(val) {
      if (!val) {
        this.selects = [];
        $("input[type='checkbox']").prop("checked", false);
      }
    }
  },

  computed: {
    records: function() {
      return this.data.slice().sort((a, b) => {
        if (this.columnOrder === "asc") {
          if (this.columnOrderby(a) < this.columnOrderby(b)) return -1;
          if (this.columnOrderby(a) > this.columnOrderby(b)) return 1;
          return 0;
        } else {
          if (this.columnOrderby(a) < this.columnOrderby(b)) return 1;
          if (this.columnOrderby(a) > this.columnOrderby(b)) return -1;
          return 0;
        }
      });
    }
  },

  methods: {
    onSelect(index, e) {
      if ($(e.target).prop("checked")) {
        this.selects.push(this.data[index]);
      } else {
        this.selects.splice(el => el === this.data[index], 1);
      }

      this.$emit("input", this.selects.slice());
    },

    onSelectAll(e) {
      if ($(e.target).prop("checked")) {
        $("input[type='checkbox']").prop("checked", true);
        this.selects = [];
        this.data.forEach(el => {
          this.selects.push(el);
        });
      } else {
        $("input[type='checkbox']").prop("checked", false);
        this.selects = [];
      }
      this.$emit("input", this.selects.slice());
    },

    toggleOrder(header, index, e) {
      if(!header.value) return;
      this.tableOrderby = header.value;
      this.columnOrderby = header.orderby;
      this.columnOrder = this.columnOrder === "asc" ? "des" : "asc";
    }
  }
};
</script>

<style scoped>
th {
  cursor: pointer;
}

th div {
  display: inline-block;
}

th i {
  font-size: 0.9;
  vertical-align: bottom;
}
</style>