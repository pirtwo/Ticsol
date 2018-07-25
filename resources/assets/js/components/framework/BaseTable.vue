<template>

  <table>
    <thead>
      <slot name="toolbar">

      </slot>
      <th v-if="selection">
        <input type="checkbox" @click.self="onSelectAll">
      </th>

      <th v-for="(value, index) in header" :key="index" @click="toggleOrder(index, $event)">
        <slot name="header" :item="value">
          {{ value }}
          <!-- fallback content -->
        </slot>        
        <i v-show="sortBy == value.orderBy" class="material-icons">{{ colOrder === "asc" ? "arrow_upward" : "arrow_downward"}}</i>        
      </th>

    </thead>

    <tbody>
      <tr v-for="(value, index) in list" v-bind:key="index">
        <td v-if="selection">
          <input type="checkbox" @click="onSelect(index, $event)">
        </td>
        <slot name="body" :item="value">
          {{ value }}
        </slot>        
      </tr>
    </tbody>

  </table>

</template>

<script>
export default {
  name: "BaseTable",
  props: {
    data: {
      type: Array,
      default: null
    },
    header: {
      type: Array,
      default: null
    },
    orderBy: {
      type: String,
      default: null
    },
    order: {
      type: String,
      default: "asc",
      validator: value => {
        return ["asc", "des"].indexOf(value) !== -1;
      }
    },
    selection: {
      type: Boolean,
      default: false
    },
    value: {
      type: [Array, Object],
      default: null
    }
  },

  data() {
    return {
      selects: [],
      sortBy: this.orderBy,
      colOrder: this.order
    };
  },

  computed: {
    list: function() {
      return this.data.sort((a, b) => {
        if (this.colOrder === "asc") {
          if (a[this.sortBy] < b[this.sortBy]) return -1;
          if (a[this.sortBy] > b[this.sortBy]) return 1;
          return 0;
        } else {
          if (a[this.sortBy] < b[this.sortBy]) return 1;
          if (a[this.sortBy] > b[this.sortBy]) return -1;
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
        this.selects.splice(el => el === data[index], 1);
      }

      console.log(this.selects);
      this.$emit("input", this.selects);
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
      this.$emit("input", this.selects);
    },

    toggleOrder(index, e) {
      let orderby = "";

      if (e.target.tagName === "TH") {
        orderby = $(e.target)
          .find("[data-orderBy]")
          .data().orderby;
      } else {
        orderby = $(e.target)
          .closest("th")
          .find("[data-orderBy]")
          .data().orderby;
      }

      if (this.colOrder === "asc") {
        this.colOrder = "des";
      } else {
        this.colOrder = "asc";
      }

      this.sortBy = orderby;
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